<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Admin\HelperController;

// use App\Http\Controllers\HelperController;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\City;
use App\Country;
use App\State;
use Spatie\PdfToText\Pdf;
use Workbench\App\Models\User;
use Geeky\CV\ParserFacade as CV;
use Illuminate\Support\Facades\Auth;
use SoapClient;

class ResumeFetchController extends Controller
{
    public function index()
    {
        return view('admin.resumeFetch.index');
    }

    // public function upload(Request $request){

    //     $this->emptyDirs();
    //     if($request->has('file')){
    //         $file = $request->file('file');
    //         $ext  = $file->getClientOriginalExtension();
    //         $hash = md5(time());
    //         $file->storeAs('public/cv', $hash.'.'.$ext);

    //         if(in_array(strtolower($ext), ['doc', 'docx', 'rtf','pdf'])) {
    //             $resumeName = time() . '.' . $ext;
    //             $file->move(public_path('candidate_resume'), $resumeName);
    //         }
    //         $pdf  = public_path('candidate_resume/'.$resumeName);
    //         // $storepath = 'candidate_resume/'.$resumeName;
    //         $storepath = $resumeName;

    //         $user = $this->getData($pdf, $hash);
    //         $user['resume_path'] = $storepath;

    //         if(isset($request->candidates_id))
    //         {

    //             $user['resume'] = $user['resume_path'];
    //             $candidate_resume = $user;

    //             return redirect()->route('admin.bodCandidate.edit', [
    //                 'bodCandidate' => encrypt_data($request->candidates_id),
    //                 'candidate_resume' => $candidate_resume,
    //             ]);

    //         } else {
    //             $user = (object)$user;
    //             return redirect()->route('admin.bodCandidate.create')
    //             ->with('user', $user);
    //         }
    //     }

    //     return redirect()->route('admin.bodCandidate.create');
    // }

    public function upload(Request $request){
        // dd($request->all());
        $authId = Auth::user()->id;
        $roleName = getUserRole($authId);

        // dd($authRole);

            $this->emptyDirs();
            if($request->has('file')){
                $file = $request->file('file');
                // dd($file);
                $ext  = $file->getClientOriginalExtension();
                $resumeFileName  = $file->getClientOriginalName();


                $hash = md5(time());
                $file->storeAs('public/cv', $hash.'.'.$ext);

                if(in_array(strtolower($ext), ['doc', 'docx', 'rtf','pdf'])) {
                    $resumeName = time() . '.' . $ext;
                    $file->move(public_path('candidate_resume'), $resumeName);
                }
                $pdf  = public_path('candidate_resume/'.$resumeName);
                // $storepath = 'candidate_resume/'.$resumeName;
                $storepath = $resumeName;

                $user = $this->getData($pdf, $hash);
                $user['resume_path'] = $storepath;
                $user['resume_original_name'] = $resumeFileName;

                // if(isset($request->candidates_id))
                // {
                //     $user['resume'] = $user['resume_path'];
                //     $candidate_resume = $user;

                //     return redirect()->route('admin.bodCandidate.edit', [
                //         'bodCandidate' => encrypt_data($request->candidates_id),
                //         'candidate_resume' => $candidate_resume,
                //     ]);

                // } else {
                //     $user = (object)$user;
                //     return redirect()->route('admin.bodCandidate.create')
                //     ->with('user', $user);
                // }

                if (in_array($roleName, ['Super Admin', 'Admin'])) {

                    if($request->apply_job_resume == "apply_job_resume"){
                        $user['resume'] = $user['resume_path'];
                        // $candidate_resume = $user;
                        return $user;
                    } else {
                        if(isset($request->candidates_id))
                        {
                            $user['resume'] = $user['resume_path'];
                            $candidate_resume = $user;

                            return redirect()->route('admin.bodCandidate.edit', [
                                'bodCandidate' => encrypt_data($request->candidates_id),
                                'candidate_resume' => $candidate_resume,
                            ]);

                        } else {
                            $user = (object)$user;
                            return redirect()->route('admin.bodCandidate.create')
                            ->with('user', $user);
                        }
                    }

                } else if ($roleName == "Recruiter") {

                    if($request->apply_job_resume == "apply_job_resume"){
                        $user['resume'] = $user['resume_path'];
                        return $user;
                    } else {
                        if(isset($request->candidates_id))
                        {
                            $user['resume'] = $user['resume_path'];
                            $candidate_resume = $user;

                            return redirect()->route('admin.candidate.edit', [
                                'bodCandidate' => encrypt_data($request->candidates_id),
                                'candidate_resume' => $candidate_resume,
                            ]);

                        } else {
                            $user = (object)$user;
                            return redirect()->route('admin.candidate.create')
                            ->with('user', $user);
                        }
                    }


                } else {

                    if($request->apply_job_resume == "apply_job_resume"){
                        $user['resume'] = $user['resume_path'];
                        return $user;
                    } else {
                        if(isset($request->candidates_id))
                        {
                            $user['resume'] = $user['resume_path'];
                            $candidate_resume = $user;

                            return redirect()->route('admin.bodCandidate.edit', [
                                'bodCandidate' => encrypt_data($request->candidates_id),
                                'candidate_resume' => $candidate_resume,
                            ]);

                        } else {
                            $user = (object)$user;
                            return redirect()->route('admin.bodCandidate.create')
                            ->with('user', $user);
                        }
                    }
                }

            }

            // return redirect()->route('admin.bodCandidate.create');
    }


