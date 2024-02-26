@extends('layouts.admin')
<style>
    .disabled-link{
      pointer-events: none;
    }
    .action-data
   {
      max-height: 300px;
      overflow-y: auto;
   }
  </style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
    <!-- ============ Body content start ============= -->
    <div class="main-content">
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li><a href="{{ url('/home') }}">Dashboard</a></li>
                <li><a href="{{ url('/candidates') }}">Candidate</a></li>
                <li>Create New</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="card text-left">
                <div class="card-body" style="">
                    <div class="col-12">
                        <section>
                            @include('admin.candidates.create.menu')
                        </section>
                        <marquee width="60%" direction="left" onmouseover="this.stop();" onmouseout="this.start();" height="50px">
                            Note<span class="text-danger">*</span>:- Click to download Excel format to create multiple candidate at a time. <a href="{{ env('CANDIDATE_CUSTOM_EXCEL_PATH') }}" ><i class="far fa-hand-point-right"></i> Excel <i class="far fa-hand-point-left"></i></a>
                        </marquee>
                    </div>
                    <div class="col-md-8 offset-md-2">
                        <form class="mt-2" method="post" id="addCandidateForm" action="{{ url('/candidates/create/custom') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-10">
                                    <h4 class="card-title mb-1" style="border-bottom:1px solid #ddd;">Add a new candidate </h4> 
                                    <p class="mt-1"> Fill the required details </p>			
                                </div>
                                <div class="col-md-10">
                                    <div class="form-group">
                                        <label for="service">Select a Customer <span class="text-danger">*</span></label>
                                        <select class="form-control customer" name="customer" id="customer">
                                            <option value="">-Select-</option>
                                            @if( count($customers) > 0 )
                                                @foreach($customers as $item)
                                                    <option value="{{ $item->id }}">{{ ucfirst($item->company_name).' - '.$item->name }}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-customer"></p>
                                    </div>
                                    <div class="sla-div d-none">
                                        <div class="form-group"> 
                                            <label for="service">Select a SLA <span class="text-danger">*</span></label> 
                                            <select class="form-control slaList" name="sla"> 
                                                <option value="">-Select-</option> 
                                                @if( count($slas) > 0 ) 
                                                    @foreach($slas as $sla) 
                                                        <option value="{{ $sla->id }}" >{{ ucfirst($sla->title) }}</option> 
                                                    @endforeach 
                                                @endif 
                                            </select> 
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p> 
                                        </div>
                                        <div class="form-group SLAResult"> 
                                        </div> 
                                        <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>
                                    </div>
                                    <div class="file-div d-none">
                                        <div class="form-group">
                                            <label for="service">Select a file <span class="text-danger">*</span></label>
                                            <input class="form-control file" type="file" id="csv_file" name="file"  accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" >
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-file"></p>
                                        </div>
                                        <button class="btn btn-info submit" type="submit" >Import User Data</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal"  id="excel_data_mdl">
    <div class="modal-dialog modal-lg" style="min-width: 90%;">
        <div class="modal-content" style="min-width: 90%;">
            <!-- Modal Header -->
            <div class="modal-header">
                <h4 class="modal-title">Excel data Preview</h4>
                {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
            </div>
            <p style="margin-left: 20px; color: red;">Note:- If any data will incorrect in any row then those candidate will not be created by the System.</p>

            <!-- Modal body -->
            <form method="post" action="{{ url('/candidates/store/custom') }}" id="excel_form">
            @csrf
                <input type="hidden" name="unique_id"  id="unique_id" >
                <div class="modal-body">
                    <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p> 
                
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive action-data"> 
                                <table class="table table-bordered" id="dummy_data">
                                    {{-- <thead>
                                        <tr>
                                            <th scope='col' style='position: sticky;top:0px;'>Employee ID</th>
                                            <th scope='col' style='position: sticky;top:0px;'>First Name</th>
                                            <th scope='col' style='position: sticky;top:0px;'>Middle Name</th>
                                            <th scope='col' style='position: sticky;top:0px;'>Last Name</th>
                                            <th scope='col' style='position: sticky;top:0px;'>Father's Name</th>
                                            <th scope='col' style='position: sticky;top:0px;'>DOB</th>
                                            <th scope='col' style='position: sticky;top:0px;'>Present Address</th>
                                            <th scope='col' style='position: sticky;top:0px;'>Permanent Address</th>
                                            <th scope='col' style='position: sticky;top:0px;'>Document Type</th>
                                            <th scope='col' style='position: sticky;top:0px;'>Document Number</th>
                                            <th scope='col' style='position: sticky;top:0px;'>Phone</th>
                                            <th scope='col' style='position: sticky;top:0px;'>DOJ</th>
                                        </tr>
                                    </thead> 
                                    <tbody>
                                        <tr>
                                            <td >Employee ID</td>
                                            <td >First Name</td>
                                            <td >Middle Name</td>
                                            <td >Last Name</td>
                                            <td >Father's Name</td>
                                            <td >DOB</td>
                                            <td >Present Address</td>
                                            <td >Permanent Address</td>
                                            <td >Document Type</td>
                                            <td >Document Number</td>
                                            <td >Phone</td>
                                            <td >DOJ</td>
                                        </tr>    
                                        
                                    </tbody>     --}}
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal footer -->
                <div class="modal-footer">
                    
                    <button type="submit" class="btn btn-info mutiple_submit" >Submit </button>
                    <button type="button" class="btn btn-danger mutiple_close" data-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){

        // $('#excel_data_mdl').modal({
        //     backdrop: 'static',
        //     keyboard: false
        //  });

        $(document).on('change','.customer',function(e) {
                e.preventDefault();
                var _this = $(this);
                var customer = _this.val();
                if(customer!='')
                {
                    $('.sla-div').removeClass('d-none');
                }
                else
                {
                    $('.sla-div').addClass('d-none');

                    $('.file-div').addClass('d-none');

                    $('.slaList').prop('selectedIndex',0);

                    $(".SLAResult").html("");
                    
                }
        });

        $(document).on('change','.slaList',function(e) {
            e.preventDefault();
            var _this = $(this);
            var sla_id = _this.val();

            if(sla_id!='')
            {
                $('.file-div').removeClass('d-none');
                $(".SLAResult").html("");

                $.ajax({ 
                    type:"POST",
                    url: "{{ url('/customer/mixSla/serviceItems') }}",
                    data: {"_token": "{{ csrf_token() }}",'sla_id':sla_id},      
                    success: function (response) {
                        if(response.success==true  ) {   
                            $.each(response.data, function (i, item) {
                                
                            if(item.checked_atatus){$(".SLAResult").append("<div class='form-check form-check-inline disabled-link'><input class='form-check-input error-control services_list' type='checkbox' checked name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type='' readonly><label class='form-check-label error-control' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                            }else{
                                $(".SLAResult").append("<div class='form-check form-check-inline disabled-link'><input class='form-check-input error-control services_list' type='checkbox' name='services[]' value='"+item.service_id+"' id='"+item.service_id+"' data-type='' readonly><label class='form-check-label error-control' for='"+item.service_id+"'>"+item.service_name+"</label></div>");
                            }

                            });
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
            }
            else
            {
                $('.file-div').addClass('d-none');

                $(".SLAResult").html("");
            }
        });

        $(document).on('submit', 'form#addCandidateForm', function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error_container').html("");

            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            //$('.submit').attr('disabled',true);
            //$('.close').attr('disabled',true);
            $('.form-control').attr('readonly',true);
            $('.form-control').addClass('disabled-link');
            $('.error-control').addClass('disabled-link');
            if ($('.submit').html() !== loadingText) {
                $('.submit').html(loadingText);
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
                            //$('.submit').attr('disabled',false);
                            //$('.close').attr('disabled',false);
                            $('.form-control').attr('readonly',false);
                            $('.form-control').removeClass('disabled-link');
                            $('.error-control').removeClass('disabled-link');
                            $('.submit').html('Import User Data');
                        },2000);
                        // console.log(response);
                        if(response.success==true  ) {

                            $('#unique_id').val(response.unique_excel_id);
                            $("#dummy_data").html(response.excel);
                            $('#excel_data_mdl').modal({
                                backdrop: 'static',
                                keyboard: false
                            });
                            $('#excel_data_mdl table tr td .exceldata').first().focus();
                        
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
                    error: function (xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                    }
                });
                return false;
        });

        $(document).on('focusout','.exceldata', function() {
            var _this =$(this);
            var current= $(this).text();
            var id = $(this).closest("td").find("input[type=hidden]").val();
            var name =$(this).closest("td").attr("data-value");
    
            //console.log(name);
            $.ajax({
                type: 'POST',
                url: "{{url('/')}}"+'/candidates/validate/custom',
                data:  {"_token": "{{ csrf_token() }}",'id': id,'field_value':current,'field_name':name},
            
                success: function (response) {
                    if (response.fail == false ) {
                       // console.log('pahuch gya');
                        _this.closest("td").find("span").removeClass('text-danger');
                        _this.closest("td").find("span").prop('contenteditable', 'false');
                    }
                    if(response.fail==true && response.error=='required' ) {                              
                        // for (control in response.errors) {   
                            _this.closest("td").find("span").html('Required');
                        // }
                        
                    }
                    //show the form validates error
                    if(response.fail==true && response.error=='unique' || response.fail==true ) {                              
                        // for (control in response.errors) {   
                        //     $('#error-' + control).html(response.errors[control]);
                        // }
                    }
                    
                },
                // error: function(data){
                // console.log(data);
                // } 
            });
            // alert('test');

        });

        $(document).on('submit', 'form#excel_form', function (event) {
            event.preventDefault();
            //clearing the error msg
            $('p.error_container').html("");
            $('.form-control').removeClass('border-danger');
            var form = $(this);
            var data = new FormData($(this)[0]);
            var url = form.attr("action");
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                $('.mutiple_submit').attr('disabled',true);
                $('.mutiple_close').attr('disabled',true);
                $('.form-control').attr('readonly',true);
                $('.form-control').addClass('disabled-link');
                $('.error-control').addClass('disabled-link');
                if ($('.mutiple_submit').html() !== loadingText) {
                    $('.mutiple_submit').html(loadingText);
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
                        $('.mutiple_submit').attr('disabled',false);
                        $('.mutiple_close').attr('disabled',false);
                        $('.form-control').attr('readonly',false);
                        $('.form-control').removeClass('disabled-link');
                        $('.error-control').removeClass('disabled-link');
                        $('.mutiple_submit').html('Submit');
                        $('.mutiple_close').html('Close');
                    },2000);

                    //console.log(response);
                    if(response.fail==false) {          
                        // window.location = "{{ url('/')}}"+"/sla/?created=true";
                        toastr.success('All correct candidates have been created successfully.');
                        window.setTimeout(function(){
                            window.location = "{{ url('/')}}"+"/candidates/";
                        },2000);
                    }
                    //show the form validates error
                    if(response.success==true ) {                              
                        for (control in response.errors) {  
                            $('.'+control).addClass('border-danger'); 
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