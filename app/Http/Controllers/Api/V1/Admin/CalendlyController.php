<?php

namespace App\Http\Controllers\Api\V1\Admin;


use App\Http\Controllers\Controller;
use App\AppliedJob;
use Illuminate\Http\Request;
use App\Candidate;
use App\City;
use App\Country;
use App\State;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;
use Gate;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\CandidateJobStatusComment;
class CalendlyController extends Controller
{
    public function calendlySchedulePayload(Request $request){
        // $data =CandidateJobStatusComment::where('id',10)->first();
        // $item =json_decode($data->Invitee_created_payload,true);
        // dd($item);
        // Log::debug('Calendly Webhook Request:', $request->all());

        // Get the JSON data from the request body
        $payload = $request->json()->all();



          if(!isset($payload) || empty($payload) ){
            return false;
          }
          $event = $payload['event'];

          // Log the extracted event for further debugging


        // $parsedData = json_decode($payload, true);
        // Log::debug('Calendly Webhook Request:', $payload);
        $info_data = CandidateJobStatusComment::where(['job_id'=>$payload['payload']['tracking']['utm_campaign'],'candidate_id'=> $payload['payload']['tracking']['utm_source']])->first();

        if($payload['event'] =='invitee.created' && !empty($payload['payload']['tracking']['utm_campaign']) &&  !empty($payload['payload']['tracking']['utm_source']) &&  !empty($payload['payload']['tracking']['utm_medium'])){

            $candidateDataArr = array(
                'job_id' => $payload['payload']['tracking']['utm_campaign'],
                'candidate_id' => $payload['payload']['tracking']['utm_source'],
                'status' => $payload['payload']['tracking']['utm_medium'],
                'field_status' => 'None',
                'Invitee_created_payload'=>json_encode($payload),
                'applied_job_id'=>$info_data->applied_job_id,
                'user_id'=>$info_data->user_id,
                'job_creator_id'=>$info_data->job_creator_id,

            );

            if($info_data->is_bod_candidate_id == '1'){
                $candidateDataArr['is_bod_candidate_id'] = $info_data->is_bod_candidate_id;
            }


            $updatetdata = CandidateJobStatusComment::updateOrCreate(
                [
                    'job_id' => $payload['payload']['tracking']['utm_campaign'],
                    'candidate_id' => $payload['payload']['tracking']['utm_source'],
                    'status' => $payload['payload']['tracking']['utm_medium'],

                ],
                $candidateDataArr
            );


          }

          if($payload['event'] =='invitee.canceled'){


            $candidateDataArr = array(
                'job_id' => $payload['payload']['tracking']['utm_campaign'],
                'candidate_id' => $payload['payload']['tracking']['utm_source'],
                'status' => $payload['payload']['tracking']['utm_medium'],
                'field_status' => 'None',
                'Invitee_canceled_payload'=>json_encode($payload),
                'applied_job_id'=>$info_data->applied_job_id,
                'user_id'=>$info_data->user_id,
                'job_creator_id'=>$info_data->job_creator_id,

            );

            if($info_data->is_bod_candidate_id == '1'){
                $candidateDataArr['is_bod_candidate_id'] = $info_data->is_bod_candidate_id;
            }

            $updatetdata = CandidateJobStatusComment::updateOrCreate(
                [
                    'job_id' => $payload['payload']['tracking']['utm_campaign'],
                    'candidate_id' => $payload['payload']['tracking']['utm_source'],
                    'status' => $payload['payload']['tracking']['utm_medium'],

                ],
                $candidateDataArr
            );



          }


        // Log::info('This is an info message.',$request->all());
    }
    public function createEvent(Request $request)
    {
        $token = $request->input('token');
        $body = [
            "name" => "My First Event",
            "start_time" => "2023-07-03T10:00:00Z",
            "end_time" => "2023-07-03T11:00:00Z",
            "location" => [
              "type" => "physical",
              "location" => "My Office"
            ],
            'description' => 'description',
        ];

        $response = Http::post('https://api.calendly.com/events', [
            'headers' => ['Authorization' => 'Bearer ' . $token],
            'json' => $body,
        ]);
        // dd($response);


        if ($response->status() == 201) {
            return response()->json([
                'success' => true,
                'data' => $response->json(),
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => $response,
            ]);
        }
    }
}
