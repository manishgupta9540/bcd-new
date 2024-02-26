<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class AppController extends Controller
{
    //
    public function index(Request $request)
    {
        $rules=[
            'image' => 'required',
            'image.*'  => 'required|mimes:jpg,jpeg,png,bmp,gif,svg|max:50000'
        ];

        $custom=[
            'image.*.max' => 'Image Size must be maximum 50 MB'
        ];

        $validator = Validator::make($request->all(),$rules,$custom);

        if ($validator->fails()) {            
            return response()->json(['status' => 'error',
                                    'message'=>'The given data was invalid.',
                                    'errors'=> $validator->errors()], 200);
        }

        if(count($request->image)>0)
        {
            $file_arr= [];
            foreach($request->image as $file)
            {
                $imagePath = public_path('/uploads/test_file/');  
                if(!File::exists($imagePath))
                {
                    File::makeDirectory($imagePath, $mode = 0777, true, true);
                }
                $image = $file;
                $profile_photo  = 'test-'.date('mdYHis').'-'.$image->getClientOriginalName();        
                $data = $image->move($imagePath, $profile_photo); 

                DB::table('file_attachments')->insert([
                    'image' => $profile_photo,
                    'created_at' =>date('Y-m-d H:i:s')
                ]);

                $file_arr[]=['name'=>$profile_photo,'url'=>url('/').'/uploads/test_file/'.$profile_photo];

            }
        }

        $response = [
            'status' => true,
            'file_result' => $file_arr,
            'mesage' => 'File Uploaded Successfully !!'
        ];

        return response()->json($response,200);
    }
}
