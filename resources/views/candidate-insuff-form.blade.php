@extends('layouts.app')
@section('content')
<style type="text/css">
    /*@media (min-width: 576px) and (max-width: 767.98px) { 
    img.images12 {
    width: 158px;
    position: relative;
    left: 30%;
    }

    }
    @media (max-width: 575.98px) { 
    img.images12 {
    width: 158px;
    position: relative;
    left: 30%;
    }
     }*/

     .instant-verify {
            background-color: #F9F9F9;
            padding: 55px 225px;
            margin: 50px 0px;
        }

        .instant-verify h3 {
            font-size: 30px;
            color: #142550;
            margin: 15px 0px;
            text-align: center;

        }

        .custom-input {
            margin: 0px 0px 10px 0px;
            padding: 5px;
            width: 100%;
            height: auto;

        }

        .verifiy-btn {
            background: #E10813;
            max-width: 200px;
            color: #fff;
            padding: 10px 15px;
            font-size: 12px;
            border-radius: 5px;
            margin-top: 25px;
        }

        .fw-600 {
            font-weight: 600;
        }

        .advance-feature {
            text-align: center;
        }

        .advance-feature h2 {
            color: #142550;
            font-weight: 600;
            padding: 25px 180px;
            margin: 20px 0px;
        }

        .advance-feature h6 {
            font-size: 18px;
            color: #142550;
            font-weight: 600;
            padding: 15px 0px;
        }

        .advance-feature p {
            color: #474747;
            line-height: 29px;
            text-align: justify;
            padding: 0px 20px;
        }

        .text-blue {
            color: #142550;
        }

        .text-para {
            color: #474747;
        }

        .para-custom {
            line-height: 29px;
            color: #444444;
        }

        .mt-80 {
            margin-top: 80px;
        }

        .hiring-process {
            background-color: #F9F9F9;
            padding: 50px 0px;
        }

        .first-container {
            /*background-image: url('images/verification_banner_no_text.jpg');
       background-size: cover;
       background-repeat: no-repeat;
       height: 590px;
       box-shadow: 0px 5px 10px #ddd;*/
            box-shadow: 0px 5px 10px #999;
            position: sticky;
            top: 0px;
            background: #fff;
            z-index: 4;

        }

        .banner-section {
            /* background-image: url('admin/images/Contact-us-ban.png');
       background-size: cover;
       background-repeat: no-repeat; */
            /* height: 100vh; */
            box-shadow: 0px 5px 10px #ddd;

        }

        .registration-menu {
            list-style-type: none;
            padding: 0px;
            /*position: absolute;
       right:130px;
       top:20px;
       z-index:2;*/
        }

        .registration-menu li {
            display: inline;
            margin: 5px 10px;
        }

        .registration-menu li a {
            font-weight: 500;
            line-height: 1;
            font-size: 17px;
            font-family: 'Ruda', sans-serif;
            color: #002e62 !important;
            text-decoration: none;

        }

        .registration-menu li a:hover {
            color: #ff0000 !important;
        }

        .sec-one-left {
            /* margin: 50px 0;
    padding: 50px 0; */
            /*   */
        }

        .sec-one-left h4 {
            color: #F63A55;
            font-size: 44px;
            font-weight: 600;

        }

        .sec-one-left h6 {
            font-size: 24px;
            color: #000000;
            font-weight: 600;
            line-height: 32px;
        }

        .contact-form {
            /*background:rgba(0, 46, 98, 0.55);*/
            color: #585858;
            /* text-align: center; */
            padding: 50px 10px;
        }

        .contact-form h4 {
            text-align: left;
            color: #171C3A;
            font-size: 30px;
            font-weight: 600;
            margin-bottom: 35px;
        }

        .contact-form label {
            text-align: left;
            font-weight: 600;
            margin-bottom: 5px !important;
        }

        .contact-form textarea {
            width: 100%;
        }

        .form-banner h6 {
            font-size: 18px;
            line-height: 24px;
        }

        .form-banner h6 span {
            color: #E11E26;
            font-weight: 600;
        }

        .registration-nav {
            position: relative;
            top: 0px;
            left: 0px;
            padding: 0px;
        }

        .verification-footer {
            /* background-color: #002E62; */
            background-color: #ACACAC;
            text-align: center;
            color: #fff !important;
            padding: 10px;
        }

        .sec-six-heading {
            padding: 50px 10px 0px 10px;
        }

        .sec-six-heading h4 {
            text-align: left;
            color: #171C3A;
            font-size: 30px;
            font-weight: 600;
            margin-bottom: 35px;
        }

        .sec-six-heading h6 {
            font-size: 18px;
            color: #447C8D;
            line-height: 24px;
        }

        .contact-ways {
            list-style-type: none;
            padding: 0px;
        }

        .contact-ways li {
            color: #002E62;
            margin: 40px 0px;
            font-weight: 600;
            display: flex;
            justify-content: start;
        }

        .contact-ways li .zmdi {
            color: #ff0000;
            font-size: 25px;
            margin-right: 10px;

        }

        .btn-opacity {
            opacity: .65;
        }

        @media only screen and (min-width: 320px) and (max-width: 767px) {
            /*.first-container{
       background-image: url('images/Contact-us-ban.png');
       background-size: cover;
       background-repeat: no-repeat;
       background-position: 0% 100%;
       height: 300px;
    }*/

            .banner-section {
                /* background-image: url('admin/images/Contact-us-ban.png');
       background-size: cover;
       background-repeat: no-repeat;
       background-position: 0% 100%; */
                /* height:130px; */
            }

            .registration-menu {

                background-color: #eacccc;
                top: 0px;
                position: relative;

            }

            .advance-feature h2 {
                color: #142550;
                font-weight: 600;
                padding: 25px 65px;
                margin: 20px 0px;
            }

            .instant-verify {
                background-color: #F9F9F9;
                padding: 38px;
                margin: 50px 0px;
            }

            .sec-one-left h4 {
                color: #F63A55;
                font-size: 25px;
                font-weight: 600;
            }

            .sec-one-left h6 {
                font-size: 13px;
                color: #000000;
                font-weight: 600;
                line-height: 23px;
            }

            .sec-six-heading {
                text-align: center;
                padding: 20px;
            }

            .sec-one-left {
                /* margin: 0px;
    padding: 10px 0px 50px 0px; */

                position: absolute;
                top: 30px;
            }


        }

        /*.ban-data{position: absolute;
    top:80px;left:0px;}*/

        /* .insuff-data {

            overflow-x: hidden;
            overflow-y: scroll;
        } */
        .filelabel {
    width: 100%;
    border: 1px solid #9e9ea7;
    border-radius: 5px;
    display: block;
    width: 100%;
    height: 45px;
    background: none;
    padding: 7px 10px;
    color: #000;
    font-size: 17px;
    margin: 0;
}
.filelabel i {
    display: block;
    font-size: 27px;
    font-weight: 600;
    padding-bottom: 5px;
}
.filelabel i,
.filelabel .title {
  color: grey;
  transition: 200ms color;
}
/* .filelabel:hover {
  border: 2px solid #1665c4;
}
.filelabel:hover i,
.filelabel:hover .title {
  color: #1665c4;
} */
#FileInput{
    display:none;
}
.custom-styling{
   margin-bottom: 11px;
    display: block;
	color: #2e3280 !important;
}
@media screen and (min-device-width: 320px) and (max-device-width: 767px) { 
    .sec-one-left {
    position: relative !important;
    }
    button.btn.bg-dark.text-white.w-25.mx-auto.d-table.raise_submit {
    margin-top: 30px;
}
.mobile-padding{padding-bottom: 5rem!important;}

}

