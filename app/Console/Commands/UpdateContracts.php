<?php

namespace App\Console\Commands;

use App\AssignedContract;
use App\User;
use Illuminate\Console\Command;
use Carbon\Carbon;
use DateTime;

class UpdateContracts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-contracts';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today_date = Carbon::now();
        $today_date = $today_date->format('Y-m-d');
        $today_date = new DateTime($today_date);

        $getContracts = AssignedContract::where('status', 'Active')->get();

        foreach ($getContracts as $getAssignedContracts) {

            if($getAssignedContracts->recurring_contracts == '1'){

                $start_date = new DateTime($getAssignedContracts->start_date);

                if ($getAssignedContracts->end_date == null) {

                    $start_date = new DateTime($getAssignedContracts->start_date);
                    $start_date->modify('+1 year');
                    $end_date = $start_date->format('Y-m-d');
                    
                    if($end_date =  $today_date){

                        $start_date = $end_date;
                    }


                    AssignedContract::where('id', $getAssignedContracts->id)
                    ->update(['start_date' => $start_date]);


                }else{
                    $end_date = new DateTime($getAssignedContracts->end_date);

                    if ($end_date == $today_date) {

                        $interval = $start_date->diff($end_date);

                        $days = $interval->days;

                        $end_date->modify("+$days days");

                        $end_date = $end_date->format('Y-m-d');
                        $start_date = $today_date->format('Y-m-d');


                        AssignedContract::where('id', $getAssignedContracts->id)
                            ->update(['start_date' => $start_date, 'end_date' => $end_date]);
                    }

                }
            }else{

                $start_date = new DateTime($getAssignedContracts->start_date);

                if ($getAssignedContracts->end_date != null) {

                    $end_date = new DateTime($getAssignedContracts->end_date);

                    if ($end_date == $today_date) {

                        User::where('id', $getAssignedContracts->user_id)
                            ->update(['status' => 'DeActive']);
                    }

                }
            }
        }
    }
}