    public function emptyDirs(){

        $dirs = ['cv', 'images', 'tmp'];

        foreach ($dirs as $dir){

            $dir = storage_path() . '/app/public/'.$dir;

            $files = glob($dir . '/*');
            foreach ($files as $file) {
                if (is_file($file))
                    @unlink($file);
            }
        }
    }

    public function getData($pdf, $hash){

        $text = (new Pdf(env('PATH_PDFTOTEXT')))
            ->setPdf($pdf)->text();

        $textLayout = (new Pdf(env('PATH_PDFTOTEXT')))
            ->setOptions(['layout', 'r 96'])
            ->setPdf($pdf)->text();



        /* ******** */
        // $user = new User();
        // dd($this->getAddress($text));
        $user = [
            "candidate_name" => $this->getName($text),
            "email" => $this->getEmail($text),
            "contact_number" => $this->getPhone($text),
            "address" => $this->getAddress($text),

            // "nationality" => $this->getNationality($text),
            // "birthday" => $this->getBirthday($text),
            // "gender" => $this->getGender($text),
            // "linkedin" => $this->getLinkedInProfile($text),
            // "github" => $this->getGithubProfile($text),
            // "skills" => $this->getSkills($text),
            // "languages" => $this->getLanguages($text),
            // "image" => $this->getProfilePicture($text),
        ];


        // $user->fullname    = $this->getName($text);
        // $user->email       = $this->getEmail($text);
        // $user->phone       = $this->getPhone($text);
        // $user->nationality = $this->getNationality($text);
        // $user->birthday    = $this->getBirthday($text);
        // $user->gender      = $this->getGender($text);
        // $user->linkedin    = $this->getLinkedInProfile($text);
        // $user->github      = $this->getGithubProfile($text);
        // $user->skills      = $this->getSkills($text);
        // $user->languages   = $this->getLanguages($text);
        // $user->image       = $this->getProfilePicture($pdf, $hash);

        // //dd($this->getEducationSegment($text));
        // //dd($this->parseExperienceSegment($text));

        // $user->education   = $this->parseEducationSegment($textLayout);
        // $user->experience  = $this->parseExperienceSegment($textLayout);

        //dd($user);

        return $user;
    }

    public function getName($text){

        $userSegment = $this->getUserSegment($text);

        // dd($userSegment);
        // preg_match('/^[a-zA-Z\s]+$/', $input)

        $filteredData = array_filter($userSegment, function ($item) {
            $varrrr = [];
            if(preg_match('/^[a-zA-Z\s()\.\'-]+$/u', $item) == 1){
                $varrrr['name'] = $item;

            }
            return !empty($varrrr);
        });

        $filteredData = array_values($filteredData);
        return $filteredData[0];
        // die;
        // dd("testttt");

        // return $userSegment[0];

    }

    public function getEmail($text){

        $pattern = '/[a-z0-9_\-\+\.]+@[a-z0-9\-]+\.([a-z]{2,4})(?:\.[a-z]{2})?/i';

        preg_match_all($pattern, $text,$matches);

        if(count($matches) > 0){

            if(isset($matches[0][0])){
                return $matches[0][0];
            }
        }

        return null;
    }

