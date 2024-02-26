@extends('layouts.guest')
<style type="text/css">
	
</style>
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
    <!-- ============ Body content start ============= -->
    <div class="main-content">				
        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
					<li><a href="{{ url('/guest/home') }}">Dashboard</a></li>
					<li><a href="{{ url('/guest/candidates') }}">Candidate</a></li>
					<li><a href="{{ route('/guest/candidates/verification',['id'=>base64_encode($guest_v->candidate_id)]) }}">Verification</a></li>
					<li>Checkout</li>
                </ul>
            </div>
            <!-- ============Back Button ============= -->
            <div class="col-sm-1 back-arrow">
                <div class="text-right">
                <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
                </div>
            </div>
        </div>
        <?php $guest_v_s=Helper::get_guest_verification_services($guest_v->id) ?>
        @if($guest_v_s!=NULL && count($guest_v_s)>0)
            <form method="post" id="verificationForm" action="{{ url('/guest/candidates/verification/checkout/store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="gv_id" id="gv_id" value="{{base64_encode($guest_v->id)}}">
                <input type="hidden" name="candidate_id" value="{{base64_encode($guest_v->candidate_id)}}">
                <div class="container-fluid">
                    <div class="row pt-3">
                        <div class="col-md-12">
                            <div class="pb-2">
                                <h2 class="">Shopping Bag</h2>
                            </div>
                        </div>
                        <div class="col-md-8" >
                            <h5 class="card-title mb-1" style=""><strong> Candidate Details :</strong></h5> 
                            <div class="row" style="">
                                    <div class="col-md-4">
                                        <p class="mb-1" style="">Name: {{$candidate->name}}</p> 
                                    </div>
                                    <div class="col-md-4">
                                        <p class=" mb-1" style="">Email: {{$candidate->email}}</p> 
                                    </div>
                                    <div class="col-md-4">
                                        <p class=" mb-1" style="">Contact Number: {{$candidate->phone}}</p> 
                                    </div>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>
                                            Checks
                                        </th>
                                        <th>
                                            Price
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($guest_v_s as $guest_item)
                                    <tr class="record">
                                        <td>
                                            <p class="mb-0"><strong> {{Helper::get_service_name($guest_item->service_id)}}</strong><br>
                                                <?php
                                                    $service_data_array=json_decode($guest_item->service_number,true);
                                                ?>
                                                @foreach($service_data_array as $key => $value)
                                                    <?php
                                                        $k_arr=explode('_',$key);
                                                        $keys=implode(' ',$k_arr);
                                                    ?>
                                                    <small>{{ucwords($keys)}} : {{$value}}</small><br>
                                                @endforeach
                                            </p>
                                        </td>
                                        <td>
                                            <h6 class="fw-600"><span fixed-item-price="" id="item-price"><i class="fas fa-rupee-sign"></i> {{$guest_item->price}}</span></h6>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>	
                        </div>
                        <div class="col-md-4">
                            <div class="order-billing">
                                <div class="border-bottom border-dark">
                                    <h5 class="fw-600">Summary</h5>
                                </div>
                                <table class="summary-table" style="">
                                    <tbody>
                                        <tr>
                                        <td>Subtotal</td>
                                        <td><i class="fas fa-rupee-sign"></i> <span id="sub-total">{{$guest_v->sub_total}}</span></td>
                                        <td></td>
                                        </tr>
                                        <tr>
                                        <td>Tax</td>
                                        <td><span>0</span></td>
                                        <td></td>
                                        </tr>
                                        <tr>
                                        @if($guest_v->promo_code_id!=NULL)
                                            <?php $promocode=Helper::get_promo_code($guest_v->promo_code_id);?>
                                            <td>Discount</td>
                                            <td><span id="discount">{{$promocode->discount}} %</span></td>
                                            <td></td>
                                            </tr>
                                            <tr class="promo_result">
                                                <td>Promocode</td>
                                                <td><span class="text-success">{{$promocode->title}}</span></td>
                                                <td><span class="badge badge-success">Applied</span></td>
                                            </tr>						
                                            <tr class="border-top border-dark">
                                                <th>TOTAL</th>
                                                <th><i class="fas fa-rupee-sign"></i><input type="hidden" id="total_price" name="total_price" value="{{$guest_v->total_price}}"> <span id="total">{{$guest_v->total_price}}</span></th>
                                                <th></th>
                                            </tr>
                                        @else
                                            <td>Discount</td>
                                            <td><span id="discount">0</span></td>
                                            <td></td>
                                            </tr>
                                            <tr class="promo_result">
                                                <td>Promo Code</td>
                                                <td>
                                                    <input type="text" class="form-control promocode" id="promocode" name="promocode">
                                                    <p style="margin-bottom: 2px;font-size:10px;" class="text-danger error_container" id="error-promocode"></p>
                                                </td>
                                                <td><button type="button" class="btn btn-sm btn-warning promo">Apply</button></td>
                                            </tr>						
                                            <tr class="border-top border-dark">
                                                <th>TOTAL</th>
                                                <th><i class="fas fa-rupee-sign"></i><input type="hidden" id="total_price" name="total_price" value="{{$guest_v->total_price}}"> <span id="total">{{$guest_v->total_price}}</span></th>
                                                <th></th>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-warning checekout-btn">CHECKOUT</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        @endif
    </div>
</div> 
<script>
    // alert("hi");
    $(document).ready(function(){
        $(document).on('click', '.promo', function (event) {
            $('p.error_container').html("");
            var token=$(this).attr('data-token');
            var data=$('#promocode').val();
            var total_price=$('#total_price').val();
            var guest_id=$('#gv_id').val();
            var loadingText = '<i class="fa fa-circle-o-notch fa-spin"></i> loading...';
            $('.promo').attr('disabled',true);
            if($('.promo').html!=loadingText)
            {
                $('.promo').html(loadingText);
            }
            $.ajax({
                type:'POST',
                url: "{{route('/guest/candidates/verification/promocode')}}",
                data: {"_token": "{{ csrf_token() }}",'promocode':data,'total_price':total_price,'gv_id':guest_id},        
                success: function (response) {        
                console.log(response);
                window.setTimeout(function(){
                        $('.promo').attr('disabled',false);
                        $('.promo').html('Apply');
                    },2000);
                    if(response.success==false ) {                              
                        for (control in response.errors) {  
                            // $('.'+control).addClass('border-danger'); 
                            $('#error-' + control).html(response.errors[control]);
                        }
                    }
                    else if(response.success==true ) {                              
                        var total_price=response.total_price;
                        var discount=response.discount;
                        var title=response.title;

                        $('#discount').html(discount+' %');
                        $('#total_price').val(total_price);
                        $('#total').html(total_price);

                        $('.promo_result').html('<td>Promocode</td><td><span class="text-success">'+title+'</span></td><td><span class="badge badge-success">Applied</span></td>');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    alert("Error: " + errorThrown);
                }
            });
        });
    });
        
</script>   
@endsection