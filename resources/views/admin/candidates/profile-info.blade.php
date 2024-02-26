<style>
   .profile-infoleft {
    border-bottom: 1px solid #ced4da;
}
.nav-tabs {
    border-bottom: 1px solid #ced4da!important;
}
</style>
<p class="text-left profile-infoleft" style="font-size:14px;"><strong> Profile Info   </strong>  </p>

<p class="text-left" style="display: grid;">
   <?php 
      $last_name ="";
      $name = explode(' ', $candidate->name);

      $job_item_data = Helper::get_job_items($candidate->id,$candidate->business_id);
       
      if( count($name ) >1 ){ $last_name = end($name); }
      ?>
      <label> <strong>First Name :</strong> {{ $name[0] }}</label> 
      <!-- <label>   </label> -->
      <label> <strong>Middle Name :</strong> {{$candidate->middle_name?$candidate->middle_name:'N/A'}} </label>
      <label> <strong>Last Name :</strong> {{$candidate->last_name?$candidate->last_name:'N/A'}} </label>
      <label> <strong>Gender :</strong> {{$candidate->gender?$candidate->gender:'N/A'}} </label>
      <!-- <label>  </label> -->
      <label> <strong>Father's Name :</strong> {{ $candidate->father_name }}</label>
      <label> <strong>DOB :</strong> {{ date('d-m-Y',strtotime($candidate->dob)) }}</label>
      <label> <strong>Alternative Email :</strong>  {{ $candidate->alternative_email?$candidate->alternative_email:'N/A' }}</label>
      <label> <strong>Email :</strong>  {{ $candidate->email?$candidate->email:'N/A' }}</label>
      <label> <strong>Aadhaar Number :</strong> {{ $candidate->aadhar_number?$candidate->aadhar_number:'N/A' }}</label>
      <label> <strong>Phone :</strong> {{ "+".$candidate->phone_code."-".str_replace(' ','',$candidate->phone) }}</label>
    </p>
   <hr style="margin-top:10px; margin-bottom:10px;">
   <p class="text-left" style="display: grid;">
      <label> <strong>Emp. Code :</strong> {{ $candidate->client_emp_code?$candidate->client_emp_code:'N/A' }}</label>
      <label> <strong>Entity Code :</strong> {{ $candidate->entity_code?$candidate->entity_code:'N/A' }}</label>
      <label> <strong>Reference No:</strong> {{ $candidate->display_id?$candidate->display_id:'N/A' }}</label>
   </p>
   <hr style="margin-top:10px; margin-bottom:10px;">
   <p class="text-left" style="display: grid;">
      <label> <strong>SLA Name :</strong> {{ Helper::get_sla_name($sla_items[0]->sla_id)}}</label>
      <label><strong>TAT Type :</strong> {{ucwords($job_item_data->tat_type.'-Wise')}}</label>
      <label><strong>Price Type :</strong> {{ucwords($job_item_data->price_type.'-Wise')}}</label>
      <label><strong>Internal TAT :</strong> {{$job_item_data->tat}} {{$job_item_data->tat > 1 ? 'days' : 'day'}}</label>
      <label><strong>Client TAT :</strong> {{$job_item_data->client_tat}} {{$job_item_data->client_tat > 1 ? 'days' : 'day'}}</label>
      <label> <strong>Checks Name :
         {{-- @foreach ($sla_items as $item) --}} 
      </strong> {!! Helper::get_service_name_slot($sla_items[0]->alot_services) !!}</label>
         {{-- @endforeach --}}
    </p>
    <hr style="margin-top:10px; margin-bottom:10px;">
    <p class="text-left" style="display: grid;">
      <label><strong>FRNID :</strong> {{ $candidate->frnid!=NULL?$candidate->frnid:'N/A'}}</label>
      <label><strong>Recruiter :</strong> {{$candidate->recruiter!=NULL?$candidate->recruiter:'N/A'}}</label>
      <label><strong>Source :</strong> {{$candidate->source!=NULL?$candidate->source:'N/A'}}</label>
      <label><strong>Cost Code :</strong> {{$candidate->cost_code!=NULL?$candidate->cost_code:'N/A'}}</label>
      <label><strong>Grade :</strong> {{$candidate->grade!=NULL?$candidate->grade:'N/A'}}</label>
    </p>