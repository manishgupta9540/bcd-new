<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class FaqController extends Controller
{
    //
    public function index()
    {
        $user_id=Auth::user()->id;
        $business_id=Auth::user()->business_id;
        $faq=DB::table('faq')->where(['business_id'=>$business_id,'status'=>0])->get();
        return view('admin.accounts.faq.index',compact('faq'));
    }

    public function create()
    {
        return view('admin.accounts.faq.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'question' => 'required',
            'answer' => 'required',
            ]);
              
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        
        //    $answer = $request->answer;
    
        //    $dom = new \DomDocument();
    
        //    $dom->loadHtml($answer, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);    
    
        //    $images = $dom->getElementsByTagName('img');
    
        //    foreach($images as $k => $img){
    
    
        //        $data = $img->getAttribute('src');
    
        //        list($type, $data) = explode(';', $data);
    
        //        list($type, $data) = explode(',', $data);
    
        //        $data = base64_decode($data);

        //     //    dd($data);
    
        //        $image_name= time().$k.'.png';
    
        //        $path = public_path('/uploads/faq/').$image_name;
        //        file_put_contents($path, $data);
    
        //        $img->removeAttribute('src');
    
        //        $img->setAttribute('src', $image_name);
    
        //     }

        //     $answer = $dom->saveHTML();
        // $service_id =json_encode($data);
        DB::beginTransaction();
        try{
                $faq_data=[
                    "business_id" => $business_id,
                    "user_id" => $user_id,
                    "question" => $request->question,
                    "answer" => $request->answer,
                    "created_at" => date('Y-m-d H:i:s'),
                    "updated_at" => date('Y-m-d H:i:s')
                ];

                $faq=DB::table('faq')->insert($faq_data);
                DB::commit();
                return redirect('/faq')
                    ->with('success', 'FAQ created successfully');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function edit($id)
    {
        $faq_id = base64_decode($id);
        // $permission = Permission::get();
        $faq = DB::table("faq")->where('id', $faq_id)->first();
           
        return view('admin.accounts.faq.edit', compact('faq'));
    }
    
    public function update(Request $request,$id)
    {
        $faq_id = base64_decode($id);

        $this->validate($request, [
            'question' => 'required',
            'answer' => 'required',
            ]);
        
        $business_id = Auth::user()->business_id;
        $user_id=Auth::user()->id;
        
        // $service_id =json_encode($data);

        // $answer = $request->answer;
 
        // $dom = new \DomDocument();
  
        // $dom->loadHtml($answer, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);    
  
        // $images = $dom->getElementsByTagName('img');
  
        // foreach($images as $k => $img){
  
        //     $data = $img->getAttribute('src');
  
        //     list($type, $data) = explode(';', $data);
  
        //     list($type, $data) = explode(',', $data);
  
        //     $data = base64_decode($data);
 
        //  //    dd($data);
            
        //     $image_name= time().$k.'.png';
  
        //     $path = public_path('/uploads/faq/').$image_name;
        //     file_put_contents($path, $data);
  
        //     $img->removeAttribute('src');
  
        //     $img->setAttribute('src', $image_name);
  
        //  }
 
        //  $answer = $dom->saveHTML();
        DB::beginTransaction();
        try{
            $faq_data=[
                "business_id" => $business_id,
                "user_id" => $user_id,
                "question" => $request->question,
                "answer" => $request->answer,
                "updated_at" => date('Y-m-d H:i:s')
            ];

            $faq=DB::table('faq')->where('id',$faq_id)->update($faq_data);
            DB::commit();
            return redirect('/faq')
                ->with('success', 'FAQ updated successfully');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 
    }

    public function destroy($id)
    {
        //
        $faq_id = base64_decode($id);
        DB::beginTransaction();
        try{
            $faq = DB::table("faq")->where('id', $faq_id)->update(['status'=>1]);
            DB::commit();
            return redirect('/faq')
                ->with('success', 'FAQ Deleted successfully');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

    }
}
