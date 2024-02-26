<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class SubscriptionController extends Controller
{
    //

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $items = DB::table('subscription_plans')->get();
        // dd($items);
        return view('superadmin.subscriptions.index', compact('items'));
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        return view('superadmin.subscriptions.create');
    }
    
    //store 
    public function store(Request $request)
    {
        // Form validation
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric',
            'user_limit'=>'required|numeric',
            'verification_limit'=>'required|numeric',
         ]);

    	$data = 
    	[
    		'name'=>$request->input('name'),
            'price'=>$request->input('price'),
            'description'=>$request->input('description'),
            'candiates_allowed'=>$request->input('user_limit'),
    		'verifications_allowed'=>$request->input('verification_limit'),
    		'created_at'=> date('Y-m-d H:i:s')
    	];
    	
        DB::table('subscription_plans')->insert($data);

        return redirect()
        ->route('/subscriptions')
        ->with('success', 'subscriptions created successfully.'); 

        }

        /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $s_id = base64_decode($id);
        $data = DB::table('subscription_plans')->where(['id'=>$s_id])->first();

        return view('superadmin.subscriptions.edit', compact('data'));
    }
    
    //store 
    public function update( Request $request)
    {
        $s_id = base64_decode($request->input('id'));
        // Form validation
        $this->validate($request, [
            'name' => 'required',
            'price' => 'required|numeric',
            'user_limit'=>'required|numeric',
            'verification_limit'=>'required|numeric',
         ]);

        $data = 
        [
            'name'=>$request->input('name'),
            'price'=>$request->input('price'),
            'description'=>$request->input('description'),
            'candiates_allowed'=>$request->input('user_limit'),
            'verifications_allowed'=>$request->input('verification_limit'),
            'created_at'=> date('Y-m-d H:i:s')
        ];
        
        DB::table('subscription_plans')->where(['id'=>$s_id])->update($data);

        return redirect()
        ->route('/subscriptions')
        ->with('success', 'subscriptions updated successfully.'); 

        }

}
