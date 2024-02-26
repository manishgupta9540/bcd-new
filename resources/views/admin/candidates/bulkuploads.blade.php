@extends('layouts.admin')
<style>
    .disabled-link {
        pointer-events: none;
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
                        </div>
                        <br>
                        <div class="col-md-8 offset-md-2">
                            <form class="mt-2" method="post" id="addCandidateForm" action="{{ url('/candidates/jaf/uploads') }}" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    @if ($message = Session::get('error'))
                                        <div class="col-md-12">
                                            <div class="alert alert-danger">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="col-md-10">
                                        <h4 class="card-title mb-1" style="border-bottom:1px solid #ddd;">Add a new
                                            candidate </h4>
                                        <p class="mt-1"> Fill the required details </p>
                                    </div>

                                    <div class="col-md-10">
                                        <!-- select a customer  -->
                                        <div class="form-group">
                                            <label for="service">Select a Customer <span class="text-danger">*</span></label>
                                            <select class="form-control customer" name="customer" id="customer">
                                                <option value="">-Select-</option>
                                                @if (count($customers) > 0)
                                                    @foreach ($customers as $item)
                                                        <option value="{{ $item->id }}">
                                                            {{ ucfirst($item->company_name) . ' - ' . $item->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-customer"></p>
                                        </div>

                                        {{-- <div class="sla_row">

                                        </div> --}}

                                        <div class="sla_type_result">

                                        </div>
                                        
                                        <div class="file-div d-none">
                                            <div class="form-group">
                                                <label for="service">Select a file</label>
                                                <input class="form-control file" type="file" id="csv_file" name="excelFile" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-excelFile"></p>
                                            </div>
                                            <div><span class="text-danger">Note * : </span> Click to download Excel format for bulk upload Candidates/Cases.
                                                <a href="" class="urldata"><i class="far fa-hand-point-right"></i> Excel Template<i class="far fa-hand-point-left"></i></a>
                                                <a href="" class="csvdata"><i class="far fa-hand-point-right"></i> CSV Template<i class="far fa-hand-point-left"></i></a>
                                            </div>
                                            <button class="btn btn-info"  type="submit">Import User Data</button>
                                        </div>

                                            {{-- <div class="form-group mt-2">
                                                <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-user"></p>
                                                <button type="submit" class="btn btn-info submit">Submit</button>
                                            </div> --}}
                                        </div>
                                    </div>
                            </form>
                        </div>
                    </div>

                </div><!-- Footer Start -->
                <div class="flex-grow-1"></div>
            </div>
            {{-- Modal for excel verification    --}}

            <div class="modal" id="excel_data">
                <div class="modal-dialog" style="max-width: 96%;">
                    <div class="modal-content" style=" max-width: 80%;">
                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Excel data Preview</h4>
                            {{-- <button type="button" class="close" data-dismiss="modal">&times;</button> --}}
                        </div>
                        <p style="margin-left: 20px;">Note:- If any data will incorrect in any row then those candidate
                            will not be created by the System.</p>

                        <!-- Modal body -->
                        <form method="post" action="{{ url('/candidates/multiple/jafuploads') }}" id="excel_form">
                            @csrf
                            <input type="hidden" name="unique_id" id="unique_id">
                            <input type="hidden" name="customer_id" id="customer_id">
                            <input type="hidden" name="sla_id" id="sla_id">
                            <input type="hidden" name="service_id" id="service_id">
                           
                            <div class="modal-body ">
                                <p style="margin-bottom: 2px;" class="text-danger error-container" id="error-all"> </p>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="table-responsive">
                                            {{-- @if ($VIEW_CUSTOMER_ACCESS) --}}
                                            <table class="table table-bordered">
                                                <thead >
                                                    <tr class="dynamic_label_name">
                                                        {{-- <th scope="col">#</th> --}}
                                                        <th scope="col">Client emp code</th>
                                                        <th scope="col">Entity code </th>
                                                        <th scope="col">First Name</th>
                                                        <th scope="col">Middle Name</th>
                                                        <th scope="col">Last Name</th>
                                                        <th scope="col">Father Name</th>
                                                        <th scope="col">Aadhar Number</th>
                                                        <th scope="col">DOB</th>
                                                        <th scope="col">Gender</th>
                                                        <th scope="col">Email</th>
                                                        <th scope="col">Phone</th>
                                                        <th scope="col">Frn Id</th>
                                                        <th scope="col">Recruiter</th>
                                                        <th scope="col">Source</th>
                                                        <th scope="col">Cost Code</th>
                                                        <th scope="col">Grade</th>
                                                        {{-- <th scope="col">BGV Filling Access</th> --}}
                                                        {{-- <th scope="col" class="dynamic_label_name"></th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody id="dummy_data">
                                                    {{-- @foreach ($excel_dummy as $dummy)
                                                        <tr>
                                                            <td>{{$dummy->client_emp_code}}</td>
                                                            <td>{{$dummy->entity_code}}</td>
                                                            <td>{{$dummy->first_name}}</td>
                                                            <td>{{$dummy->middle_name}}</td>
                                                            <td>{{$dummy->last_name}}</td>
                                                            <td>{{$dummy->father_name}}</td>
                                                            <td>{{$dummy->aadhar_number}}</td>
                                                            <td>{{$dummy->dob}}</td>
                                                            <td>{{$dummy->gender}}</td>
                                                            <td>{{$dummy->phone}}</td>
                                                            <td>{{$dummy->email}}</td>
                                                            <td>{{$dummy->jaf_filling_access}}</td>
                                                        </tr>
                                                    @endforeach --}}
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal footer -->
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-info mutiple_submit">Submit </button>
                                <button type="button" class="btn btn-danger mutiple_close" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>

        <script>
            $(function() {
                $('.switch').on('change.bootstrapSwitch', function(e) {
                    console.log(e.target.checked);
                });

                $('.customer').prop('selectedIndex', 0);

                //customer select value 
                $(document).on('change', '.customer', function() {

                    var type = $(this).val();
                    
                    var cust_id = $(this).attr('data-id');
                    var customer = $('.customer option:selected').val();
                    $('.sla_type_result').html("");
                    // alert(type);
                    if (customer != '') {
                        $('.sla_type_result').html('<div class="form-group"> <label for="service">Select a SLA <span class="text-danger">*</span></label> <select class="form-control slaList " name="sla" id="sla"> <option value="">-Select-</option> </select> <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-sla"></p> </div> <div class="form-group SLAResult" > </div> <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-services"></p>');
                        $('.slaList').empty();
                        $('.slaList').append("<option value=''>-Select-</option>");
                        $(".SLAResult").html("");

                        $.ajax({
                            type: "POST",
                            url: "{{ url('/customers/sla/getlist') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                'customer_id': customer
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success == true) {
                                    $.each(response.data, function(i, item) {
                                        $(".slaList").append("<option value='" + item.id + "'>" + item.title + "</option>");
                                    });
                                }
                                //show the form validates error
                                if (response.success == false) {
                                    for (control in response.errors) {
                                        $('#error-' + control).html(response.errors[control]);
                                    }
                                }
                            },
                            error: function(xhr, textStatus, errorThrown) {
                                // alert("Error: " + errorThrown);
                            }
                        });
                        // return false;
                    }


                });

                //on select sla item
                $(document).on('change', '.slaList', function(e) {
                    e.preventDefault();
                    //$(".SLAResult").html("");
                    var sla_id = $('.slaList option:selected').val();

                    if(sla_id !='')
                    {
                        $('.file-div').removeClass('d-none');
                        $(".SLAResult").html("");
                        // alert(sla_id);
                        $.ajax({
                            type: "POST",
                            url: "{{ url('/customer/mixSla/serviceItems') }}",
                            data: {
                                "_token": "{{ csrf_token() }}",
                                'sla_id': sla_id
                            },
                            success: function(response) {
                                console.log(response);
                                if (response.success == true) {
                                    $.each(response.data, function(i, item) {

                                        if (item.checked_atatus) {
                                            $(".SLAResult").append(
                                                "<div class='form-check form-check-inline disabled-link'><input class='form-check-input error-control services_list' type='checkbox' checked name='services[]' value='" +
                                                item.service_id + "' id='" + item
                                                .service_id +
                                                "' data-type='' readonly><label class='form-check-label error-control' for='" +
                                                item.service_id + "'>" + item.service_name +
                                                "</label></div>");
                                        } else {
                                            $(".SLAResult").append(
                                                "<div class='form-check form-check-inline disabled-link'><input class='form-check-input error-control services_list' type='checkbox' name='services[]' value='" +
                                                item.service_id + "' id='" + item
                                                .service_id +
                                                "' data-type='' readonly><label class='form-check-label error-control' for='" +
                                                item.service_id + "'>" + item.service_name +
                                                "</label></div>");
                                        }

                                    });
                                }
                                //show the form validates error
                                if (response.success == false) {
                                    for (control in response.errors) {
                                        $('#error-' + control).html(response.errors[control]);
                                    }
                                }
                            },
                            error: function(xhr, textStatus, errorThrown) {
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


                 //excel file download
                $(document).on('change','.slaList',function(e){
                
                    var id = $('.slaList option:selected').val();
                    $.ajax({
                        type:'get',
                        url: "{{ url('sla-export-data') }}/"+id,
                        success: function (data) {
                            console.log(data);
                            if(data.success == true){
                                //window.open(response.url);
                                var urlvalue = data.url;
                                //alert(urlvalue);
                                $('.urldata').attr('href',urlvalue);
                            }
                            else{
                                $('#loading').html(response.error);
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            // alert("Error: " + errorThrown);
                        }
                    });
                });

                //csv file download 
                $(document).on('change','.slaList',function(e){
                
                    var id = $('.slaList option:selected').val();
                    $.ajax({
                        type:'get',
                        url: "{{ url('csv-export-data') }}/"+id,
                        success: function (data) {
                            console.log(data);
                            if(data.success == true){
                                //window.open(response.url);
                                var urlvalue = data.url;
                                //alert(urlvalue);
                                $('.csvdata').attr('href',urlvalue);
                            }
                            else{
                                $('#loading').html(response.error);
                            }
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            // alert("Error: " + errorThrown);
                        }
                    });
                });

                $(document).on('change', '.variable_services_list', function() {

                    var total_price = 0;

                    var total_check = 0;

                    if (this.checked) {
                        var id = $(this).attr("value");
                        var text = $(this).attr("data-string");
                        var verify = $(this).attr("data-verify");
                        var tat = 1;

                        var readonly = '';

                        var display_none = '';

                        var price_type = $('.price_type:checked').val();

                        if (price_type.toLowerCase() == 'package'.toLowerCase()) {
                            readonly = 'readonly';

                            display_none = 'd-none';
                        }

                        if (text.toLowerCase() == 'Address'.toLowerCase()) {
                            tat = 7;
                        } else if (text.toLowerCase() == 'Employment'.toLowerCase()) {
                            tat = 5;
                        } else if (text.toLowerCase() == 'Educational'.toLowerCase()) {
                            tat = 7;
                        } else if (text.toLowerCase() == 'Criminal'.toLowerCase()) {
                            tat = 3;
                        } else if (text.toLowerCase() == 'Judicial'.toLowerCase()) {
                            tat = 2;
                        } else if (text.toLowerCase() == 'Reference'.toLowerCase()) {
                            tat = 2;
                        } else if (text.toLowerCase() == 'Covid-19 Certificate'.toLowerCase()) {
                            tat = 5;
                        }

                        if (verify.toLowerCase() == "Auto".toLowerCase())
                            $(".service_result").append("<p class='pb-border row-" + id +
                                "'></p><div class='row row-" + id + " mt-2' id='row-" + id +
                                "'><div class='col-sm-2'><label>" + text +
                                "</label></div><div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-" +
                                id +
                                "' value='1' readonly><p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-" +
                                id +
                                "'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='tat-" +
                                id + "' value='" + tat +
                                "' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-" +
                                id +
                                "'></p></div><div class='col-sm-2'><label>Incentive TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='incentive-" +
                                id +
                                "' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-" +
                                id + "'></p></div></div><div class='row mt-2 row-" + id + "' id='row-" + id +
                                "'><div class='col-sm-2'></div><div class='col-sm-3 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='penalty-" +
                                id + "' value='" + tat +
                                "'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-" +
                                id + "'></p></div><div class='col-sm-2 price_row " + display_none +
                                " pt-2'><label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label></div><div class='col-sm-3 price_row'><input class='form-control check_price' type='text' name='price-" +
                                id + "' value='0' " + readonly +
                                "><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-" +
                                id + "'></p></div></div>");
                        else
                            $(".service_result").append("<p class='pb-border row-" + id +
                                "'></p><div class='row row-" + id + " mt-2' id='row-" + id +
                                "'><div class='col-sm-2'><label>" + text +
                                "</label></div><div class='col-sm-2'><input class='form-control no_of_check' type='text' name='service_unit-" +
                                id +
                                "' value='1' ><p style='margin-top:2px; margin-bottom: 2px;' class='text-danger error_container' id='error-service_unit-" +
                                id +
                                "'></p></div><div class='col-sm-1'><label>TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='tat-" +
                                id + "' value='" + tat +
                                "' placeholder='TAT' ><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-tat-" +
                                id +
                                "'></p></div><div class='col-sm-2'><label>Incentive TAT</label></div><div class='col-sm-3'><input class='form-control' type='text' name='incentive-" +
                                id +
                                "' value='1'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-incentive-" +
                                id + "'></p></div></div><div class='row mt-2 row-" + id + "' id='row-" + id +
                                "'><div class='col-sm-2'></div><div class='col-sm-3 pt-2 text-right'><label>Penalty TAT</label></div><div class='col-sm-2'><input class='form-control' type='text' name='penalty-" +
                                id + "' value='" + tat +
                                "'><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-penalty-" +
                                id + "'></p></div><div class='col-sm-2 price_row " + display_none +
                                " pt-2'><label>Price (<small class='text-muted'>Per Item <i class='fas fa-rupee-sign'></i></small>)</label></div><div class='col-sm-3 price_row'><input class='form-control check_price' type='text' name='price-" +
                                id + "' value='0' " + readonly +
                                "><p style='margin-bottom: 2px;' class='text-danger error_container' id='error-price-" +
                                id + "'></p></div></div>");
                    } else {
                        var id = $(this).attr("value");
                        $("div#row-" + id).remove();
                        $("p.row-" + id).remove();
                    }

                    $('.check_price').each(function() {
                        if (!isNaN(parseFloat($(this).val()))) {
                            total_price = total_price + parseFloat($(this).val());
                        }
                    });

                    $('.total_check_price').html(total_price.toFixed(2));

                    $('.no_of_check').each(function() {
                        var is_int = Number.isInteger(parseInt($(this).val()));
                        if (is_int) {
                            total_check = total_check + parseInt($(this).val());
                        }
                    });

                    $('.total_checks').html(total_check);

                });

                $(document).on('change', '.price_type', function() {

                    if (this.checked) {
                        $('.price_result').html('');
                        $('.price_result').removeClass('mb-2');
                        $('.price_result').removeAttr('style');

                        var price_type = $('.price_type:checked').val();

                        if (price_type.toLowerCase() == 'package'.toLowerCase()) {
                            $('.price_result').addClass('mb-2');
                            $('.price_result').css({
                                'border': '1px solid #ddd',
                                'padding': '10px',
                                'width': '50%'
                            });
                            $('.price_result').html(`<div class="row">
                                      <div class="col-sm-12 mt-1 mb-2" style="color:#dd2e2e">Package-Wise Price</div>
                                      <div class="col-sm-6">
                                         <div class="form-group">
                                            <label>Price <span class="text-danger">*</span> (<small class="text-muted"><i class="fas fa-rupee-sign"></i></small>)</label>
                                            <input class="form-control" type="text" name="price" value="0">
                                            <p style="margin-bottom: 2px;" class="text-danger error_container" id="error-price"></p>
                                         </div>
                                      </div> 
                                   </div>`);

                            $('.check_price').attr('readonly', true);

                            $('.price_row').addClass('d-none');

                            $('.total_p').addClass('d-none');

                        } else {
                            $('.check_price').attr('readonly', false);

                            $('.price_row').removeClass('d-none');

                            $('.total_p').removeClass('d-none');
                        }

                    } else {
                        alert('Select One price type');
                    }
                });

                $(document).on('change keyup', '.check_price', function() {

                    var total_price = 0;

                    $('.check_price').each(function() {
                        if (!isNaN(parseFloat($(this).val()))) {
                            total_price = total_price + parseFloat($(this).val());
                        }
                    });

                    $('.total_check_price').html(total_price.toFixed(2));
                });

                $(document).on('change keyup', '.no_of_check', function() {

                    var total_check = 0;
                    $('.no_of_check').each(function() {
                        var is_int = Number.isInteger(parseInt($(this).val()));
                        if (is_int) {
                            total_check = total_check + parseInt($(this).val());
                        }
                    });

                    $('.total_checks').html(total_check);
                });


            });
        </script>

        <script>
            $(function() {
                $(document).on('submit', 'form#addCandidateForm', function(event) {
                    event.preventDefault();
                    //clearing the error msg

                    var custId = $('.customer option:selected').val();
                    var slaId = $('.slaList option:selected').val();
                    var types = [];
                        $("input[name='services[]']:checked").each(function() {
                            types.push($(this).val());
                        });
                    $('#customer_id').val(custId);
                    $('#sla_id').val(slaId);
                    $('#service_id').val(types);

                    $('p.error_container').html("");

                    var form = $(this);
                    var data = new FormData($(this)[0]);
                    var url = form.attr("action");
                    var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                    $('.submit').attr('disabled', true);
                    $('.close').attr('disabled', true);
                    $('.form-control').attr('readonly', true);
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
                        success: function(response) {
                            window.setTimeout(function() {
                                $('.submit').attr('disabled', false);
                                $('.close').attr('disabled', false);
                                $('.form-control').attr('readonly', false);
                                $('.form-control').removeClass('disabled-link');
                                $('.error-control').removeClass('disabled-link');
                                $('.submit').html('Import User Data');
                            }, 2000);
                            //console.log(response);
                            if(response.success==false  ) {
                                $("#dummy_data").html('');
                                $('#unique_id').val(response.unique_excel_id);
                                $("#dummy_data").append(response.excel);
                                $('.dynamic_label_name').append(response.label_name); 
                            
                                $('#excel_data').modal({
                                    backdrop: 'static',
                                    keyboard: false
                                });
                                $('#excel_data table tr td .exceldata').first().focus();

                            }
                            // if (response.success == true) {
                            //     toastr.success("Candidate has been created successfully");
                            //     // redirect to google after 5 seconds
                            //     window.setTimeout(function() {
                            //         window.location = "{{ url('/') }}" +
                            //             "/candidates/";
                            //     }, 2000);

                            // }
                            //show the form validates error
                            if (response.success == false) {
                                var i = 0;
                                for (control in response.errors) {
                                    $('#error-' + control).html(response.errors[control]);
                                    if (i == 0) {
                                        $('select[name=' + control + ']').focus();
                                        $('input[name=' + control + ']').focus();
                                        $('textarea[name=' + control + ']').focus();
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

                // event.stopImmediatePropagation();
                // return false;
            });


            $(document).on('change', '.form_type', function() {

                var form_value = $('.form_type option:selected').val();
                if (form_value == 'single') {
                    $(".single").removeClass('d-none');
                    $(".multiple").hide();
                    $(".single").show();

                } else if (form_value == 'multiple') {
                    $(".multiple").removeClass('d-none');
                    $(".single").hide();
                    $(".multiple").show();
                } else {
                    $(".single").removeClass('d-none');
                    $(".multiple").hide();
                    $(".single").hide();
                    $(".single").removeClass('d-none');
                }
                // alert(form_value);
            });
        </script>

        <script>
            
            $(document).on('submit', 'form#excel_form', function(event) {
                event.preventDefault();
                //clearing the error msg
                $('p.error_container').html("");
                $('.form-control').removeClass('border-danger');
                var form = $(this);
                var data = new FormData($(this)[0]);
                var url = form.attr("action");
                var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
                $('.mutiple_submit').attr('disabled', true);
                $('.mutiple_close').attr('disabled', true);
                $('.form-control').attr('readonly', true);
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
                    success: function(response) {

                        window.setTimeout(function() {
                            $('.mutiple_submit').attr('disabled', false);
                            $('.mutiple_close').attr('disabled', false);
                            $('.form-control').attr('readonly', false);
                            $('.form-control').removeClass('disabled-link');
                            $('.error-control').removeClass('disabled-link');
                            $('.mutiple_submit').html('Submit');
                            $('.mutiple_close').html('Close');
                        }, 2000);

                        console.log(response);
                        if (response.fail == false) {
                            // window.location = "{{ url('/') }}"+"/sla/?created=true";
                            toastr.success('All candidates have been created successfully.');
                            window.setTimeout(function() {
                                window.location = "{{ url('/') }}" + "/candidates/";
                            }, 2000);
                        }
                        //show the form validates error
                        if (response.success == true) {
                            for (control in response.errors) {
                                $('.' + control).addClass('border-danger');
                                $('#error-' + control).html(response.errors[control]);
                            }
                        }
                    },
                    error: function(xhr, textStatus, errorThrown) {
                        // alert("Error: " + errorThrown);
                    }
                });
                return false;
            });

            $(document).on('change', '.jaf', function(event) {

                var jaf_value = $('.jaf option:selected').val();

                if (jaf_value == 'candidate') {
                    // $(".single").removeClass('d-none');
                    $(".jaf_file").hide();
                    // $(".single").show();

                    $('.jaf_note').removeClass('d-none');

                }
                // else if(jaf_value == 'customer')
                // {
                //     // $("#assign_to").prop("checked", true);
                //     $(".jaf_file").show();
                // }
                else {
                    // $(".multiple").removeClass('d-none');
                    // $(".single").hide();
                    $(".jaf_file").show();
                    $('.jaf_note').addClass('d-none');
                }

                if (jaf_value != 'customer') {
                    $("#assign_to").prop("checked", false);
                }

            });

            $(document).on('click', '.assign_to', function(event) {

                var assign_to = $("input[name='assign_to']:checked").val();
                //    alert(assign_to);
                if (assign_to == 'on') {
                    // $(".single").removeClass('d-none');
                    // $('#jaf_reset').val('');
                    // $(".jaf_div").hide();

                    $("#jaf_reset").val("customer").change();
                    $('.jaf_req').addClass('d-none');
                    //    window.reset('.jaf_div');
                    // $(".single").show();

                } else {
                    // $(".multiple").removeClass('d-none');     
                    // $(".single").hide();
                    // $('#jaf_reset').val('');

                    $('.jaf_req').removeClass('d-none');

                    // $(".jaf_div").show();

                }

            });

            $(document).on('change', '#jaf_reset', function(event) {
                var _this = $(this);

                $('.lbl_email').html('Email');

                if (_this.val().toLowerCase() == 'candidate') {
                    $('.lbl_email').html('Email <span class="text-danger">*</span>');
                }
            });
        </script>

    @endsection
