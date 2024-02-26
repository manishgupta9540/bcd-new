<?php 

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use DB;
use Hash;

class SettingController extends Controller
{
    
    /**
     * Show the general.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $company= DB::table('users')->select('company_logo')->where(['business_id'=>Auth::user()->business_id])->first();

        return view('superadmin.settings.general', compact('company'));
    }

    /**
     * Show the jaf.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function jaf()
    {
        $company= DB::table('users')->select('company_logo')->where(['business_id'=>Auth::user()->business_id])->first();

        return view('superadmin.settings.jaf', compact('company'));
    }

    /**
     * Show the sla.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function sla()
    {
        $company= DB::table('users')->select('company_logo')->where(['business_id'=>Auth::user()->business_id])->first();

        return view('superadmin.settings.sla', compact('company'));
    }

    // upload  file.
    public function uploadCompanyLogo(Request $request)
    {        
    // dd($request);
    $extensions = array("xls","xlsx","xlm","xla","xlc","xlt","xlw","doc","pdf","docx","jpg","png","jpeg","PNG","JPG","JPEG","csv","gif","txt",".apk");

    if( $request->hasFile('file') ) {
    
    $result = array($request->file('file')->getClientOriginalExtension());

      if(in_array($result[0],$extensions))
      {                                
          $attachment_file  = $request->file('file');
          $orgi_file_name   = $attachment_file->getClientOriginalName();
          
          $fileName         = pathinfo($orgi_file_name,PATHINFO_FILENAME);

          $filename         = $fileName.'-'.time().'.'.$attachment_file->getClientOriginalExtension();
          $dir              = public_path('/uploads/company-logo/');            
          $request->file('file')->move($dir, $filename);
            
          $asset_id = NULL;
          $is_temp = 1;

          DB::table('users')          
          ->where(['business_id'=>Auth::user()->business_id])  
          ->update([                    
                        'company_logo'  => $filename,
                        'updated_at'    => date('Y-m-d H:i:s'),
                    ]);                                

            // file type 
            $type = url('/').'/images/file.jpg';
            $extArray = explode('.', $filename);
            $ext = end($extArray);
           
              if($filename != NULL)
              {
                  if($ext == 'jpg' || $ext == 'png' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'JPG' || $ext == 'PNG' || $ext == 'JPEG'  || $ext == 'GIF')
                  {              
                    $type = url('/').'/uploads/company-logo/'.$filename;
                  }                  
              }           

              return response()->json([
                'fail' => false,
                'filename' => $filename,
                'filePrev'=>$type
          ]); 

        }else{
            // Do something when it fails
            return response()->json([
                'fail' => true,
                'errors' => 'File type error!'
            ]);
        }

      }
      else{
            // Do something when it fails
            return response()->json([
                'fail' => true,
                'errors' => 'Please enter required input!'
            ]);
        }

    }

}

