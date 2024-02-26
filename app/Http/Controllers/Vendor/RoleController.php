<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin\RoleMaster;
use App\Models\Admin\RolePermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $roles = RoleMaster::whereIn('status',['0','1'])->where(['business_id'=>Auth::user()->business_id])->orderBy('id', 'DESC')->paginate(10);
    
        if($request->ajax()){
            return view('vendor.roles.ajax', compact('roles'));
        }
        else{
            return view('vendor.roles.index', compact('roles'));
        }  
    } 

    public function create()
    {
        return view('vendor.roles.create');
    }

    public function store(Request $request)
    {
        $rules=[
            'role' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
              
        $business_id = Auth::user()->business_id;
        
        $user_id=Auth::user()->id;
        
        DB::beginTransaction();
        try{
            $role_exist = DB::table('role_masters')->where(['role'=>$request->role,'business_id'=>$business_id,'status'=>'1'])->count();

            if($role_exist > 0)
            {
                return response()->json([
                    'success' => false,
                    'errors' => ['role' => 'This Role is Already Exist!']
                ]);
            }
            // $service_id =json_encode($data);
            $new_role = new RoleMaster();
            $new_role->business_id =$business_id;
            $new_role->created_by =$user_id;
            $new_role->role_type = "vendor"; 
            $new_role->role= $request->role; 
            $new_role->status ='1';
            $new_role->save();
            
            // dd($new_role);
                $data =[];
            if ($new_role) {
                # code...
           
                $data =["26"];
            
                $permissions_id =json_encode($data);
                $permission_data =
                        [
                            'business_id' => $new_role->business_id,
                            'role_id'        => $new_role->id,
                            'permission_id'  => $permissions_id,
                            'status'         => '1'
                        ];
                // $count = DB::table('role_permissions')->where('role_id',$new_role->id)->count();
                // if($count>0){
                // DB::table('role_permissions')->where(['role_id'=>$request->role_id,'business_id'=>Auth::user()->business_id])->update($permission_data);
                // }else{
                    $user_id = DB::table('role_permissions')->insertGetId($permission_data);
                // }
            }
            // return redirect('/roles')
            //     ->with('success', 'Role created successfully');
            DB::commit();
            return response()->json([
                'success' => true,
                'errors' => []
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }    
    }

    public function edit($id)
    {
        $role_id = base64_decode($id);

        $roles = DB::table("role_masters")->where('id', $role_id)->first();
           
        return view('vendor.roles.edit', compact('roles'));
    } 

    public function update(Request $request)
    {
        $id =base64_decode($request->id);
       
        $business_id=Auth::user()->business_id;
        
        $rules=[
            'role' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
    
        if ($validator->fails()){
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ]);
        }
        DB::beginTransaction();
        try{
            $role_exist = DB::table('role_masters')->where(['role'=>$request->role,'business_id'=>$business_id,'status'=>'1'])->count();

            if($role_exist > 0)
            {
                return response()->json([
                    'success' => false,
                    'errors' => ['role' => 'This Role is Already Exist!']
                ]);
            }

            $user_id=Auth::user()->id;

            $new_role = RoleMaster::find($id);
            $new_role->business_id =Auth::user()->business_id;
            $new_role->updated_by =$user_id;
            $new_role->role_type = "vendor"; 
            $new_role->role= $request->role; 
            $new_role->status ='1';
            $new_role->save();
            

            DB::commit();
            return response()->json([
                'success' => true,
                'errors' => []
            ]);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }    
    }
    
    public function delete(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $role_id =base64_decode($request->id);
        
        $users=DB::table('users')
                    ->where(['user_type'=>'user','business_id'=>$business_id,'is_deleted'=>'0'])
                    ->where('role',$role_id)
                    ->get();

        if(count($users)>0)
        {
            return response()->json(['success'=>false]);  
        }

        $privacy = RoleMaster::find($role_id);
        $privacy->status = '2'; //Association Status in delete mode
        $privacy->save();

        return response()->json(['success'=>true]);  
    }

    public function roleChangeStatus(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $role_id=base64_decode($request->id);
        $type = base64_decode($request->type);
        DB::beginTransaction();
        try{
            if(stripos($type,'disable')!==false)
            {
                $users=DB::table('users')
                    ->where(['user_type'=>'user','business_id'=>$business_id,'is_deleted'=>'0'])
                    ->where('role',$role_id)
                    ->get();
                if(count($users)>0)
                {
                    return response()->json(['success'=>false]);
                }

                $user = RoleMaster::find($role_id);
                $user->status = '0';
                $user->save();
            }
            elseif(stripos($type,'enable')!==false)
            {
                $user = RoleMaster::find($role_id);
                $user->status = '1';
                $user->save();
            }

            DB::commit();
            return response()->json(['success'=>true,'type'=>$type,'message'=>'Status change successfully.']);
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        }    
    }
}