</style>
<section class=" contact-banner d-flex justify-content-center align-items-center py-5 px-1" style="min-height: 100vh;">
   <div class="container">
      <div class="col-md-8 col-xs-12 col-sm-12 offset-md-2 border py-5 shadow mobile-padding px-1" >
         <form method="post" action="{{route('/candidateInsuffForm',['id'=>base64_encode($candidate_data->id)])}}" id="cand_insuff_form" class="cand_insuff_form" enctype="multipart/form-data">
            @csrf

            @if(count($candidate_insuff_data)>0)
               <div class="row ban-data ">
                  <div class="col-md-12">
                     <div class="form-group">
                        <label><b>Candidate Name:</b> {{$candidate_data->name}} ({{$candidate_data->display_id}})</label>
                     </div>
                  </div>
                  
                  <div class="row w-100">
                     @foreach ($candidate_insuff_data as $data)
                        <div class="col-md-6 card p-3 border-0">
                           <label><b>Check Name:</b> {{$data->verification_type=='Manual' ? $data->service_name.' - '.$data->check_item_number : $data->service_name}}</label>
                        </div>

                        @if($data->type_name=='pan' || $data->type_name=='educational' || $data->type_name=='employment')
                           @php
                              $form_data = $data->form_data;
                           @endphp
                           @if($form_data!=null)
                                 @php
                                    $input_item_data_array =  json_decode($form_data, true);
                                 @endphp

                                 @foreach ($input_item_data_array as $key => $input) 
                                    @php
                                       $key_val = array_keys($input);
                                       $input_val = array_values($input);
                                       // dd($key_val);
                                    @endphp
                                    @if($data->type_name=='pan')   
                                       @if(stripos($key_val[0],'PAN Number')!==false)
                                          <div class="col-6">
                                             <label><b>Pan Number:</b> {{$input_val[0]}}</label>
                                          </div>
                                       @endif
                                    @elseif($data->type_name=='educational')
                                       @if(stripos($key_val[0],'Degree')!==false)
                                          <div class="col-6">
                                             <label><b>Degree:</b> {{$input_val[0]}}</label>
                                          </div>
                                       @endif
                                    @elseif($data->type_name=='employment')
                                       @if(stripos($key_val[0],'Company name')!==false)
                                          <div class="col-6">
                                             <label><b>Company name:</b> {{$input_val[0]}}</label>
                                          </div>
                                       @endif
                                    @endif
                                 @endforeach
                           @endif
                        @endif

                        <div class="col-md-6 card p-3 border-0">
                           <label><b>Insuff Remark:</b> {{$data->insufficiency_notes}}</label>
                        </div>

                        @php
                           $insuff_attach=DB::table('insufficiency_attachments')->where(['jaf_form_data_id'=>$data->id,'status'=>'raise'])->get();
                        @endphp

                        @if(count($insuff_attach)>0)
                           <div class="col-md-12">
                              <label><strong>Insuff Attachments: </strong></label>
                              <div class="row">
                                 @foreach ($insuff_attach as $insuff)
                                    @php
                                       $path=url('/').'/uploads/raise-insuff/';
                                       if(stripos($insuff->file_platform,'s3')!==false)
                                       {
                                             $filePath = 'uploads/raise-insuff/';

                                             $disk = Storage::disk('s3');

                                             $command = $disk->getDriver()->getAdapter()->getClient()->getCommand('GetObject', [
                                                'Bucket'                     => Config::get('filesystems.disks.s3.bucket'),
                                                'Key'                        => $filePath.$insuff->file_name,
                                                'ResponseContentDisposition' => 'attachment;'//for download
                                             ]);

                                             $req = $disk->getDriver()->getAdapter()->getClient()->createPresignedRequest($command, '+10 minutes');

                                             $file = (string)$req->getUri();
                                       }
                                       else
                                       {
                                             $file=$path.$insuff->file_name;
                                       }
                                    @endphp
                                    <div class="col-md-4">
                                       <div class="image-area" style="width:110px;">
                                          <a href="{{$file}}" download="">
                                             <img src="{{stripos($insuff->file_name, 'pdf')!==false ? asset('/admin/images/icon_pdf.png') : $file}}" title="{{$insuff->file_name}}" alt="preview" style="height:100px;">
                                          </a>
                                       </div>
                                    </div>
                                 @endforeach
                              </div>
                           </div>
                        @endif
               
                        <div class="col-md-12">
                           <label for="label_name"> Comments: </label>
                           <textarea id="comments" name="comments-{{$data->id}}" class="form-control comments" placeholder=""></textarea>
                           <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-comments-{{$data->id}}"></p> 
                        </div>

                        <div class="col-md-12">
                           <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </label>
                           <input type="file" name="attachments-{{$data->id}}[]" id="attachments" multiple class="form-control attachments">
                           <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments-{{$data->id}}"></p>  
                        </div>
                     @endforeach
                     <br>
                  </div>
                  <div class="col-12">
                     <button type="submit" class="btn bg-dark text-white w-lg-25 mx-auto d-table raise_submit ">Submit </button>
                  </div>
               
               @else
                  <div class="row ban-data w-100">
                     <h3 class="text-center text-danger">Thank you for submitting the form !!</h3>
                  </div>
               @endif	
            </div>
         </form>
      </div>
   </div>
