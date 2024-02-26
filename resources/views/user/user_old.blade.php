@extends('layouts.candidate')
<style>
    span.show-hide-password {
        position: absolute;
        top: 34px;
        right: 10px;
        font-size: 14px;
        color: #748a9c;
        cursor: pointer;
    }
</style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
            
            <div class="row" style="padding-top: 50px;">
			<div class="card text-left">
               <div class="card-body" >
               
               <div class="col-md-8 offset-md-2">
               <form class="mt-2" method="post" id="addpasswordForm" action="{{ url('/user/password/store') }}">
                @csrf
                    <div class="row" >
                    
                        @if ($message = Session::get('error'))
                        <div class="col-md-12">   
                            <div class="alert alert-danger">
                            <strong>{{ $message }}</strong> 
                            </div>
                        </div>
                        @endif

                        <div class="col-md-10">
                        <h4 class="card-title mb-1" style="border-bottom:1px solid #ddd;">User </h4> 
                            <p class="mt-1"> Set Password for login as a User</p>			
                        </div>
                        <input type="hidden" name="business_id" value="{{$business_id}}">
                        <input type="hidden" name="user_id" value="{{$user_id}}">
                        <input type="hidden" name="token_no" value="{{$token_no}}">
                    <div class="col-md-10">	
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" class="form-control" id="password" placeholder="Enter password"   value="{{ old('password') }}">
                                    <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>  
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="confirm-password">Confirm password</label>
                                    <input type="password" name="confirm-password" class="form-control" id="confirm-password" placeholder="Enter confirm password"  value="{{ old('password') }}">
                                    <span class="show-hide-password js-show-hide has-show-hide"><i class="fa fa-eye-slash"></i></span>
                                    <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-password"></p>  
                                </div>
                            </div>
                        </div> 

                        <div class="col-xs-12 col-sm-12 col-md-12 text-center"><button type="submit" class="btn btn-primary">Submit</button></div>   
                    </div>
                </div>
                <!--  -->
                </form>
               </div>
            </div>
				
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
        </div>
        
 
 </div>
 <script src="{{ asset('admin/js/jquery-3.3.1.min.js') }}"></script>
 <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script>
    // $(function(){
    
    $('.submit').on('click', function() {
        var $this = $(this);
        var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
        if ($(this).html() !== loadingText) {
          $this.data('original-text', $(this).html());
          $this.html(loadingText);
        }
        setTimeout(function() {
          $this.html($this.data('original-text'));
        }, 5000);
    });
    
    $('#createPasswordBtn').click(function(e) {
        e.preventDefault();
        $("#addpasswordForm").submit();
    });

    $(document).on('submit', 'form#addpasswordForm', function (event) {
        event.preventDefault();
        //clearing the error msg
        $('p.error_container').html("");
        
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
                    
                        //notify
                    toastr.success("password created successfully");
                        // redirect to google after 5 seconds
                        window.setTimeout(function() {
                            window.location = "{{ url('/')}}"+"/user/thank-you-user";
                        }, 2000);
                    
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

    $(document).on('click','.js-show-hide',function (e) {

        e.preventDefault();

        var _this = $(this);

        if (_this.hasClass('has-show-hide'))
        {
            _this.parent().find('input').attr('type','text');
            _this.html('<i class="fa fa-eye"></i>');
            _this.removeClass('has-show-hide');
        }
        else
        {
            _this.addClass('has-show-hide');
            _this.parent().find('input').attr('type','password');
            _this.html('<i class="fa fa-eye-slash"></i>');
        }


    });
    // });
    
    </script>
   