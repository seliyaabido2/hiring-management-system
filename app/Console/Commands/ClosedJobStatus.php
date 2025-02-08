<?php

namespace App\Console\Commands;

use App\EmployerJob;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;

class ClosedJobStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'closed-job-status';

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
        // Retrieve the current date

        $currentDate = Carbon::now();
        $currentDate = $currentDate->format('Y-m-d');
        $currentDate = new DateTime($currentDate);


        // Retrieve the active jobs
        $activeJobs = EmployerJob::whereIn('status', ['active','Deactive'])->get();

        foreach ($activeJobs as $job) {
           $exipreJobDate = GetJobExpiryDate($job->job_start_date,$job->job_recruiment_duration);

           $exipreJobDate = new DateTime($exipreJobDate);

           // Check if the job's scheduled date has passed
            if ($currentDate >= $exipreJobDate) {

                // Update the job's status
                $job->status = 'Closed';
                $job->save();

                // Perform any additional actions you require for the completed job
                // ...
            }
        }
    }

}
