<?php

use App\AppliedJob;
use App\AssessmentLink;
use App\BodCandidate;
use App\City;
use App\Country;
use App\State;
use App\User;
use App\Candidate;
use App\EmployerDetail;
use App\Notification;
use App\CandidateJobStatusComment;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


use function Laravel\Prompts\select;

function createSlug($string)
{

    $slug = Str::slug($string);
    return $slug;
}

function checkCandidateSlugIsExist($slug)
{

    $count = AppliedJob::where('candidate_job_slug', $slug)->count();

    if ($count > 0) {
        return false;
    }
    return true;
}

// function checkCandidateArrSlugIsExist($candidateId,$jobId){

//     $candidate_job_slug = Candidate::whereIn('candidate_id', $candidateId)->get()->pluck('candidate_job_slug')->toArray();

//     $count = AppliedJob::whereIn('candidate_job_slug', $candidate_job_slug)->where('job_id',$jobId)->count();
//     if($count > 0){


//         $data = AppliedJob::whereIn('candidate_job_slug', $candidate_job_slug)
//         ->where('job_id', $jobId)
//         ->distinct()
//         ->get()->pluck('candidate_id')->toArray();

//         dd($data);

//         $result = Candidate::whereNotIn('candidate_id', $data)->get()->pluck('candidate_id')->toArray();

//         dd($result);

//         $candidatesStr = implode(',',$data);

//         return $candidatesStr;
//     }

//     return true;

// }

function checkCandidateArrSlugIsExist($candidateId, $jobId)
{

    $userId = auth()->user()->id;
    $RoleName = getUserRole($userId);

    if ($RoleName == 'Admin' || $RoleName == 'Super Admin') {

        // $candidate_job_slug = BodCandidate::whereIn('candidate_id', $candidateId)->get()->pluck('candidate_job_slug')->toArray();
        $candidate_email_Arr = BodCandidate::whereIn('candidate_id', $candidateId)
            ->get()
            ->pluck(['email'])
            ->toArray();

        $candidate_contact_Arr = BodCandidate::whereIn('candidate_id', $candidateId)
            ->get()
            ->pluck(['contact_no'])
            ->toArray();
    } else {
        // $candidate_job_slug = Candidate::whereIn('candidate_id', $candidateId)->get()->pluck('candidate_job_slug')->toArray();
        $candidate_email_Arr = Candidate::whereIn('candidate_id', $candidateId)
            ->get()
            ->pluck(['email'])
            ->toArray();

        $candidate_contact_Arr = Candidate::whereIn('candidate_id', $candidateId)
            ->get()
            ->pluck(['contact_no'])
            ->toArray();
    }



    $appliedJobCandidates = AppliedJob::where('job_id', $jobId)
        ->get()
        ->map(function ($appliedJob) {
            if ($appliedJob->is_bod_candidate_id == 1) {
                $appliedJob->setRelation('candidate', $appliedJob->bod_candidate);
            } else {
                $appliedJob->setRelation('candidate', $appliedJob->candidate);
            }
            return $appliedJob;
        });




    $emailsInAppliedJobs = $appliedJobCandidates->pluck('candidate.email')->merge($appliedJobCandidates->pluck('bodcandidate.email'));

    $contactsInAppliedJobs = $appliedJobCandidates->pluck('candidate.contact_no')->merge($appliedJobCandidates->pluck('bodcandidate.contact_no'));


    // Checking if any of the emails exist in the $emails array
    $foundEmails = $emailsInAppliedJobs->intersect($candidate_email_Arr);
    $foundContacts = $contactsInAppliedJobs->intersect($candidate_contact_Arr);
    $foundDataArr = [];

    // Check if any emails were found
    if ($foundEmails->isNotEmpty()) {
        // Emails found in the $emails array
        foreach ($foundEmails as $email) {
            $foundDataArr['email'][] = $email;
        }
    }

    if ($foundContacts->isNotEmpty()) {
        // Emails found in the $emails array
        foreach ($foundContacts as $contact) {
            $foundDataArr['contact'][] = $contact;
        }
    }


    $dublicateArr = [];

    if (count($foundDataArr) > 0) {

        $foundDataArr['email'] = $foundDataArr['email'] ?? [];
        $foundDataArr['contact'] = $foundDataArr['contact'] ?? [];

        if ($RoleName == 'Admin' || $RoleName == 'Super Admin') {


            $dublicateArr = BodCandidate::whereIn('email', $foundDataArr['email'])
                ->OrwhereIn('contact_no', $foundDataArr['contact'])
                ->get()
                ->pluck(['name'])
                ->toArray();
        } else {

            $dublicateArr = Candidate::whereIn('email', $foundDataArr['email'])
                ->OrwhereIn('contact_no', $foundDataArr['contact'])
                ->get()
                ->pluck(['name'])
                ->toArray();
        }
    }

    if (empty($dublicateArr)) {

        $responce = [];
        $responce['status'] = true;
    } else {
        $uniqueArray = array_unique($dublicateArr);
        $msg = implode(',', $uniqueArray);
        $responce = [];
        $responce['status'] = false;
        $responce['msg'] = $msg;
    }

    return $responce;
}


