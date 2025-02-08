<?php

namespace App\Http\Controllers\Admin;

use App\AppliedJob;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Candidate;
use App\Country;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CommonController extends Controller
{

    public function ValidateUniqueItem(Request $request){

        if(isset($request->id)){

            $ishave = DB::table($request->table_name)
            ->where($request->column_name, $request->column_value)
            ->where('id','!=',$request->id)
            ->count();

            if($ishave == 0){
                return response()->json(['status' => true]);
            }else{
                return response()->json(['status' => false]);
            }


        }else{


            $ishave = DB::table($request->table_name)
                ->where($request->column_name, $request->column_value)
                ->count();

            if($ishave == 0){
                return response()->json(['status' => true]);
            }else{
                return response()->json(['status' => false]);
            }

        }
        
    }

}