    public function getAddress($text)
    {
        // echo 45345; die;
        $city = City::select('name')->where('country_code','US')->get()->toArray();
        // dd($city);

        $uniqueNames = array();
        foreach ($city as $item) {
            $uniqueNames[] = $item['name'];
        }

        $uniqueNames = array_unique($uniqueNames);
        // echo "<pre>"; print_r($uniqueNames); die;
        $largeString = $text;
        $wordArray = $uniqueNames;

        foreach ($wordArray as $word) {
            if (Str::contains($largeString, $word)) {
                // echo "<pre>"; print_r($word) ;
                return $word;
                // return response()->json(['success' => true, 'matched_word' => $word]);
            }
        }
        // die;
        return null;
    }

    public function getPhone($text){

        // $pattern = "/\d{9,}/i";
        $pattern = '/\b(?:\+1\s?)?(\d{3})[-.\s]?(\d{3})[-.\s]?(\d{4})\b/';

        // $text = str_replace(array(" ", "-", "(", ")", "/"), array("", "", "", "", ""), $text);
        // $text = preg_replace('/[^\d+]/', '', $text);
        // dd($text);

        preg_match($pattern, $text, $matches);
        // dd($matches);
        if(empty($matches)){
            return null;
        }
        $phoneNumber = $matches[0];
        // $cleanedPhoneNumber = substr($phoneNumber, 1);
        $text = str_replace("+1", "", $phoneNumber);

        // dd($cleanedPhoneNumber);








        // preg_match_all($pattern, $text,$matchesww);
            // dd($matchesww[0]);
            $patterns = '/\b(?:\+1\s?)?(\d{3})[-.\s]?(\d{3})[-.\s]?(\d{4})\b/';
        foreach ($matches as $number) {
            // dd($number);
            $text = str_replace("+1", "", $number);
            // dd($text);
            if (preg_match($patterns, $text, $matches)) {
                // dd($matches);
                $formattedNumber = "+1 {$matches[1]}-{$matches[2]}-{$matches[3]}";

                return $formattedNumber;
            }
        }
        return null;
    }

    public function getNationality($text){

        $userSegment = $this->getUserSegment($text);

        // dd($userSegment);

        $nationalities = Nationality::getNationalities();
        // echo "<pre>"; print_r($nationalities); die;
        $tok = new WhitespaceAndPunctuationTokenizer();

        foreach($userSegment as $line){

            $lineTokens = $tok->tokenize($line);

            foreach ($lineTokens as $token){
                if(strlen($token) > 3) {
                    if (in_array(ucfirst(strtolower($token)), $nationalities)) {
                        return $token;
                    }
                }
            }
        }
        return null;
    }

    public function getBirthday($text){

        $pattern = '/([0-9]{2})\/([0-9]{2})\/([0-9]{4})|([0-9]{2})\.([0-9]{2})\.([0-9]{4})/i';

        $userSegment = $this->getUserSegment($text);

        // dd($userSegment);

        foreach ($userSegment as $line){

            preg_match_all($pattern, $line,$matches);

            if(count($matches) > 0){
                if(isset($matches[0][0])){ echo 9999; die;
                    return $this->normalizeBirthDay($matches[0][0]);
                } echo 2222; die;
            }
        }

        return null;
    }

    public function getGender($text){

        $tok = new WhitespaceAndPunctuationTokenizer();

        $userSegment = $this->getUserSegment($text);

        foreach($userSegment as $line){

            $lineTokens = $tok->tokenize($line);

            foreach ($lineTokens as $token){
                if(in_array(strtolower($token), ['male', 'female'])){
                    return ucfirst($token);
                }
            }
        }

        return null;
    }

    public function getLinkedInProfile($text){

        $needle = "linkedin.com";

        $tokens = $this->getTokens($text);

        foreach($tokens as $token){

            $pos = strpos(strtolower($token), $needle);

            if ($pos > - 1) {
                return $token;
            }
        }

        return "";
    }

    public function getGithubProfile($text){

        $needle = "github.com";

        $tokens = $this->getTokens($text);

        foreach($tokens as $token){

            $pos = strpos(strtolower($token), $needle);

            if ($pos > - 1) {
                return $token;
            }
        }

        return "";
    }

