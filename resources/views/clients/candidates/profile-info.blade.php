<style>
   .profile-infoleft {
    border-bottom: 1px solid #ced4da;
}
.nav-tabs {
    border-bottom: 1px solid #ced4da!important;
}
</style>
<p class="text-left profile-infoleft" style="font-size:14px;"><strong> Profile Info   </strong>  </p>
{{-- <hr style="margin-top:10px; margin-bottom:10px;"> --}}
<p class="text-left" style="display: grid;">
   <?php 
      $last_name ="";
      $name = explode(' ', $candidate->name);
       
      if( count($name ) >1 ){ $last_name = end($name); }
      ?>
   <label> <strong>First Name :</strong> {{ $name[0] }}</label>
   <!-- <label>   </label> -->
   <label> <strong>Middle Name :</strong> {{$candidate->middle_name?$candidate->middle_name:'N/A'}} </label>
   <label> <strong>Last Name :</strong> {{$last_name?$last_name:'N/A'}} </label>
   <!-- <label>  </label> -->
   <label> <strong>Father's Name :</strong> {{ $candidate->father_name }}</label>
   <label> <strong>DOB :</strong> {{ date('d-m-Y',strtotime($candidate->dob)) }}</label>
   <label> <strong>Gender :</strong> {{ $candidate->gender }}</label>

   <label> <strong>Email :</strong> {{ $candidate->email }}</label>
   <label> <strong>Aadhaar Number :</strong> {{ $candidate->aadhar_number!=NULL ? $candidate->aadhar_number : 'N/A' }}</label>
   <label> <strong>Phone :</strong> {{ "+".$candidate->phone_code."-".str_replace(' ','',$candidate->phone) }}</label>
    </p>
   <hr style="margin-top:10px; margin-bottom:10px;">
   <p class="text-left" style="display: grid;">
   <label> <strong>Emp. Code :</strong> {{ $candidate->client_emp_code }}</label>
   <label> <strong>Entity Code :</strong> {{ $candidate->entity_code?$candidate->entity_code:'N/A' }}</label>
   <label> <strong>Reference Number:</strong> {{ $candidate->display_id }}</label>
   </p>
   <hr style="margin-top:10px; margin-bottom:10px;">
   <p class="text-left" style="display: grid;">
   <label> <strong>Sla Name :</strong> {{ Helper::get_sla_name($sla_items[0]->sla_id)}}</label>
   <label> <strong>Checks Name :
       {{-- @foreach ($sla_items as $item) --}}
    </strong>{!! Helper::get_service_name_slot($sla_items[0]->alot_services)!!}
    </label>
       {{-- @endforeach --}}

    </p>