function getCandidateByCandidateId($candidateId)
{

    $candidate = Candidate::where('candidate_id', $candidateId)->first();
    if (empty($candidate)) {

        $candidate = BodCandidate::where('candidate_id', $candidateId)->first();
    }

    return $candidate;
}

function getUserRole($userId)
{
    $user = User::find($userId);

    if ($user) {
        $role = $user->roles[0]->title;

        if ($role) {
            return $role;
        }
    }

    return null; // If the user or role doesn't exist
}

function getUserName($userId)
{
    $user = User::where('id', $userId)->first();

    if ($user) {

        return $user;
    }

    return null; // If the user or role doesn't exist
}

function getUserDetail($userId)
{
    $getUserRole = getUserRole($userId);
    $user = User::where('id', $userId);

    if ($getUserRole == 'Admin') {
        $user = $user->with('AdminDetail');
    } elseif ($getUserRole == 'Employer') {
        $user = $user->with('EmployerDetail');
    } elseif ($getUserRole == 'Recruiter') {
        $user = $user->with('RecruiterDetail');
    }

    $userData = $user->get();


    if ($userData) {

        return $userData;
    }

    return null; // If the user or role doesn't exist
}

function getCountryById($id)
{
    $country = Country::find($id);

    if ($country) {

        $country = $country->name;

        if ($country) {
            return $country;
        }
    }

    return null; // If the user or role doesn't exist
}

function getStateById($id)
{
    $state = State::find($id);

    if ($state) {
        $state = $state->name;

        if ($state) {
            return $state;
        }
    }

    return null; // If the user or role doesn't exist
}

function getCityById($id)
{
    $city = City::find($id);

    if ($city) {
        $city = $city->name;

        if ($city) {
            return $city;
        }
    }

    return null; // If the user or role doesn't exist
}

function encrypt_data($id)
{
    $id = Crypt::encrypt($id);

    if ($id) {
        return $id;
    }

    return null; // If the user or role doesn't exist
}

function decrypt_data($id)
{
    $id = Crypt::decrypt($id);

    if ($id) {
        return $id;
    }

    return null; // If the user or role doesn't exist
}

function generateUniqueCandidateId()
{
    $id = uniqid(); // Get a unique identifier


    // Remove any characters that are not digits
    $id = preg_replace("/[^0-9]/", "", $id);

    // Generate a random number to fill up remaining digits
    $remainingDigits = 13 - strlen($id);
    $randomDigits = "";
    for ($i = 0; $i < $remainingDigits; $i++) {
        $randomDigits .= mt_rand(0, 9);
    }

    $id .= $randomDigits;

    return $id;
}

function checkCandidateIdIsExist($candidateId)
{

    $UserId = auth()->user()->id;
    $roleName = getUserRole($UserId);

    $itemcount = count($candidateId);
    if (in_array($roleName, array('Super Admin', 'Admin'))) {

        $count = BodCandidate::whereIn('candidate_id', $candidateId)->count();
    } else {
        $count = Candidate::whereIn('candidate_id', $candidateId)->count();
    }

    if ($count == $itemcount) {

        return true;
    } else {

        return false;
    }
}