</section>
<script>
   $(document).on('submit', 'form#cand_insuff_form', function (event) {
         event.preventDefault();
         //clearing the error msg
         $('p.error_container').html("");
         $('.error_control').removeClass('border-danger');
         var loadingText = '<i class="fa fa-circle-o-notch fa-spin px-2"></i> loading...';
         var form = $(this);
         var data = new FormData($(this)[0]);
         var url = form.attr("action");
         $('.raise_submit').addClass('btn-opacity');
         $('.raise_submit').attr('disabled',true);
         if ($('.raise_submit').html() !== loadingText) {
            $('.raise_submit').html(loadingText);
         }
         $.ajax({
            type: form.attr('method'),
            url: url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,      
            success: function (response) {
               window.setTimeout(function(){
                  $('.raise_submit').removeClass('btn-opacity');
                  $('.raise_submit').attr('disabled',false);
                  $('.raise_submit').html('Submit');
               },2000);
               console.log(response);
               if(response.success==true) {          
                  // window.location = "{{ url('/')}}"+"/sla/?created=true";
                  // toastr.success('Check Your Mail to Confirm your account !');
                  toastr.success('Form Has Been Submitted Successfully !');
                  
                  window.setTimeout(function(){
                     window.location.reload();
                  },2000);
               }
               //show the form validates error
               if(response.success==false ) {                              
                  for (control in response.errors) {  
                     //$('form#insuff_form').find('.'+control).addClass('border-danger'); 
                     console.log(response.errors);
                     console.log(control);
                     $('form#cand_insuff_form').find('#error-'+control).html(response.errors[control]);
                  }
               }
            },
            error: function (xhr, textStatus, errorThrown) {
               // alert("Error: " + errorThrown);
            }
         });
         return false;
      });
</script>
@endsection

