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
    margin-top: 80px;
    width:0% !important;
}

}
    </style>
    <section class="contact-banner d-lg-flex justify-content-center align-items-center py-5 " style="min-height: 100vh;">
        <div class="container ">
            <div class="col-md-8 col-xs-12 col-sm-12 offset-md-2 border py-5 shadow">
                <form method="post" action="{{ route('/candidateDocumentForm', ['id' => base64_encode   ($candidate_data->id)]) }}"
                    id="document_form" class="document_form" enctype="multipart/form-data">
                    @csrf
               
                    @if($candidate_documents->status == 0)
                        <div class="row ban-data">
                                 <div class="col-md-12">
                                    <div class="form-group">
                                          <label><b>Candidate Name:</b> {{ $candidate_data->name }}
                                             ({{ $candidate_data->display_id }})</label>
                                             <input type="hidden" name="candidate_id" value="{{ $candidate_data->id }}">
                                    </div>
                                 </div>
                                 <div class=" row w-100">
                                    <div class="col-md-6 card p-3 border-0">
                                          <label for="">Document Name <span class="text-danger">*</span></label>
                                          <select name="document_name" id="document_name" class="form-control">
                                             <option value="">Selcet Document</option>
                                             <option value="Voter ID Card">Voter ID Card</option>
                                             <option value="Driving License">Driving Licence</option>
                                             <option value="Passport">Passport</option>
                                             <option value="Water Bill or Electricity Bill">Water Bill or Electricity Bill</option>
                                          </select>
                                          <p style="margin-bottom: 2px;" class="text-danger error-container"
                                             id="error-document_name"></p>
                                    </div>
                                    <div class="col-md-6 card p-3 border-0">
                                        <label for="label_name"> Attachments: <i class="fa fa-info-circle tool" data-toggle="tooltip" data-original-title="Only jpeg,png,jpg,pdf are accepted "></i> </label>
                                        <input type="file" name="attachments[]" id="attachments" multiple class="form-control attachments">
                                        <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-attachments"></p>  
                                    </div>
                                 </div>
                                 <div class="col-md-12">
                                    <label for="">ID Number</label>
                                    <input type="text" class="form-control" name="id_number">
                                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-id_number"></p>
                                 </div>
                                 <div class="col-md-12 mt-2">
                                 <label for="label_name"> Remarks <span class="text-danger">*</span></label>
                                 <textarea id="remarks" name="remarks" class="form-control remarks" placeholder=""></textarea>
                                 <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-remarks"></p>
                                 </div>
                        </div>
                        <div class="col-12">
                              <button type="submit" class="btn bg-dark text-white w-md-25 mx-auto d-table raise_submit ">Submit </button>
                        </div>
                    @else
                        <div class="row ban-data w-100">
                           <h4 class="text-center text-danger">Thank you for submitting the form. !!</h4>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
    <script>
        $(document).on('change','#document_name',function(){
            var document_name = $('#document_name').val();
         });

         $(document).on('change','#attachments',function(){
            var document_name = $('#document_name').val();
            if(!document_name){
               alert('please select one document');
               return false;
            }
         });

        $(document).on('submit', 'form#document_form', function(event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error_container').html("");
            $('.error_control').removeClass('border-danger');
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin px-2"></i> loading...';
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            $('.raise_submit').addClass('btn-opacity');
            $('.raise_submit').attr('disabled', true);
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
                success: function(response) {
                    window.setTimeout(function() {
                        $('.raise_submit').removeClass('btn-opacity');
                        $('.raise_submit').attr('disabled', false);
                        $('.raise_submit').html('Submit');
                    }, 2000);
                    console.log(response);
                    if (response.success == true) {
                        toastr.success('Form Has Been Submitted Successfully !');

                        window.setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                    }
                    //show the form validates error
                    if(response.success==false ) {  
                        var i=0;                            
                        for (control in response.errors) {   
                            $('#error-' + control).html(response.errors[control]);
                            if(i==0)
                            {
                                $('select[name='+control+']').focus();
                                $('input[name='+control+']').focus(); 
                                $('textarea[name='+control+']').focus();
                            }
                            i++;  
                        }
                    }
                },
                error: function(xhr, textStatus, errorThrown) {
                    // alert("Error: " + errorThrown);
                }
            });
            return false;
        });
    </script>

    {{-- <script>
      $("#FileInput").on('change',function (e) {
            var labelVal = $(".title").text();
            var oldfileName = $(this).val();
                fileName = e.target.value.split( '\\' ).pop();

                if (oldfileName == fileName) {return false;}
                var extension = fileName.split('.').pop();

            if ($.inArray(extension,['jpg','jpeg','png']) >= 0) {
                $(".filelabel i").removeClass().addClass('fa fa-file-image-o');
                $(".filelabel i, .filelabel .title").css({'color':'#208440'});
                $(".filelabel").css({'border':' 2px solid #208440'});
            }
            else if(extension == 'pdf'){
                $(".filelabel i").removeClass().addClass('fa fa-file-pdf-o');
                $(".filelabel i, .filelabel .title").css({'color':'red'});
                $(".filelabel").css({'border':' 2px solid red'});

            }
         else if(extension == 'doc' || extension == 'docx'){
            $(".filelabel i").removeClass().addClass('fa fa-file-word-o');
            $(".filelabel i, .filelabel .title").css({'color':'#2388df'});
            $(".filelabel").css({'border':' 2px solid #2388df'});
        }
            else{
                $(".filelabel i").removeClass().addClass('fa fa-file-o');
                $(".filelabel i, .filelabel .title").css({'color':'black'});
                $(".filelabel").css({'border':' 2px solid black'});
            }

            if(fileName ){
                if (fileName.length > 10){
                    $(".filelabel .title").text(fileName.slice(0,4)+'...'+extension);
                }
                else{
                    $(".filelabel .title").text(fileName);
                }
            }
            else{
                $(".filelabel .title").text(labelVal);
            }
      });
    </script> --}}
@endsection
