<?php

namespace App\Http\Controllers\Admin;

use App\BodCandidate;
use App\Candidate;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmployerJob;
use App\BodCandidateReportLog;
use App\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class BodCandidateReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('bod_candidate_reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_id = auth()->user()->id;
        $roleName =  getUserRole($user_id);

        if ($roleName == 'Employer') {
            $jobs = EmployerJob::where('user_id', $user_id)->get();
        } else {
            $jobs = EmployerJob::all();
        }

        $candidates = BodCandidate::select('candidate_id', 'name')->get();

        $recruiterUsers = User::select('id', 'first_name')->whereHas('roles', function ($query) {
            $query->where('role_id', 1)->orwhere('role_id', 2);
        })
            ->get();

        $getAllData = BodCandidateReportLog::orderBy('id', 'desc')
            ->select(
                'id',
                'link',
                DB::raw('DATE_FORMAT(from_date, "%d-%m-%Y") as from_date'),
                DB::raw('DATE_FORMAT(to_date, "%d-%m-%Y") as to_date'),
                DB::raw('IFNULL(recruiter_name, "All") as recruiter_name'),
                DB::raw('IFNULL(candidate_name, "All") as candidate_name'),
                DB::raw('IFNULL(job_status, "All") as job_status'),
                DB::raw('IFNULL(job_title, "All") as job_title'),
                DB::raw('IFNULL(round_name, "All") as round_name'),
                DB::raw('IFNULL(round_status, "All") as round_status'),
                DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as created_at_formatted')
            )
            ->where('user_id', auth()->user()->id)
            ->get();

        return view('admin.bodCandidateReports.index', compact('jobs', 'getAllData', 'candidates', 'recruiterUsers'));
    }

    public function getJobs(Request $request)
    {
        $recruiter_id = "";
        if (!empty($request->recruiter_id)) {
            $recruiter_id = $request->recruiter_id;
        }
        $candidate_id = "";
        if (!empty($request->candidate_id)) {
            $candidate_id = $request->candidate_id;
        }

        $allJobs = EmployerJob::select('id', 'job_title')
            ->whereHas('appliedJobs', function ($query) use ($recruiter_id, $candidate_id) {
                if (!empty($recruiter_id)) {
                    $query->where('user_id', '=', $recruiter_id);
                }
                if (!empty($candidate_id)) {
                    $query->where('candidate_id', '=', $candidate_id);
                }
            });

        if (isset($request->start_date) && !empty($request->start_date)) {
            $startDate = Carbon::createFromFormat('d-m-Y', $request->start_date)->startOfDay()->format('Y-m-d');
            $allJobs->whereDate('job_start_date', '>=', $startDate);
        }

        if (isset($request->end_date) && !empty($request->end_date)) {
            $endDate = Carbon::createFromFormat('d-m-Y', $request->end_date)->startOfDay()->format('Y-m-d');
            $allJobs->whereDate('job_start_date', '<=', $endDate);
        }

        $allJobs = $allJobs->get()->toArray();

        return $allJobs;
    }

    public function store(Request $request)
    {

        $from_date = date('Y-m-d', strtotime($request->from_date));
        $to_date = date('Y-m-d', strtotime($request->to_date));

        $job_id = $request->job_id;
        $job_round_name = $request->job_round_name;
        $job_round_status = $request->job_round_status;
        $user_id = auth()->user()->id;

        $user_id = auth()->user()->id;
        $roleName =  getUserRole($user_id);

        if (!empty($job_id)) {
            $job_title = EmployerJob::where('id', $job_id)->value('job_title');
        } else {
            $job_title = "All";
        }

        $query = DB::table('applied_jobs as a')
            ->join('employer_jobs as e', 'e.id', '=', 'a.job_id')
            ->join('users as u', 'u.id', '=', 'a.user_id')
            ->join('bod_candidates as b', 'b.candidate_id', '=', 'a.candidate_id')
            ->join('candidate_job_status_comments as s', function ($join) {
                $join->on('s.job_id', '=', 'a.job_id')
                    ->on('s.candidate_id', '=', 'b.candidate_id');
            })

            ->select('e.job_title', 'e.status as job_status', 'u.first_name', 'b.name', 'b.location', 's.status', 's.field_status')
            ->whereBetween('e.job_start_date', [$from_date, $to_date]);

        if (isset($request->recruiter_id) && (empty($request->recruiter_id)) && (($roleName == 'Super Admin') || ($roleName == 'Admin'))) {
            $recruiter_id = "All";
        } else if (isset($request->recruiter_id) && (!empty($request->recruiter_id)) && (($roleName == 'Super Admin') || ($roleName == 'Admin'))) {
            $recruiter_id = $request->recruiter_id;
            $query->where('a.user_id', $recruiter_id);
        } else {
        }

        if (isset($request->candidate_id) && (empty($request->candidate_id)) && (($roleName == 'Super Admin') || ($roleName == 'Admin'))) {
            $candidate_id = "All";
        } else if (isset($request->candidate_id) && (!empty($request->candidate_id)) && (($roleName == 'Super Admin') || ($roleName == 'Admin'))) {
            $candidate_id = $request->candidate_id;
            $query->where('a.candidate_id', $candidate_id);
        } else {
        }


        if (!empty($job_id)) {
            $query->where('a.job_id', $job_id);
        }

        if (!empty($job_round_name)) {
            $query->where('s.status', $job_round_name);
        }

        if (!empty($job_round_status)) {
            $query->where('s.field_status', $job_round_status);
        }

        $joinResults = $query->orderBy('s.id', 'desc')->get();

        if (count($joinResults) == 0) {
            return response()->json(['status' => false, 'message' => 'No data found']);
        }

        $data = [];
        if (in_array($roleName, ['Super Admin', 'Admin'])) {
            $data[] = ['S.No', 'Recruiter Name', 'Job title', 'Job status', 'Candidate name', 'Candidate address', 'Job round name', 'Job round status'];
        } else {
            $data[] = ['S.No', 'Job title', 'Job status', 'Candidate name', 'Candidate address', 'Job round name', 'Job round status'];
        }

        if (!empty($joinResults)) {
            foreach ($joinResults as $key => $joinResult) {
                $row = [];
                $no = $key + 1;
                $row[] = $no;
                if (in_array($roleName, ['Super Admin', 'Admin'])) {
                    $row[] = $joinResult->first_name;
                }

                if($joinResult->status == 'None'){
                    $joinResult->status = 'Profile Received';
                }

                $row[] = $joinResult->job_title;
                $row[] = $joinResult->job_status;
                $row[] = $joinResult->name;
                $row[] = $joinResult->location;
                $row[] = $joinResult->status;
                $row[] = $joinResult->field_status;

                $data[] = $row;
            }
        }

        $excel_path =  $this->bodCandidateGenerateReportXlsx($data);

        $inserdata = [

            "from_date" => $from_date,
            "to_date" => $to_date,
            "job_status" =>  !empty($job_id) ? $joinResult->job_status : 'All',
            "job_title" => !empty($job_id) ? $joinResult->job_title : 'All',
            "recruiter_name" => !empty($request->recruiter_id) ? $joinResult->job_title : 'All',
            "candidate_name" => !empty($request->candidate_id) ? $joinResult->name : 'All',
            "round_name" => !empty($job_round_name) ? $joinResult->status : 'All',
            "round_status" => !empty($job_round_status) ? $joinResult->field_status : 'All',
            "user_id" => $user_id,
            "link" => $excel_path

        ];

        $inserReport = BodCandidateReportLog::create($inserdata);

        $getAllData = BodCandidateReportLog::orderBy('id', 'desc')
            ->select(
                'id',
                'link',
                DB::raw('DATE_FORMAT(from_date, "%d-%m-%Y") as from_date'),
                DB::raw('DATE_FORMAT(to_date, "%d-%m-%Y") as to_date'),
                DB::raw('IFNULL(recruiter_name, "All") as recruiter_name'),
                DB::raw('IFNULL(candidate_name, "All") as candidate_name'),
                DB::raw('IFNULL(job_status, "All") as job_status'),
                DB::raw('IFNULL(job_title, "All") as job_title'),
                DB::raw('IFNULL(round_name, "All") as round_name'),
                DB::raw('IFNULL(round_status, "All") as round_status'),
                DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as created_at_formatted')
            )
            ->where('user_id', auth()->user()->id)
            ->get();

        if ($inserReport) {
            return response()->json(['status' => true, 'data' => $getAllData, 'message' => 'Report generated successfully.']);
        } else {
            return response()->json(['status' => false, 'message' => 'somthing went wrong.']);
        }
    }

    public function bodCandidateGenerateReportXlsx($data)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray($data, null, 'A1');
        $highestRow = $spreadsheet->getActiveSheet()->getHighestRow(); // e.g. 10
        $highestColumn = $spreadsheet->getActiveSheet()->getHighestColumn(); // e.g 'F'

        $sheet->getProtection()->setSheet(true);
        foreach (range('A', $highestColumn) as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        $sheet->getStyle('A2:' . $highestColumn . $highestRow)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

        $writer = new Xlsx($spreadsheet);

        $currentTimestamp = now()->timestamp;
        $filename = 'genrate-report-' . $currentTimestamp . '.xlsx';

        if (!file_exists(public_path('/bod_candidate_report_generate/'))) {
            mkdir(public_path('/bod_candidate_report_generate/'));
        }
        $writer->save(public_path('/bod_candidate_report_generate/' . $filename));
        return '/bod_candidate_report_generate/' . $filename;
    }
}
