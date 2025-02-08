<?php
namespace App\Jobs;

use App\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\User;
use Mail;

class SendQueueNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $details;
    public $timeout = 7200; // 2 hours

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $data = User::all();
        // $input['subject'] = $this->details['subject'];

        $sender_id =$this->details['sender_id'];
        $recever_ids = $this->details['recever_ids'];
        $title = $this->details['title'];
        $type = $this->details['title'];
        $message = $this->details['message'];

        if(!empty($recever_ids)){
            foreach ($recever_ids as $key => $value) {

                $notification = new Notification();
                $notification->sender_id = $sender_id;
                $notification->receiver_id = $value;
                $notification->title = $title;
                $notification->message = $message;
                $notification->type = $type;
                $notification->save();
            }
        }

    }
}
