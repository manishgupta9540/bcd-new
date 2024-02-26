<?php 

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

// use Hash;

class UserController extends Controller
{
    
    /*** Display a listing of the resource.
     *** @return \Illuminate\Http\Response*/
    
    public function index(Request $request)
    {
        $users = User::where(['business_id'=>Auth::user()->business_id,'user_type'=>'user'])->orderBy('id', 'DESC')->paginate(5);
        return view('superadmin.users.index', compact('users'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }
    
    /*** Show the form for creating a new resource.
     * ** @return \Illuminate\Http\Response*/
    
    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();
        return view('superadmin.users.create', compact('roles'));
    } 
    
    /*** Store a newly created resource in storage.
    *** @param  \Illuminate\Http\Request  $request* @return \Illuminate\Http\Response*/

    public function store(Request $request)
    {
        $this->validate($request, 
        ['first_name'   => 'required', 
        'email'         => 'required|email|unique:users,email',
        'phone'         => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'password'      => 'required|regex:#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#|same:confirm-password', 
        'confirm-password' => 'required|regex:#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#|same:password',
        'roles'         => 'required']
        );

        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
        
         $user_data = [
                        'first_name'=>$request->input('first_name'),
                        'last_name' =>$request->input('last_name'),
                        'name'      =>$name,
                        'email'     =>$request->input('email'),
                        'phone'     =>$request->input('phone'),
                        'password'  => Hash::make($request->input('password')),
                        'user_type' =>'user',
                        'business_id'=>Auth::user()->business_id
                     ];

        $user = User::create($user_data);
        $user->assignRole($request->input('roles'));
        return redirect('/app/users')
            ->with('success', 'User created successfully');

    } 
    
    /*** Display the specified resource.** 
     * @param  int  $id* @return \Illuminate\Http\Response*/

    public function show($id)
    {
        $user = User::find($id);
        return view('superadmin.users.show', compact('user'));
    } 
    
    /*** Show the form for editing the specified resource.** 
     * @param  int  $id* @return \Illuminate\Http\Response*/

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        
        $userRole = $user
            ->roles
            ->pluck('name', 'name')
            ->all();
        return view('superadmin.users.edit', compact('user', 'roles', 'userRole'));
    } 
    
    /*** Update the specified resource in storage.** 
     * @param  \Illuminate\Http\Request  
     * $request* @param  int  $id* @return \Illuminate\Http\Response
    */

    public function update(Request $request, $id)
    {
        $this->validate($request, 
        ['first_name'   => 'required', 
        'email'         => 'required|email|unique:users,email,' . $id, 
        'password'      => 'regex:#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#|same:confirm-password',
        'confirm-password' => 'regex:#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#|same:password', 
        'roles'         => 'required']);

        $input = $request->all();
        if (!empty($input['password']))
        {
            $password = Hash::make($input['password']);
            DB::table('users')->where(['id'=>$id])->update(['password'=>$password]);
        }

        $name = trim(preg_replace('/^\s+|\s+$|\s+(?=\s)/', '', $request->input('first_name').' '.$request->input('last_name')));
        
        $user_data = [
            'first_name'=>$request->input('first_name'),
            'last_name' =>$request->input('last_name'),
            'name'      =>$name,
            'email'     =>$request->input('email'),
            'phone'     =>$request->input('phone'),
         ];
        
        DB::table('users')->where(['id'=>$id])->update($user_data);

        $user = User::find($id);
        
        DB::table('model_has_roles')->where('model_id', $id)->delete();
        $user->assignRole($request->input('roles'));
        
        return redirect('app/users')
            ->with('success', 'User updated successfully');

    } 
    
    /*** Remove the specified resource from storage.** 
     * @param  int  $id* @return \Illuminate\Http\Response*/

    public function destroy($id)
    {
        User::find($id)->delete();
        
        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully');
    }

    /**
     * Display the user detail.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changePassword()
    {       
        return view('superadmin.change-password');
    }

    /**
     * Update password
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePassword(Request $request)
    {
        $business_id = Auth::user()->business_id;

        $user_id = Auth::user()->id;
        // $this->validate($request, [
        //     'old_password' => 'required',
        //     'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
        //     'password_confirmation' => 'min:6'
        // ]);

        $rules = [
            'old_password' => 'required',
            'password' => 'min:10|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:10'
            
            ];
    
      
               $validator = Validator::make($request->all(), $rules);
                
               if ($validator->fails()){
                   return response()->json([
                       'success' => false,
                       'errors' => $validator->errors()
                   ]);
               }
        
       $raw_pass =$request->input('password');

       if (!preg_match("#.*^(?=.{10,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $raw_pass)){
          
            return response()->json([
                'success' => false,
                'custom'=>'yes',
                'errors' => ['password'=>'Password must be atleast 10 characters long including (Atleast 1 uppercase letter(A–Z), Lowercase letter(a–z), 1 number(0–9), 1 non-alphanumeric symbol(‘$%£!’) !']
            ]);
       }
        // hash password
       $password = bcrypt($request->input('password'));

       $login = DB::table('users as u')
      ->select('u.id','u.first_name','u.last_name','u.password','u.email','u.name')    
      ->where(['u.id'=>Auth::user()->id])
      ->first();

       $pStatus = Hash::check($request->get('old_password'),$login->password);
       
       // check password 
       if($pStatus === false)
       {    
        // return redirect('/app/change-password')->with('error', 'Old password is not correct!');    

        return response()->json([
            'success' => false,
            'custom'=>'yes',
            'errors' => ['old_password'=>'Please enter your correct old password']
          ]);   
       }
       if($pStatus === true) 
       {  
            DB::table('users')
            ->where('id', $user_id)
            ->update(['password'=>$password]);

            $email = $login->email;
            $name  = $login->name;
            $sender = DB::table('users')->where(['id'=>$business_id])->first();
            $data  = array('name'=>$name,'email'=>$email,'sender'=>$sender);
            
            Mail::send(['html'=>'mails.changed-password'], $data, function($message) use($email,$name) {
            $message->to($email, $name)->subject
                ('myBCD System - Your account credential');
            $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
            });
            
            Session::getHandler()->destroy(Auth::user()->session_id);

            DB::table('users')->where(['id' =>$user_id])->update(['session_id'=>NULL]);

            Auth::logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            return response()->json([
                'success' =>true,
                'custom'  =>'yes',
                'errors'  =>[]
            ]);
       }

        //    DB::table('users')
        //    ->where('id', Auth::user()->id)
        //    ->update(['password'=>$password]);
    
        //     return redirect('/app/change-password')->with('success', 'Your password has been updated!');  
    }

}