function getConvertedDate($date)
{

    date($date);
    $old_date = date('l, F d y h:i:s');
    $old_date_timestamp = strtotime($date);
    $new_date = date('F jS, Y', $old_date_timestamp);

    return $new_date;
}

function getConvertedDateTime($date)
{

    // Parse the date using Carbon
    $carbonDate = Carbon::parse($date);

    // Format the date as per your desired format: Y-m-d h:i:A
    $formattedDate = $carbonDate->format('Y-m-d h:i:A');


    return $formattedDate;
}

function calculateEndDate($startDate, $duration)
{

    $startTimestamp = strtotime($startDate);
    $endTimestamp = strtotime("+$duration days", $startTimestamp);
    $endDate = date('d-m-Y', $endTimestamp);

    return $endDate;
}

function GetJobExpiryDate($startDate, $duration)
{

    $endDate = date('Y-m-d', strtotime($startDate . ' + ' . ($duration - 1) . ' days'));

    return $endDate;
}

function getAssessmentLink($candidateId, $status)
{

    $link = AssessmentLink::where(['candidate_id' => $candidateId, 'status' => $status])->first();

    if (empty($link)) {
        return $link = "";
    }
    return $link->link;
}

function getCandidateComments($candidateJobStatusComment, $desiredStatus)
{

    $additional_note = array_column(
        array_filter($candidateJobStatusComment, function ($subArray) use ($desiredStatus) {
            return $subArray['status'] === $desiredStatus;
        }),
        'additional_note',
    );
    return $additional_note;
}

function getCandidateLinks($candidateJobStatusComment, $desiredStatus)
{

    $links = array_column(
        array_filter($candidateJobStatusComment, function ($subArray) use ($desiredStatus) {
            return $subArray['status'] === $desiredStatus;
        }),
        'links',
    );
    return $links;
}

function getCandidateStatusId($candidateJobStatusComment, $desiredStatus)
{

    $id = array_column(
        array_filter($candidateJobStatusComment, function ($subArray) use ($desiredStatus) {
            return $subArray['status'] === $desiredStatus;
        }),
        'id',
    );
    return $id;
}

function getUpdatedDateForComments($candidateJobStatusComment, $desiredStatus)
{

    $updated_date = array_column(
        array_filter($candidateJobStatusComment, function ($subArray) use ($desiredStatus) {
            return $subArray['status'] === $desiredStatus;
        }),
        'updated_at',
    );

    $date =  date('Y-F-d h:i A', strtotime($updated_date[0]));

    return $date;
}

function calendlyOwnerDetails()
{

    try {
        $calendly_url = config('app.calendly_url');

        $calendly_access_token = config('app.calendly_access_token');

        //   /  dd($calendly_access_token);
        $client = new Client();
        $response = $client->get($calendly_url . '/users/me', [
            'headers' => [
                'Authorization' => 'Bearer ' . $calendly_access_token,
                'Accept' => 'application/json',
            ]
            // You can also pass any query parameters if required
            // 'query' => [
            //     'param1' => 'value1',
            //     'param2' => 'value2',
            // ],
        ]);
        // dd($response);
        $statusCode = $response->getStatusCode();
        $data = $response->getBody()->getContents();
        $parsedData = json_decode($data, true);
        // dd($parsedData);
        return $data;
    } catch (\Exception $e) {


        $message = $e->getMessage();


        preg_match('/"message":"([^"]+)"/', $message, $matches);

        if (isset($matches[1])) {
            $errorMessage = $matches[1];
        } else {
            $errorMessage = "Something went to wrong";
        }

        return back()->with('error', $errorMessage);
    }
}

