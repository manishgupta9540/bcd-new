@extends('layouts.admin')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
            <!-- ============ Body content start ============= -->
            <div class="main-content">				
 
                <div class="row">
				<div class="card text-left">
               <div class="card-body" style="height: 100vh;">
               
               <div class="col-md-12">
               <form class="mt-2" method="post" action="">
                @csrf
			   <div class="row">
			    <div class="col-md-12">
                <h4 class="card-title mb-1">Create a new candidate </h4> 
                    <p class="mt-1"> Select an option to create a candidate and start verifications </p>  		
				</div>
            
                
			    <div class="col-md-12">		
                <section style="display: block; height: 150px; border:1px solid #ddd;">
                    <div class="row-border" style="text-align:center; padding-top:50px; ">
                        <a href="{{ url('/candidates/create')}}" style="font-size:25px;"> With Email </a>
                    </div>
                    </section>
                </div>

                <div class="col-md-12 mt-3">		
                <section style="display: block; height: 150px; border:1px solid #ddd;">
                    <div class="row-border" style="text-align:center; padding-top:50px; ">
                        <a href="" style="font-size:25px;"> With JAF </a>
                    </div>
                    </section>
                </div>

                <div class="col-md-12 mt-3">		
                <section style="display: block; height: 150px; border:1px solid #ddd;">
                    <div class="row-border" style="text-align:center; padding-top:50px; ">
                        <a href="" style="font-size:25px;"> Send Link  </a>
                    </div>
                    </section>
                </div>
                
            <!--  -->

             </form>
            </div>
            </div>
				
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
			
        </div>

<script>
$(function(){

    $(document).on('change','.customer_type',function(e) {
        e.preventDefault();
        var selVal = $('.customer_type option:selected').val();
        if(selVal =='with_subscription')
        {
            $('.subscription_list').removeClass('d-none');
            $('.subscription_list').addClass('d-block');
            $('.sla_list').addClass('d-none');
            $('.sla_list').removeClass('d-block');
        }

        if(selVal =='with_sla')
        {   $('.subscription_list').removeClass('d-block');
            $('.subscription_list').addClass('d-none');
            $('.sla_list').removeClass('d-none');
            $('.sla_list').addClass('d-block');
        }
        
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
            if(response.success==true  ) {          
               $("tbody.contactList").prepend("<tr><td scope='row'><input type='checkbox'></td><td><a href=''>"+response.data.name+"</a></td><td> "+response.data.email+"</td><td>"+response.data.phone+"</td><td> </td><td>"+response.data.associated_company+"</td></tr>");
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


</script>


@endsection