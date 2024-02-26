@extends('layouts.superadmin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
      <div class="col-md-12">
         <div class="card text-left">
            <div class="card-body">
               <div class="row">
                  <div class="col-md-8">
                     <h4 class="card-title mb-3"> Contacts </h4>
                     <p> {{ count($items)}} Contacts </p>
                  </div>
                  <div class="col-md-4 text-right">
                     <div class="btn-group">
                        <button class="btn btn-secondary btn-lg dropdown-toggle" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   Actions  </button>
                        <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                        <button class="btn btn-secondary btn-lg" id="btn" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">   import  </button>               
                          
                        <button class="btn btn-danger" type="button"  onclick="openCreateContactForm()" aria-haspopup="true" aria-expanded="false"> <i class="fa fa-plus"></i> Create contact </button>             
                     </div>
                  </div>
               </div>
               <ul class="nav nav-tabs" id="myIconTab" role="tablist">
                  <li class="nav-item"><a class="nav-link active show" id="home-icon-tab" data-toggle="tab" href="#homeIcon" role="tab" aria-controls="homeIcon" aria-selected="true"><i class="nav-icon i-Pen-2 mr-1"></i> All Contacts </a></li>
                  <li class="nav-item"><a class="nav-link" id="profile-icon-tab" data-toggle="tab" href="#profileIcon" role="tab" aria-controls="profileIcon" aria-selected="false"><i class="nav-icon i-Pen-2 mr-1"></i> My Contacts </a></li>
                  <li class="nav-item"><a class="nav-link" id="contact-icon-tab" data-toggle="tab" href="#contactIcon" role="tab" aria-controls="contactIcon" aria-selected="false"><i class="nav-icon i-Pen-2 mr-1"></i> Unsigned Contact</a></li>
                  
               </ul>
               <div class="tab-content" id="myIconTabContent">
                  <div class="tab-pane fade active show" id="homeIcon" role="tabpanel" aria-labelledby="home-icon-tab">
                     <div class="row" style="margin-bottom:15px">
                        <div class="col-md-2">
                           <div class="search-bar">
                              <input type="text" placeholder="Search" autocomplete="off" style="padding: 5px;border-radius: 4px;background: #f6f8fc;">
                           </div>
                        </div>
                        <div class="col">
                           <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">   Contact owner  </button>
                           <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                           <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">   Create date </button>
                           <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                           <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">   Last Activity date  </button>
                           <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                           <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: #6a88c7!important;">   Lead status  </button>
                           <div class="dropdown-menu"><a class="dropdown-item" href="#">Action</a><a class="dropdown-item" href="#">Another Action</a><a class="dropdown-item" href="#">Something Else Here</a></div>
                        </div>
                        
                     </div>
                     <div class="table-responsive tableFixHead" style="height: 520px;">
                        <table class="table table-bordered ">
                           <thead>
                              <tr>
                                 <th scope="col"> <input type="checkbox"> </th>
                                 <th scope="col">Name </th>
                                 <th scope="col"> Email </th>
                                 <th scope="col"> Phone Number </th>
                                 <th scope="col"> Contact Owner </th>
                                 <th scope="col"> Associated Company</th>
                              </tr>
                           </thead>
                           <tbody class="contactList">
                              @if( count($items) > 0 )
                              @foreach($items as $item)
                              <tr>
                                 <td scope="row"><input type="checkbox"></td>
                                 <td scope="row"><a href=""> {{$item->name}} </a> </td>
                                 <td> {{$item->email}} </td>
                                 <td> {{$item->phone}} </td>
                                 <td>  </td>
                                 <td> {{$item->associated_company}} </td>
                              </tr>
                              @endforeach
                              @else
                              <tr><td colspan="7"> <h3>No record!</h3> </td></tr>
                             @endif
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <!--  -->
                  <div class="tab-pane fade" id="profileIcon" role="tabpanel" aria-labelledby="profile-icon-tab">
                     <div class="table-responsive">
                        <table class="table table-bordered">
                           <thead>
                              <tr>
                                 <th scope="col"> <input type="checkbox"> </th>
                                 <th scope="col">Name new</th>
                                 <th scope="col"> Email </th>
                                 <th scope="col"> Phone Number </th>
                                 <th scope="col"> Contact Owner </th>
                                 <th scope="col"> Associated Company</th>
                              </tr>
                           </thead>
                           <tbody class="contactList">
                               @if( count($items) > 0 )
                               @foreach($items as $item)
                              <tr>
                                 <td scope="row"><input type="checkbox"></td>
                                 <td scope="row"><a href=""> {{$item->name}} </a> </td>
                                 <td> {{$item->email }} </td>
                                 <td> {{$item->phone}} </td>
                                 <td> Mithilesh Sah </td>
                                 <td> {{$item->associated_company}} </td>
                              </tr>
                              @endforeach
                             @endif
                           </tbody>
                        </table>
                     </div>
                  </div>
                  <div class="tab-pane fade" id="contactIcon" role="tabpanel" aria-labelledby="contact-icon-tab">
                     <div class="table-responsive">
                        <table class="table table-bordered">
                           <thead>
                              <tr>
                                 <th scope="col">#</th>
                                 <th scope="col">Product</th>
                                 <th scope="col">Date</th>
                                 <th scope="col">Price</th>
                                 <th scope="col">Status</th>
                                 <th scope="col">Action</th>
                              </tr>
                           </thead>
                           <tbody>
                              <tr>
                                 <th scope="row">1</th>
                                 <td>Watch</td>
                                 <td>12-10-2019</td>
                                 <td>$30</td>
                                 <td><span class="badge badge-success">Delivered</span></td>
                                 <td>
                                    <button class="btn btn-success" type="button"><i class="nav-icon i-Pen-2"></i></button>
                                    <button class="btn btn-danger" type="button"><i class="nav-icon i-Close-Window"></i></button>
                                 </td>
                              </tr>
                              <tr>
                                 <th scope="row">2</th>
                                 <td>Iphone</td>
                                 <td>23-10-2019</td>
                                 <td>$300</td>
                                 <td><span class="badge badge-info">Pending</span></td>
                                 <td>
                                    <button class="btn btn-success" type="button"><i class="nav-icon i-Pen-2"></i></button>
                                    <button class="btn btn-danger" type="button"><i class="nav-icon i-Close-Window"></i></button>
                                 </td>
                              </tr>
                              <tr>
                                 <th scope="row">3</th>
                                 <td>Watch</td>
                                 <td>12-10-2019</td>
                                 <td>$30</td>
                                 <td><span class="badge badge-warning">Not Delivered</span></td>
                                 <td>
                                    <button class="btn btn-success" type="button"><i class="nav-icon i-Pen-2"></i></button>
                                    <button class="btn btn-danger" type="button"><i class="nav-icon i-Close-Window"></i></button>
                                 </td>
                              </tr>
                           </tbody>
                        </table>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         </div>
      </div>
   </div>
   <!-- Footer Start -->
   <div class="flex-grow-1"></div>
  
</div>

<style>
/*----------------------
   contact toggle form
----------------------*/

#formSidebar{
   display:none; 
   height:100vh; 
   width:530px; 
   z-index:101; 
   position:fixed; 
   top:0; 
   right:0px; 
   background-color:#fff; 
   box-shadow: 0px 0px 6px 1px #000;}

#formSidebar:before {
    content: "";
    position: fixed;
    background: rgba(255,255,255,0.7);
    width: 100%;
    height: 100vh;
    /* left: 0px; */
    right: 530px;
}