    public function getSkills($text){

        $allSkills = Skill::getSkills();

        $skills = [];

        $text = $this->getText($text);

        foreach ($allSkills as $skill){

            if(HelperController::isWordInText($skill, $text)){

                $skills[] = $skill;
            }
        }

        return $skills;
    }

    public function getLanguages($text){

        $allLanguages = Skill::getLanguages();

        $languages = [];

        $text = $this->getText($text);

        foreach ($allLanguages as $language){

            if(HelperController::isWordInText($language, $text)){

                $languages[] = $language;
            }
        }

        return $languages;
    }

    public function getProfilePicture($pdf, $hash){

        $tmp = storage_path() . '/app/public/tmp';

        $cmd = env('PATH_PDFIMAGES') . ' -all -f 1 ' . $pdf . ' ' . $tmp . '/prefix';
        exec($cmd);

        $images = array_diff(preg_grep('~\.(jpeg|jpg|png)$~', scandir($tmp)), array('.', '..', '.DS_Store'));
        $images = array_slice($images, 0, 3, true);

        foreach ($images as $image) {

            $imageInfo = getimagesize($tmp . '/' . $image);

            $width  = $imageInfo[0];
            $height = $imageInfo[1];

            if ($height > 50) {

                if ($width > 200) {

                    $img = Image::make($tmp . '/' . $image);
                    $img->resize(200, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $img->save($tmp . '/' . $image);
                }

                $ext = File::extension($tmp . '/' . $image);

                if ($ext == 'png' || $ext == 'jpeg') {
                    $newImage = str_replace('png', 'jpg', $image);
                    $newImage = str_replace('jpeg', 'jpg', $newImage);

                    $img = Image::make($tmp . '/' . $image)->encode('jpg', 75);
                    $img->save($tmp . '/' . $newImage);
                    $image = $newImage;
                }

                $isFace = FaceDetect::extract($tmp . '/' . $image)->face_found;

                if ($isFace) {
                    $imageDir = storage_path() . "/app/public/images/".$hash . ".jpg";
                    FaceDetect::extract($tmp . '/' . $image)->save($imageDir);
                    break;
                }
            }
        }

        return (isset($imageDir))? $hash . ".jpg" : null;
    }

    public function getUserSegment($text){

        $segment = [];

        $lines = $this->getLines($text);

        $educationKeywords      = $this->getEducationSegmentKeywords();
        $degreeKeywords         = $this->getDegreeSegmentKeywords();
        $projectKeywords        = $this->getProjectSegmentKeywords();
        $skillKeywords          = $this->getSkillSegmentKeywords();
        $accomplishmentKeywords = $this->getAccomplishmentSegmentKeywords();
        $experienceKeywords     = $this->getExperienceSegmentKeywords();
        // echo "<pre>"; print_r($educationKeywords); die;
        foreach ($lines as $line){

            if(!$this->searchKeywordsInText($educationKeywords, $line) &&
                !$this->searchKeywordsInText($degreeKeywords, $line) &&
                !$this->searchKeywordsInText($projectKeywords, $line) &&
                !$this->searchKeywordsInText($skillKeywords, $line) &&
                !$this->searchKeywordsInText($accomplishmentKeywords, $line) &&
                !$this->searchKeywordsInText($experienceKeywords, $line)
              ){
                $segment[] = $line;
            } else {
                break;
            }
        }

        return $segment;
    }

    public function getLines($text){

        return array_values(array_filter(explode("\n", $text)));
    }

    public function getEducationSegmentKeywords(){

        return config('segments.education');
    }

    public function getDegreeSegmentKeywords(){

        return config('segments.degree');
    }

    public function getExperienceSegmentKeywords(){

        return config('segments.experience');
    }

    public function getSkillSegmentKeywords(){

        return config('segments.skill');
    }

    public function getProjectSegmentKeywords(){

        return config('segments.project');
    }

    public function getAccomplishmentSegmentKeywords(){

        return config('segments.accomplishment');
    }

    public function searchKeywordsInText($keywords, $text){

        foreach ($keywords as $keyword){
            if(isWordInText($keyword, $text)){
                return true;
            }
        }
        return false;
    }

}
