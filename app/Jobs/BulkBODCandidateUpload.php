<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

use App\BodCandidate;
use App\Candidate;
use App\SheetStatus;

class BulkBODCandidateUpload implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    // protected $highestRow;
    // protected $highestColumn;
    // protected $worksheet;

    protected $data;
    // public $timeout = 3; // 2 hours


    /**
     * Create a new job instance.
     */
    // public function __construct($highestRow,$highestColumn,$worksheet)
    public function __construct($data)
    {
        //
        // $this->highestRow = $highestRow;
        // $this->highestColumn = $highestColumn;
        // $this->worksheet = $worksheet;

        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        // Log::info("outside try checking");

        try {


            // Log::info("checksheetsStatus::---".$checksheetsStatus); die;

            // $highestRow = $this->highestRow;
            // $highestColumn = $this->highestColumn;
            // $worksheet = $this->worksheet;

            // Log::info("bulkBODcandidate_jobcheck");

            // Log::info("auth_login:---".print_r(Auth::user()));
            $data = $this->data;
            Log::info("allData:---".print_r($data['sheetName']));
            $highestRow = $data['highestRow'];
            $highestColumn = $data['highestColumn'];
            $worksheet = $data['cellValues'];
            $UserId = $data['auth_login'];
            Log::info("auth_login1221:---".$UserId);

            foreach ($worksheet as $key => $subArray) {
                if (empty(array_filter($subArray))) {
                    unset($worksheet[$key]);
                }
            }

            $worksheet = array_values($worksheet);

            $rowDataone = [];

            for ($row = 1; $row < count($worksheet); $row++)
            {
                if (isset($worksheet[$row])) {
                    $cell = $worksheet[$row];
                    $rowDataone[] = $cell;
                }
            }
            // echo "<pre>"; print_r(count($rowDataone)); die;

            $roleName = getUserRole($UserId);
            Log::info("getUserRole::---".$roleName);

            if ($roleName == 'Super Admin' || $roleName == 'Admin') {
                $user_role = $roleName;
            } else {
                $user_role = $roleName;
            }

            $checksheetsStatus = new SheetStatus;
            $checksheetsStatus->sheet_name = $data['sheetName'];
            $checksheetsStatus->status = "Processing";
            $checksheetsStatus->user_role = $user_role;
            $checksheetsStatus->save();

            // Log::info("checksheetsStatus::---".$checksheetsStatus);

            $rowcount = count($rowDataone);
            $i = 1;
            foreach($rowDataone as $key=>$rowData)
            {
                // echo "<pre>"; print_r($i);


                // echo "rowcount: ".$rowcount;
                // echo "index: ".$i;

                $candidate_id = generateUniqueCandidateId();
                Log::info("auth_login1221:---".$UserId);

                $storeresume = downloadAndStorePDF($rowData[4],$candidate_id);

                if ($roleName == 'Super Admin' || $roleName == 'Admin') {
                    // for super admin and admin BOD candidate
                    $checkbulkbod = BodCandidate::where('email',$rowData[1])->where('contact_no',$rowData[2])->first();
                    if(!empty($checkbulkbod))
                    {
                        $bodBulk = $checkbulkbod;
                    } else {
                        $bodBulk = new BodCandidate();
                    }

                } else {
                    // for recuirter candidate
                    $checkbulkbod = Candidate::where('email',$rowData[1])->where('contact_no',$rowData[2])->first();
                    if(!empty($checkbulkbod))
                    {
                        $bodBulk = $checkbulkbod;
                    } else {
                        $bodBulk = new Candidate();
                    }
                }


                $bodBulk->name = $rowData[0];
                $bodBulk->email = $rowData[1];
                $bodBulk->contact_no = $rowData[2];
                $bodBulk->location = $rowData[3];
                if(in_array($rowData[4],['spanish','korean','chinese'])){
                    $bodBulk->any_other_langauge = $rowData[4];
                }else{
                    $bodBulk->any_other_langauge ='Other';
                    $bodBulk->other_any_other_langauge = $rowData[4];

                }

                $bodBulk->license_requirement = $rowData[5];
                $bodBulk->experience_sf = $rowData[6];
                if($rowData[6] == 'Yes'){
                    $bodBulk->how_many_experience = $rowData[7];
                    $bodBulk->presently_working_in_sf = $rowData[8];
                    if($rowData[8] =='No'){
                        $bodBulk->last_month_year_in_sf = $rowData[9];
                    }
                }
                $bodBulk->candidate_id = $candidate_id;
                $bodBulk->user_id = $UserId;
                $bodBulk->resume = $storeresume;
                $bodBulk->save();


                if($rowcount == $i){
                    echo "asasasas: ";
                    $updateheetsStatus = SheetStatus::where('id',$checksheetsStatus->id)->first();
                    $updateheetsStatus->sheet_name = $data['sheetName'];
                    $updateheetsStatus->status = "Completed";
                    $checksheetsStatus->user_role = $user_role;
                    $updateheetsStatus->save();

                }

                $i++;

            }
            // die;

        } catch (\Exception $e) {

            $updateheetsStatus = SheetStatus::where('id',$checksheetsStatus->id)->first();
            $updateheetsStatus->sheet_name = $data['sheetName'];
            $updateheetsStatus->status = "Faild";
            $checksheetsStatus->user_role = $user_role;
            $updateheetsStatus->save();

            Log::info("error_bulkBODcandidate_jobcheck");
            Log::error('Error in bulk job: ' . $e->getMessage());
        }

        //// end peterson code

    }

}