#formSidebar>div{background-color:#616161; padding:20px 10px;width:100%;}

#formSidebar>div>p{color:white; font-size:30px; font-weight:bold; float:right; position:relative;top:-45px; cursor: pointer;}

#formSidebar>form{overflow:auto; height:550px;padding:10px 20px; clear:both; }

#formSidebar>form label{font-weight:bold;}

#formSidebar>div>button{color: #f44336; background-color:#fff; border: 1px solid #f44336; padding:5px 10px; margin:0px 2px; border-radius:5px;}

#formSidebar>div>button:hover{color:#fff; background-color:#f44336; }

#formSidebar>div>button.active{color:#fff; background-color:#f44336; }

/* width */
#formSidebar>form::-webkit-scrollbar {
  width:10px;
}

/* Track */
#formSidebar>form::-webkit-scrollbar-track {
  box-shadow: inset 0 0 5px grey; 
  border-radius: 10px;
}
 
/* Handle */
#formSidebar>form::-webkit-scrollbar-thumb {
  background: #f44336 ; 
  border-radius: 10px;
}

/* Handle on hover */
#formSidebar>form::-webkit-scrollbar-thumb:hover {
  background:#e21b0c; 
}
</style>
<!--  -->

<!-- Create contact toggle form -->
<div class="" id="formSidebar">
   <div >
      <h4 class="text-white"> Create a contact</h4>
      <p  onclick="closeCreateContactForm()" class="text-white"  >&times;</p>
   </div>
   <form action="{{ url('/app/contacts/store') }}" method="post" id="addContactForm">
   @csrf
      <div class="form-group">
         <label for="email">Email address</label>
         <input type="email" class="form-control" id="email" name="email"  placeholder="Enter email">
         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-email"></p>
      </div>
      <div class="form-group">
         <label for="fname">First name</label>
         <input type="text" class="form-control" id="first_name" name="first_name"  placeholder="Enter first name">
         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-first_name"></p>
      </div>
      <div class="form-group">
         <label for="lname">Last name</label>
         <input type="text" class="form-control" id="last_name" name="last_name"  placeholder="Enter last name">
      </div>
      <div class="form-group">
         <label for="phone">Phone number</label>
         <input type="tel" class="form-control" id="phone" name="phone" maxlength="10"  placeholder="Enter phone number">
         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-phone"></p>
      </div>
      <div class="form-group">
         <label for="lifecycle-stage">Type</label>
         <select class="form-control"  name="type">
         <option value="">-Select-</option>
            <option value="Client">Client</option>
            <option value="Vendor">Vendor</option>
         </select>
         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-type"></p>
      </div>
      <div class="form-group">
         <label for="lead-status"> Status</label>
         <select class="form-control" name="status">
            <option value="">-Select-</option>
            <option value="Existing" >Existing</option>
            <option value="Perspective">Perspective</option>
         </select>
         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-status"></p>
      </div>

      <div class="form-group">
         <label for="job-title" >Associated Company</label>
         <input type="text" class="form-control" name="company" id="company"  placeholder="Enter company">
         <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-company"></p>
      </div>

      <div class="form-group">
         <label for="contact-owner">Contact owner</label>
         <select class="form-control" id="contact-owner" name="contact_owner">
            <option value="">No Owner</option>
            @if( count($users) > 0)
               @foreach($users as $item)
               <option value="{{ $item->id }}">{{ $item->name}}</option>
               @endforeach
            @endif
         </select>
      </div>
      <div class="form-group">
         <label for="job-title" >Job title</label>
         <input type="text" class="form-control" name="job-title" id="job-title"  placeholder="Enter job title">
      </div>
   </form>
   <div class="bg-white border-top">
      <button type="submit" id="saveContact" class=" btn btn-danger"> Create Contact</button>
      <button type="button" class="btn btn-secondary" onclick="closeCreateContactForm()"> Cancel</button>
   </div>