function calendlyInivitationStatus($employerId)
{

    $userdata = User::where('id', $employerId)->with('EmployerDetail')->first();


    $client = new Client();


    $calendlydata = calendlyOwnerDetails();

    $parsedData = json_decode($calendlydata, true);
    $calendly_url = config('app.calendly_url');
    $calendly_access_token = config('app.calendly_access_token');


    $current_organization_id = str_replace($calendly_url . "/organizations/", "", $parsedData['resource']['current_organization']);
    $current_organization = $parsedData['resource']['current_organization'];

    if (!isset($userdata->EmployerDetail->calendly_invitation)) {

        return null;
    }

    $calendly_invitation = json_decode($userdata->EmployerDetail->calendly_invitation);

    try {
        // dd($current_organization);
        $response = $client->get($calendly_invitation->uri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $calendly_access_token,
                'Accept' => 'application/json',
            ],
            // You can also pass any query parameters if required
            'json' => [
                // 'organization' => $current_organization,

            ],
        ]);
    } catch (\Exception $e) {
    }


    $statusCode = $response->getStatusCode();
    $data = $response->getBody()->getContents();
    $parsedData = json_decode($data, true);



    $EmployerUpdate = EmployerDetail::where('user_id', $userdata->id)->first();

    $EmployerUpdate->calendly_invitation = json_encode($parsedData['resource']);
    $EmployerUpdate->save();

    return  $parsedData['resource'];
}

function getCalendlyScheduleUrl($userUri, $userId)
{
    try {
        $calendly_url = config('app.calendly_url');
        $calendly_access_token = config('app.calendly_access_token');


        //   /  dd($calendly_access_token);
        $client = new Client();
        $response = $client->get($calendly_url . '/organization_memberships?user=' . $userUri, [
            'headers' => [
                'Authorization' => 'Bearer ' . $calendly_access_token,
                'Accept' => 'application/json',
            ]
            // You can also pass any query parameters if required
            // 'query' => [
            //     'param1' => 'value1',
            //     'param2' => 'value2',
            // ],
        ]);

        $statusCode = $response->getStatusCode();
        $data = $response->getBody()->getContents();

        $EmployerUpdate = EmployerDetail::where('user_id', $userId)->first();
        $calendly_details = json_decode($data);

        $arrayCount = count($calendly_details->collection);

        // Get the last element of the array
        $lastElement = $calendly_details->collection[$arrayCount - 1];

        $EmployerUpdate->calendly_details = json_encode($lastElement);
        $EmployerUpdate->save();

        $parsedData = json_decode($data, true);

        return true;
    } catch (\Exception $e) {


        $message = $e->getMessage();

        preg_match('/"message":"([^"]+)"/', $message, $matches);

        if (isset($matches[1])) {
            $errorMessage = $matches[1];
        } else {
            $errorMessage = "Something went to wrong";
        }

        return back()->with('error', $errorMessage);
    }
}

