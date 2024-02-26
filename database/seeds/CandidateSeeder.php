<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Models\Admin\Job;
use App\Models\Admin\JobItem;
use App\Models\Admin\JobSlaItem;
use App\Models\Admin\Task;
use App\Models\Admin\TaskAssignment;
use App\Models\Admin\Candidate;
use Illuminate\Support\Facades\DB;


class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (range(1,10000) as $index) 
        {
            $faker = Faker\Factory::create();
            $email=$faker->email;
            $email_user = DB::table('users')->where('email',$email)->first();

            if($email_user == NULL || $email_user == '' )
            {
                $userdata = 
                [
                    'business_id'   =>       766,
                    'user_type'     =>       'candidate',
                    'parent_id'     =>       91,
                    'name'          =>       $faker->name,
                    'first_name'    =>       $faker->text(5),
                    'middle_name'   =>       $faker->text(5),
                    'last_name'     =>       $faker->text(5),
                    'father_name'   =>       $faker->text(5),
                    'dob'           =>       '2000-01-25',
                    'case_received_date' =>  date('Y-m-d H:i:s'),
                    'gender'        =>       'male',
                    'email'         =>       $email,
                    'phone'         =>       $faker->numberBetween(1000000000,9999999999),
                    'phone_code'    =>       91,
                    'phone_iso'     =>       'in',
                    'created_by'    =>       91,
                    'created_at'    =>       date('Y-m-d H:i:s')
                ];          
                $user_id = DB::table('users')->insertGetId($userdata);
            
                $customer_company = DB::table('user_businesses')->select('company_name')->where(['business_id'=>91])->first();
                $client_company = DB::table('user_businesses')->select('company_name')->where(['business_id'=>766])->first();
                $u_id = str_pad($user_id, 10, "0", STR_PAD_LEFT);
                $display_id = trim(strtoupper(str_replace(array(' ','-'),'',substr($customer_company->company_name,0,4)))).'-'.trim(strtoupper(substr($client_company->company_name,0,4))).'-'.$u_id;
                
                DB::table('users')->where(['id'=>$user_id])->update(['display_id'=>$display_id]);

            
                $job_data = 
                [
                    'business_id'  => 766,
                    'parent_id'    => 91,
                    'sla_id'       => 66,
                    'total_candidates'=>1,
                    'send_jaf_link_required'=>0,
                    'created_at'   => date('Y-m-d H:i:s')
                ];
                $job_id = DB::table('jobs')->insertGetId($job_data); 

                $data = 
                [ 
                    'business_id'  => 766,
                    'job_id'       =>$job_id, 
                    'candidate_id' =>$user_id,
                    'sla_id'       => 66,
                    'sla_type'     => 'package',
                    'days_type'    => 'working',
                    'price_type'    => 'check',
                    'tat_type'     => 'case',
                    'incentive'     => '0.00',
                    'penalty'     => '0.00',
                    'tat'     => '10',
                    'client_tat'     => '10',
                    'jaf_status'   =>'pending',
                    'created_by'   => 91,
                    'created_at'   => date('Y-m-d H:i:s')
                ];
                $job_item_id = DB::table('job_items')->insertGetId($data);

                $cust_sla_items = DB::table('customer_sla_items')->where(['sla_id'=>66])->get();

                // if( count($request->input('services')) > 0 ){
                //   foreach($request->input('services') as $item){
                if(count($cust_sla_items)>0)
                {
                    foreach($cust_sla_items as $item)
                    {
                            $service_d=DB::table('services')->where('id',$item->service_id)->first();
                            $number_of_verifications=1;
                            $no_of_tat=1;
                            $incentive_tat=1;
                            $penalty_tat=1;
                            $price = 0;
                    
                            $sal_item_data = DB::table('customer_sla_items')->select('number_of_verifications','tat','incentive_tat','penalty_tat','price')->where(['sla_id'=>47,'service_id'=>$item->service_id])->first(); 
                            if($sal_item_data !=null){
                                $number_of_verifications= $sal_item_data->number_of_verifications;
                                $no_of_tat= $sal_item_data->tat;
                                $incentive_tat= $sal_item_data->incentive_tat;
                                $penalty_tat= $sal_item_data->penalty_tat;
                                $price= $sal_item_data->price;
                            }

                        $jobsladata = 
                        [
                            'business_id'=> 766, 
                            'job_id'      => $job_id, 
                            'job_item_id' => $job_item_id,
                            'candidate_id' =>$user_id,
                            'sla_id'      => 66,
                            'service_id'  => $item->service_id,
                            'jaf_send_to' => 'customer',
                            'number_of_verifications'=>1,
                            'tat'=>$no_of_tat,
                            'incentive_tat'=>$incentive_tat,
                            'penalty_tat'=>$penalty_tat,
                            'price'   => $price,
                            'sla_item_id' => $item->sla_id,
                            'created_by'  => 91,
                            'created_at'  => date('Y-m-d H:i:s')
                        ]; 

                        $jsi = DB::table('job_sla_items')->insertGetId($jobsladata);
                    }
                }

                $taskdata = 
                [
                    'name'       =>      $faker->text(5),
                    'parent_id'  =>      91,
                    'business_id'=>     766, 
                    'description' =>   'JAF Filling',
                    'job_id'      =>   $job_id, 
                    'priority'    =>   'normal',
                    'candidate_id' =>  $user_id,
                    'created_by'    => 91,
                    'created_at'  =>    date('Y-m-d H:i:s'),
                    'is_completed' =>  0,
                    'status'=>         1,
                ];
    
                $task = DB::table('tasks')->insertGetId($taskdata);

                $taskdata = 
                [
                    'parent_id'=> 2,
                    'business_id'=> 766,
                    'candidate_id' =>$user_id,   
                    'job_sla_item_id'  => $jsi,
                    'task_id'=> $task,
                    //'user_id' => 91,
                    
                ];
                DB::table('task_assignments')->insert($taskdata);
                
                $cadata = 
                [   'business_id'   => 766,
                    'candidate_id'  => $user_id,
                    'job_id'        => $job_id,
                    'name'          => $faker->name,
                    'first_name'    => $faker->text(5),
                    'middle_name'   => $faker->text(5),
                    'last_name'     => $faker->text(5),
                    'email'         => $faker->email,
                    'created_by'    => 91,
                    'created_at'    => date('Y-m-d H:i:s')
                ];
                DB::table('candidates')->insert($cadata);
           
            }
        }

    }
    
}
