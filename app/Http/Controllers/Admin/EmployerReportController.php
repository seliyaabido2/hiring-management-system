<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\EmployerJob;
use App\EmployerReportLog;
use App\User;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class EmployerReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_if(Gate::denies('employer_reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $user_id = auth()->user()->id;
        $roleName =  getUserRole($user_id);

        if ($roleName == 'Employer') {
            $jobs = EmployerJob::where('user_id', $user_id)->get();
        } else {

            $jobs = EmployerJob::all();
        }

        $employerUsers = User::select('id','first_name')->whereHas('roles', function ($query)  {
                            $query->where('role_id',3);
                        })
                        ->get();
        $recruiterUsers = User::select('id','first_name')->whereHas('roles', function ($query)  {
                            $query->where('role_id',4);
                        })
                        ->get();

        $getAllData = EmployerReportLog::orderBy('id', 'desc')
            ->select(
                'id','link',
                DB::raw('DATE_FORMAT(from_date, "%d-%m-%Y") as from_date'),
                DB::raw('DATE_FORMAT(to_date, "%d-%m-%Y") as to_date'),
                DB::raw('IFNULL(employer_name, "All") as employer_name'),
                DB::raw('IFNULL(job_status, "All") as job_status'),
                DB::raw('IFNULL(job_title, "All") as job_title'),
                DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as created_at_formatted')
            )
            ->where('user_id',auth()->user()->id)
            ->get();


        return view('admin.employerReports.index', compact('jobs', 'getAllData','employerUsers','recruiterUsers'));
    }

    public function getJobs(Request $request)
    {
        $user_id = auth()->user()->id;
        $roleName =  getUserRole($user_id);

        $allJobs = EmployerJob::select('id', 'job_title');
            if(isset($request->employer_id) && !empty($request->employer_id)){
                $allJobs->where('user_id',$request->employer_id);
            }
            if(isset($request->start_date) && !empty($request->start_date)){
                $startDate = Carbon::createFromFormat('d-m-Y', $request->start_date)->startOfDay()->format('Y-m-d');
                $allJobs->whereDate('job_start_date', '>=', $startDate);
            }

            if(isset($request->end_date) && !empty($request->end_date)){
                $endDate = Carbon::createFromFormat('d-m-Y', $request->end_date)->startOfDay()->format('Y-m-d');
                $allJobs->whereDate('job_start_date', '<=', $endDate);
            }

            if(isset($request->job_status) && !empty($request->job_status)){
                $allJobs->where('status', $request->job_status);
            }

            if ($roleName == 'Employer') {
                $allJobs =$allJobs->where('user_id', $user_id)->get()->toArray();

            } else {
                $allJobs =$allJobs->get()->toArray();

            }



        return $allJobs;

    }

    public function store(Request $request)
    {


        $from_date = date('Y-m-d',strtotime($request->from_date));
        $to_date = date('Y-m-d',strtotime($request->to_date));

        $job_status = $request->job_status;
        $job_id = $request->job_id;

        $user_id = auth()->user()->id;
        $roleName =  getUserRole($user_id);

        if(!empty($job_id)){
            $job_title =EmployerJob::where('id',$job_id)->value('job_title');
        }else{
            $job_title = "All";
        }

        $query = DB::table('employer_jobs as e')
            ->select('u.id', 'u.first_name', 'e.job_title', 'e.status', 'e.job_start_date', 'e.location',
                DB::raw('COUNT(DISTINCT(a.id)) as applied_jobs_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN c.status = "Shortlist" AND (c.field_status = "Selected" OR c.field_status = "Skip") THEN c.id END) as shortlist_selected_count'),
                DB::raw('COUNT(DISTINCT CASE WHEN c.status = "Final Selection" AND c.field_status = "Selected" THEN c.id END) as final_selection_selected_count')

            )
            ->join('users as u', 'u.id', '=', 'e.user_id')
            ->leftJoin('applied_jobs as a', 'a.job_creator_id', '=', 'e.user_id')
            ->leftJoin('candidate_job_status_comments as c', 'c.job_creator_id', '=', 'a.job_creator_id')

            ->whereBetween('e.job_start_date', [$from_date, $to_date]);



        if($request->has('employer_id') && (empty($request->employer_id)) && (($roleName == 'Super Admin') || ($roleName == 'Admin'))){
            $emplaoyer_id = "";

        }else if($request->has('employer_id') && (!empty($request->employer_id)) && (($roleName == 'Super Admin') || ($roleName == 'Admin'))){
            $emplaoyer_id =$request->employer_id;
            // $query->where('u.id', $emplaoyer_id);

        }
        else{

            $emplaoyer_id = auth()->user()->id;
             $query->where('u.id', $emplaoyer_id);

        }

        // Check if $job_type is not empty and add it to the query
        if (!empty($job_status)) {

            $query->where('e.status', $job_status);
        }

        // Check if $job_title is not empty and add it to the query
        if (!empty($job_id)) {

            $query->where('e.id', $job_id);
        }

        $query->groupBy('u.id','a.job_id','e.job_title', 'e.status', 'e.job_start_date', 'e.location', 'u.first_name');
        $joinResults = $query->orderBy('e.id', 'desc')->get();


        if(count($joinResults)== 0){
            return response()->json(['status' => false, 'message' => 'No data found']);
        }

        $data = [];

        if(in_array($roleName, ['Super Admin', 'Admin'])){
            $data[] = ['S.No', 'Employer Name','Job title', 'Status','Job address' ,'Job start date', 'Total applied candidate', 'Total shortlist candidate','Total final selection candidate' ];
        }else{
            $data[] = ['S.No', 'Job title', 'Status','Job address' ,'Job start date', 'Total applied candidate', 'Total shortlist candidate', 'Total final selection candidate'];
        }

        if (!empty($joinResults)) {
            foreach ($joinResults as $key => $joinResult) {
                $row =[];
                $no = $key + 1;
                $row[] = $no;
                if(in_array($roleName, ['Super Admin', 'Admin'])){
                    $row[] = $joinResult->first_name;
                }

                $row[] = $joinResult->job_title;
                $row[] = $joinResult->status;
                $row[] = $joinResult->location;
                $row[] = $joinResult->job_start_date;
                $row[] = (string)$joinResult->applied_jobs_count;
                $row[] = (string)$joinResult->shortlist_selected_count;
                $row[] = (string)$joinResult->final_selection_selected_count;

                $data[] = $row;
            }
        }


        $excel_path =  $this->employerGenerateReportXlsx($data);

        $inserdata = [

            "from_date" => $from_date,
            "to_date" => $to_date,
            "job_status" => !empty($job_status) ? $joinResult->status:'All',
            "employer_name" =>  !empty($emplaoyer_id) ? $joinResult->first_name:'All',
            "job_title" => ($job_title =="All") ? $job_title:$joinResult->job_title,
            "user_id" => $user_id,
            "link"=>$excel_path

        ];

        $inserReport = EmployerReportLog::create($inserdata);

        // $getAllData = EmployerReportLog::OrderBy('id','desc')->get();

        $query1 = EmployerReportLog::orderBy('id', 'desc');
        if(in_array($roleName, ['Super Admin', 'Admin'])){
            $query1->select(
                'id','link',
                DB::raw('DATE_FORMAT(from_date, "%d-%m-%Y") as from_date'),
                DB::raw('DATE_FORMAT(to_date, "%d-%m-%Y") as to_date'),
                DB::raw('IFNULL(employer_name, "All") as employer_name'),
                DB::raw('IFNULL(job_status, "All") as job_status'),
                DB::raw('IFNULL(job_title, "All") as job_title'),
                DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as created_at_formatted')
            );
        }else{
            $query1->select(
                'id','link',
                DB::raw('DATE_FORMAT(from_date, "%d-%m-%Y") as from_date'),
                DB::raw('DATE_FORMAT(to_date, "%d-%m-%Y") as to_date'),
                DB::raw('IFNULL(job_status, "All") as job_status'),
                DB::raw('IFNULL(job_title, "All") as job_title'),
                DB::raw('DATE_FORMAT(created_at, "%d-%m-%Y") as created_at_formatted')
            );
        }
        $getAllData = $query1->where('user_id',auth()->user()->id)
            ->get();


        if ($inserReport) {

            return response()->json(['status' => true, 'data' => $getAllData, 'message' => 'Report generated successfully.']);
        } else {
            return response()->json(['status' => false, 'message' => 'somthing went wrong.']);
        }
    }

    public function employerGenerateReportXlsx($data)
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
        $sheet->getStyle('A2:'.$highestColumn.$highestRow)->getProtection()->setLocked(\PhpOffice\PhpSpreadsheet\Style\Protection::PROTECTION_UNPROTECTED);

        $writer = new Xlsx($spreadsheet);

        $currentTimestamp = now()->timestamp;
        $filename = 'genrate-report-'.$currentTimestamp.'.xlsx';

        if (!file_exists(public_path('/employer_report_generate/'))) {
            mkdir(public_path('/employer_report_generate/'));
        }
        $writer->save(public_path('/employer_report_generate/'.$filename));
        return '/employer_report_generate/'.$filename;
    }


}