</div>

<!-- close nav sidebar -->

<script>
$(function(){

   $('#saveContact').click(function(e) {
        e.preventDefault();
        $("#addContactForm").submit();
    });

   $(document).on('submit', 'form#addContactForm', function (event) {
   event.preventDefault();
   //clearing the error msg
    $('p.error_container').html("");
   $("#coupon-error").html("");
   $("#otp_error").html("");   
   $('#guest-address-error').html("");

   var form = $(this);
   var data = new FormData($(this)[0]);
   var url = form.attr("action");

    $.ajax({
         type: form.attr('method'),
        url: url,
        data: data,
        cache: false,
        contentType: false,
        processData: false,      
        success: function (response) {

            console.log(response);
            var contact_owner = ""; 
            if(response.success==true  ) {   
               
               if(response.is_owner==true)
               {
                  contact_owner = response.data.f_name+' '+response.data.l_name;
               }      
               $("tbody.contactList").prepend("<tr><td scope='row'><input type='checkbox'></td><td><a href=''>"+response.data.name+"</a></td><td> "+response.data.email+"</td><td>"+response.data.phone+"</td><td> "+contact_owner+"</td><td>"+response.data.associated_company+"</td></tr>");
               closeCreateContactForm();
            }
            //show the form validates error
            if(response.success==false ) {                              
                for (control in response.errors) {   
                    $('#error-' + control).html(response.errors[control]);
                }
            }
        },
        error: function (xhr, textStatus, errorThrown) {
            // alert("Error: " + errorThrown);
        }
    });
    return false;
   });
});

//
function openCreateContactForm() {
   $('#addContactForm')[0].reset();
   document.getElementById("formSidebar").style.display = "block";
}
   
function closeCreateContactForm() {
   document.getElementById("formSidebar").style.display = "none";
}

</script>

@endsection