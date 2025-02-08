<?php

namespace App\Http\Controllers\Admin;

use App\AppliedJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Candidate;
use App\Country;
use App\EmployerDetail;
use App\User;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
class CalendlyController extends Controller
{




    public function addCalendlyUser(Request $request){


            $client = new Client();

            try {


                $calendlydata = calendlyOwnerDetails();

                Log::info(print_r($calendlydata, true));

                $parsedData = json_decode($calendlydata, true);
                // dd($parsedData);
                $calendly_url = config('app.calendly_url');
                $calendly_access_token = config('app.calendly_access_token');
                $userdata =User::where('id',$request->input('id'))->first();


                    $current_organization_id = str_replace($calendly_url . "/organizations/", "", $parsedData['resource']['current_organization']);
                    $current_organization = $parsedData['resource']['current_organization'];

                    Log::info(print_r($calendly_access_token, true));
                            $response = $client->get($calendly_url .'/organizations/'.$current_organization_id.'/invitations?email='.$userdata->email.'&count=1&sort=created_at:desc', [
                                'headers' => [
                                    'Authorization' => 'Bearer ' . $calendly_access_token,
                                    'Accept' => 'application/json',
                                ],
                                // You can also pass any query parameters if required
                                'json' => [
                                    // 'email' => $current_organization,

                                ],
                            ]);


                            $statusCode = $response->getStatusCode();
                            $data = $response->getBody()->getContents();
                            $parsedData = json_decode($data, true);
                            Log::info(print_r($data, true));


                            if( isset($parsedData['collection']) && !empty($parsedData['collection']) ) {
                                if((!empty($parsedData['collection'] && $parsedData['collection'][0]['status']  == 'accepted'))){

                                    $EmployerUpdate = EmployerDetail::where('user_id', $userdata->id)->first();
                                   $calendlyuserdata = getCalendlyScheduleUrl($parsedData['collection'][0]['user'], $userdata->id);

                                    $EmployerUpdate->calendly_invitation = json_encode($parsedData['collection'][0]);

                                    $EmployerUpdate->save();

                                    return back()->with('success', 'user added in Calendly');
                                }else{
                                    $errorMessage = "calendly invitation pending";
                                    return back()->with('error',$errorMessage);
                                }


                            }else{

                                Log::info(print_r($current_organization, true));
                                try {
                                    $response = $client->post($current_organization.'/invitations', [
                                        'headers' => [
                                            'Authorization' => 'Bearer '.$calendly_access_token,
                                            'Accept' => 'application/json',
                                        ],
                                        // You can also pass any query parameters if required
                                        'json' => [
                                            'email' => $userdata->email,

                                        ],
                                    ]);

                                    $statusCode = $response->getStatusCode();
                                    $data = $response->getBody()->getContents();
                                    $parsedData = json_decode($data, true);

                                    Log::info(print_r($parsedData, true));
                                    $EmployerUpdate = EmployerDetail::where('user_id',$userdata->id)->first();
                                   if(isset($parsedData['collection']) && !empty($parsedData['collection'])){
                                     $EmployerUpdate->calendly_invitation = json_encode($parsedData['collection'][0]);
                                   }else{
                                    $EmployerUpdate->calendly_invitation = json_encode($parsedData['resource']);
                                   }


                                    $EmployerUpdate->save();

                                    return back()->with('success', 'The invitation has been successfully sent to your email. Please check your inbox.');

                                    } catch (\Exception $e) {
                                        $message =$e->getMessage();

                                        Log::debug("jjjj".print_r($message, true));
                                        preg_match('/"message":"([^"]+)"/', $message, $matches);

                                        if (isset($matches[1])) {
                                            $errorMessage = $matches[1];

                                        }else{
                                            $errorMessage="Something went to wrong";
                                        }

                                        return back()->with('error',$errorMessage);
                                    }




                            }




                    } catch (\Exception $e) {
                        $message =$e->getMessage();
                        Log::debug("jkkk".print_r($message, true));
                        preg_match('/"message":"([^"]+)"/', $message, $matches);

                        if (isset($matches[1])) {
                            $errorMessage = $matches[1];

                        }else{
                            $errorMessage="Something went to wrong";
                        }

                        return back()->with('error',$errorMessage);
                    }



    }

    public function getCalendlyUrl($appliedJobId,$status){


        $client = new Client();

        // try {
            $appliedJobId =decrypt_data($appliedJobId);
            $appliedjob =AppliedJob::where('id',$appliedJobId)->first();

            // $userdata =User::where('id',$appliedjob->job_creator_id)->with('EmployerDetail')->first();
            $data =calendlyInivitationStatus($appliedjob->job_creator_id);

            if(!empty($data) && $data['status'] =='accepted'){

             $calendly_user_data =  getCalendlyScheduleUrl($data['user'],
             $appliedjob->job_creator_id);

             $candidateData =Candidate::where('candidate_id',$appliedjob->candidate_id)->first();
                // if(!empty($calendly_user_data)){
                //     return view('admin.calendly.schedule', compact('calendly_user_data','candidateData','status'));
                // }else{
                //     return back()->with('error', 'somthing went wrong');
                // }
             }else{

                return back()->with('error', 'Please contact the administrator to add Calendly.');
             }


    //        }

    //         $calendly_url = config('app.calendly_url');
    //         $calendly_access_token = config('app.calendly_access_token');

    //     //   /  dd($calendly_access_token);

    //     $response = $client->get($calendly_url.'/users/me', [
    //         'headers' => [
    //             'Authorization' => 'Bearer '.$calendly_access_token,
    //             'Accept' => 'application/json',
    //         ]
    //         // You can also pass any query parameters if required
    //         // 'query' => [
    //         //     'param1' => 'value1',
    //         //     'param2' => 'value2',
    //         // ],
    //     ]);

    //     $statusCode = $response->getStatusCode();
    //     $data = $response->getBody()->getContents();
    //     $parsedData = json_decode($data, true);

    //     if ($statusCode == 200) {
    //         $current_organization = str_replace($calendly_url."/organizations/","",$parsedData['resource']['current_organization']);
    //         $current_organization = $parsedData['resource']['current_organization'];
    //         // dd($current_organization);
    //         $response = $client->get($calendly_url.'/organization_memberships?organization='.$current_organization, [
    //             'headers' => [
    //                 'Authorization' => 'Bearer '.$calendly_access_token,
    //                 'Accept' => 'application/json',
    //             ],
    //             // You can also pass any query parameters if required
    //             'json' => [
    //                 // 'organization' => $current_organization,

    //             ],
    //         ]);


    //         $statusCode = $response->getStatusCode();
    //         $data = $response->getBody()->getContents();
    //         $parsedData = json_decode($data, true);
    //         dd($parsedData);

    //         // return back()->with('success', 'The invitation has been successfully sent to your email. Please check your inbox.');


    //         // Success
    //         // Do something with $parsedData
    //     } else {

    //         return back()->with('error', 'something went to wrong');


    //         // Error
    //         // Handle the error case
    //     }

    // } catch (\Exception $e) {


    //     $message =$e->getMessage();
    //     dd($message);

    //     preg_match('/"message":"([^"]+)"/', $message, $matches);

    //     if (isset($matches[1])) {
    //         $errorMessage = $matches[1];

    //     }else{
    //         $errorMessage="Something went to wrong";
    //     }

    //     return back()->with('error',$errorMessage);



    // }
}

}