function calendlyInivitationList($email, $employerId)
{

    $client = new Client();


    $calendlydata = calendlyOwnerDetails();

    $parsedData = json_decode($calendlydata, true);
    $calendly_url = config('app.calendly_url');
    $calendly_access_token = config('app.calendly_access_token');


    $current_organization_id = str_replace($calendly_url . "/organizations/", "", $parsedData['resource']['current_organization']);
    $current_organization = $parsedData['resource']['current_organization'];


    try {
        // dd($current_organization);
        $response = $client->get($calendly_url . '/organizations/' . $current_organization_id . '/invitations?email=' . $email . '&count=1&sort=created_at:desc', [
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

        if (!empty($parsedData['collection'])) {
            $EmployerUpdate = EmployerDetail::where('user_id', $employerId)->first();

            $EmployerUpdate->calendly_invitation = $data;
            $EmployerUpdate->save();
        } else {

            try {
                $response = $client->post($calendly_url . '/organizations/' . $current_organization . '/invitations', [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $calendly_access_token,
                        'Accept' => 'application/json',
                    ],
                    // You can also pass any query parameters if required
                    'json' => [
                        'email' => $email,

                    ],
                ]);
            } catch (\Exception $e) {
                $message = $e->getMessage();

                preg_match('/"message":"([^"]+)"/', $message, $matches);

                if (isset($matches[1])) {
                    $errorMessage = $matches[1];
                } else {
                    $errorMessage = "Something went to wrong";
                }

                return back()->with('error', $errorMessage);
            }

            $statusCode = $response->getStatusCode();
            $data = $response->getBody()->getContents();
            $parsedData = json_decode($data, true);
            // dd($parsedData);




        }

        $EmployerUpdate = EmployerDetail::where('user_id', $employerId)->first();

        $EmployerUpdate->calendly_invitation = $data;
        $EmployerUpdate->save();

        return back()->with('success', 'The invitation has been successfully sent to your email. Please check your inbox.');
    } catch (\Exception $e) {
        $message = $e->getMessage();

        preg_match('/"message":"([^"]+)"/', $message, $matches);

        if (isset($matches[1])) {
            $errorMessage = $matches[1];
        } else {
            $errorMessage = "Something went to wrong";
        }

        return back()->with('error', $errorMessage);
    }
}

function notificationsCount()
{
    $count = Notification::where(['receiver_id' => auth()->user()->id, 'status' => '0'])->count();

    return $count;
}

function notificationsList()
{
    $notificationlists = Notification::where(['receiver_id' => auth()->user()->id, 'status' => '0'])->orderBy('created_at', 'desc')->take(3)->get();

    return $notificationlists;
}

function convertToAgoTime($date)
{
    $providedDate = Carbon::parse($date);

    // Current date and time
    $currentDate = Carbon::now();

    // Calculate the difference
    $diffInSeconds = $currentDate->diffInSeconds($providedDate);
    $diffInMinutes = $currentDate->diffInMinutes($providedDate);
    $diffInHours = $currentDate->diffInHours($providedDate);
    $diffInDays = $currentDate->diffInDays($providedDate);

    // Output the result
    if ($diffInSeconds < 60) {
        $notifytime = $diffInSeconds . ' sec ago';
    } elseif ($diffInMinutes < 60) {
        $notifytime = $diffInMinutes . ' min ago';
    } elseif ($diffInHours < 24) {
        $notifytime = $diffInHours . ' hr ago';
    } else {
        $notifytime = $diffInDays . ' days ago';
    }
    return $notifytime;
}




function isWordInTextSimple($word, $text)
{
    $patt = "/(?:^|[^a-zA-Z])" . preg_quote($word, '/') . "(?:$|[^a-zA-Z])/i";
    return preg_match($patt, $text);
}

function isWordInText($word, $text)
{

    if ((strpos($text, ucfirst($word)) > -1) || (strpos($text, strtoupper($word)) > -1)) {
        return true;
    }


    return false;
}

function downloadAndStorePDF($resume_url, $candidate_id)
{
    try {

        // $driveLink ='https://docs.google.com/document/d/1bG1RyJ2b88rAAMOxsc_xB5hQwcbWQkp5qAMsxI3Baww/edit?usp=drive_link';
        $driveLink = $resume_url;

        $parsedLink = explode('/', parse_url($driveLink, PHP_URL_PATH));

        $driveFileId = isset($parsedLink[3]) ? $parsedLink[3] : null;

        $directDownloadLink = "https://drive.google.com/uc?export=download&id={$driveFileId}";
        Log::error('directDownloadLink:=== ' . $directDownloadLink);
        $response = Http::get($directDownloadLink);
        // dd($response);
        Log::error('response_upload_error:=== ' . $response);
        if ($response->successful()) {
            $contentTypeHeader = $response->header('Content-Type');
            $extension = explode('/', $contentTypeHeader)[1]; // This will extract the extension from the Content-Type header
            $filename = 'candidate_resume/' . $candidate_id . '.' . $extension;
            $storefilename = $candidate_id . '.' . $extension;
            $localFilePath = public_path($filename); // Define your desired file path and name
            file_put_contents($localFilePath, $response->body());

            return $storefilename;
        } else {
            echo 'Failed to download the file.';
        }
    } catch (\Exception $e) {
        Log::error('Failed to download the file:=== ' . $e->getMessage());
    }
}

function inviteePayloadCreateCancel($candidateId=null,$jobId=null,$status =null)
{

    $inviteePayloadCreateCancel = CandidateJobStatusComment::where(['candidate_id' => $candidateId, 'status' => $status ,'job_id'=>$jobId])->first();

    return $inviteePayloadCreateCancel;
}
