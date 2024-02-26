<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// use Spatie\Permission\Models\Role;
// use Spatie\Permission\Models\Permission;
use App\Models\Admin\RoleMaster;
use App\Models\Admin\RolePermission;
// use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{ 
    
    /*** Display a listing of the resource.** @return \Illuminate\Http\Response*/
    
   
    
    /*** Display a listing of the resource.** @return \Illuminate\Http\Response*/
    public function index(Request $request)
    {
        $roles = RoleMaster::whereIn('status',['0','1'])->where(['business_id'=>Auth::user()->business_id])->orderBy('id', 'DESC')->paginate(10);
        // dd($roles);
        if($request->ajax())
             return view('admin.roles.ajax', compact('roles'));
        else
            return view('admin.roles.index', compact('roles'));
        // return view('admin.roles.index', compact('roles'))
        //     ->with('i', ($request->input('page', 1) - 1) * 10);
    } 
    
    /*** Show the form for creating a new resource.** @return \Illuminate\Http\Response*/
    public function create(Request $request)
    {
        // $type = $request->type;
        // $id = $request->id;
        // $form = RoleMaster::find($id);
        return view('admin.roles.create');
    } 
    
    /*** Store a newly created resource in storage.** @param  \Illuminate\Http\Request  $request* @return \Illuminate\Http\Response*/
    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'role' => 'required',
          
        //     ]);
        
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
            $new_role->role_type = "customer"; 
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
    
    /*** Display the specified resource.** @param  int  $id* @return \Illuminate\Http\Response*/
    // public function show($id)
    // {
    //     // // $role = Role::find($id);
    //     // // $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")->where("role_has_permissions.role_id", $id)->get();
    //     // return view('admin.roles.show', compact('role', 'rolePermissions'));
    // } 
    
    // /*** Show the form for editing the specified resource.** @param  int  $id* @return \Illuminate\Http\Response*/
    public function edit($id)
    {
        $role_id = base64_decode($id);
        // $permission = Permission::get();
        $roles = DB::table("role_masters")->where('id', $role_id)->first();
           
        return view('admin.roles.edit', compact('roles'));
    } 
    
    // /*** Update the specified resource in storage.
    //  *** @param  \Illuminate\Http\Request  $request* @param  int  $id* @return \Illuminate\Http\Response*/
    public function update(Request $request)
    {
        // $this->validate($request, [
        //     'role' => 'required',
          
        //     ]);
              
        // $business_id = 
        
        $id =base64_decode($request->id);
        // dd($id);
        // $service_id =json_encode($data);
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
            $new_role->role_type = "customer"; 
            $new_role->role= $request->role; 
            $new_role->status ='1';
            $new_role->save();
            

            // return redirect('/roles')
            //     ->with('success', 'Role updated successfully');
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

    public function roleChangeStatus(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $role_id=base64_decode($request->id);
        $type = base64_decode($request->type);
        DB::beginTransaction();
        try{
            // if($request->status==0)
            // {
            //     $users=DB::table('users')
            //             ->where(['user_type'=>'user','business_id'=>$business_id,'is_deleted'=>'0'])
            //             ->where('role',$role_id)
            //             ->get();
            //     if(count($users)>0)
            //     {
            //         return response()->json(['success'=>false]);
            //     }
            // }
            // $user = RoleMaster::find($role_id);
            // $user->status = $request->status;
            // $user->save();

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
    
    // /*** Remove the specified resource from storage.
    //  *** @param  int  $id* @return \Illuminate\Http\Response*/
    public function destroy(Request $request)
    {
        $business_id=Auth::user()->business_id;
        $role_id =base64_decode($request->id);
        // $id = $request->id;

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

        // return redirect('/roles')
        //     ->with('success', 'Role deleted successfully');
    }

     // get add permission page
     public function getAddPermissionPage(Request $request)
     {
         // ->whereNotIn('parent_id','0')
         $role_id = base64_decode($request->id);
         $business_id = Auth::user()->business_id;
         
         $role_data = DB::table('role_masters')->where(['business_id'=>Auth::user()->business_id,'status'=>'1','id'=>$role_id])->first();
        // dd($role_data);
        $action_route_count = DB::table('role_permissions')->where(['role_id'=>$role_id,'status'=>'1','business_id'=>Auth::user()->business_id])->count();         
        $action_route = DB::table('role_permissions')->where(['role_id'=>$role_id,'status'=>'1','business_id'=>Auth::user()->business_id])->first();        
        $permission  = DB::table('action_masters')->where(['route_group'=>'','status'=>'1','parent_id'=>'0'])->orderBy('display_order','ASC')->get();
        //dd($permission);
         return view('admin.roles.permission',compact('permission','role_id','action_route','role_data','action_route_count','business_id'));
     }

     public function addPermission(Request $request)
     {
        //  dd($request->business_id);
        $this->validate($request, [
            'permissions'      => 'required',
         ]);
        DB::beginTransaction();
        try{
                foreach($request->permissions as $permissions){
                    $data[] = $permissions;
                }
                $permissions_id =json_encode($data);
                $permission_data =
                        [
                            'business_id' => $request->business_id,
                            'role_id'        => $request->role_id,
                            'permission_id'  => $permissions_id,
                            'status'         => '1'
                        ];
                $count = DB::table('role_permissions')->where('role_id',$request->role_id)->count();
                if($count>0){
                DB::table('role_permissions')->where(['role_id'=>$request->role_id,'business_id'=>Auth::user()->business_id])->update($permission_data);
                }else{
                    $user_id = DB::table('role_permissions')->insertGetId($permission_data);
                }

                DB::commit();
                return redirect('/roles')
                    ->with('success', 'Permission updated successfully');
        }
        catch (\Exception $e) {
            DB::rollback();
            // something went wrong
            return $e;
        } 

     }
}

