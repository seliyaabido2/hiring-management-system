<?php
namespace App\Http\Controllers\Api\V1\Admin;

use App\CmsPage;
use App\Http\Controllers\Controller;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class PageController extends Controller
{

    public function index(Request $request)
    {

        try
        {
            $UserId = $request->user()->token()->user_id;

            // $userRoleName = getUserRole($UserId);

            // if($userRoleName != 'Super Admin'){

            //     return response()->json(['status' => false, 'message' => 'Not Authorized User!']);

            // }

            $cmsPage = CmsPage::all();

            return response()->json(['status' => true, 'data' => $cmsPage ]);

        }catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th]);
        }


    }

    // public function store(Request $request)
    // {
    //     try
    //     {
    //         $UserId = $request->user()->token()->user_id;

    //         $userRoleName = getUserRole($UserId);

    //         if($userRoleName != 'Super Admin'){

    //             return response()->json(['status' => false, 'message' => 'Not Authorized User!']);

    //         }

    //         $validation = Validator::make(

    //             $request->all(), [
    //                 'title'=>'required|unique:cms_pages',
    //                 'content'=>'required'           
    //             ]
    //         );

    //         if ($validation->fails()) {
    //             return response()->json(['status' => false, 'message' => $validation->errors()->first()]);
    //         } 

    //         $slug = Str::slug($request->input('title'));

    //         $cmsPagesinsertDataArr = [
    //             'title' => $request->input('title'),
    //             'slug' => $slug,
    //             'content' => $request->input('content'),

    //         ];

    //         $data = CmsPage::create($cmsPagesinsertDataArr);


    //        if($data == ''){
    //             return response()->json(['status' => false, 'message' => 'somthing went wrong in CMS page insert table!']);

    //         }else{

    //             return response()->json(['status' => true, 'message' => 'page insert successfully']);

    //         }

    //     }catch (\Throwable $th) {
    //         return response()->json(['status' => false, 'message' => $th]);
    //     }

    // }

    // public function update(Request $request){

    //     try
    //     {
    //         $UserId = $request->user()->token()->user_id;

    //         $userRoleName = getUserRole($UserId);

    //         if($userRoleName != 'Super Admin'){

    //             return response()->json(['status' => false, 'message' => 'Not Authorized User!']);

    //         }

    //         $validation = Validator::make(

    //             $request->all(), [
    //                 'content'=>'id',
    //                 'content'=>'title',
    //                 'content'=>'required',


    //             ]
    //         );

    
    //         $msg = $request->input('title')." Update successfully.";
    
    //     }
    //     catch (\Throwable $th) {
    //         return response()->json(['status' => false, 'message' => $th]);
    //     }
    // }



}