@extends('layouts.admin')

@section('content')
<style type="text/css">
    ul,li
    {
      list-style-type: none;
    }
    </style>
<div class="main-content-wrap sidenav-open d-flex flex-column"> 
    <div class="main-content">         
      <div class="row pb-3">
        <div class="col-sm-11">
            <ul class="breadcrumb">
            <li>
            <a href="{{ url('/home') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{ url('/check/control') }}">Check Control</a>
            </li>
            <li>
              {{-- @if ($role_data == null)
                Permission  
              @else
                {{$role_data->role}}
              @endif --}}
            </li>
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
         
    <div class="col-md-3 content-container">
     <!-- left-sidebar -->
     @include('admin.accounts.left-sidebar') 
     <!-- start right sec -->
     </div> 
     <div class="col-md-9 content-wrapper">
      <div class="formCover">
          <!-- section -->
          
          <div class="col-sm-12 ">
                  <!-- row -->
              <div class="row">
                  <div class="col-md-12">
                    <h4 class="card-title mb-1 mt-3">Check Input Required Setting  </h4>
                    <p class="pb-border"> </p>
                  </div>
                                                              
                    {{-- @if ($message = Session::get('success'))
                            <div class="col-md-12">   
                                <div class="alert alert-success">
                                  <strong>{{ $message }}</strong> 
                                </div>
                            </div>
                    @endif --}}
                <div class="col-md-12">     
                    <form action="{{url('/save/check/input')}}"  method="post">
                      @csrf 
                      <input type="hidden" name="id" value="{{base64_encode($id)}}">
                      <div class="form-row">
                          <div class="form-group col-md-12">
                            {{-- <div class="row">
                                <div class="col-md-6">
                                  <label><h4>Role Name :- {{$role_data->role}}</h4></label>
                                </div>
                            </div> --}}
                              @if(count($services)>0)
                                <div class="row">
                                  @foreach($services as $key => $data)
                                    @php
                                      $result = count($services) - 2;
                                      $t = $key + 1;
                                    @endphp
                                    <div class="col-6">
                                        @php
                                            $form_items= Helper::get_check_item_inputs1($data->id); 
                                            $check_control = helper::get_check_control($data->id,$id);
                                            if($check_control){
                                                  $checked = 'checked';
                                                }else{
                                                  $checked =  '';
                                                }
                                        @endphp
                                        <li>
                                          <input type="checkbox" name="services[]" id="{{$data->id}}" value="{{$data->id}}" {{$checked}}> <label for="{{ $data->id }}"> {{$data->name}} </label>
                                          <ul>
                                            @if(count($form_items)>0)
                                              @foreach($form_items as $form_item)
                                                @php
                                                $check_input= Helper::check_item_input($form_item->id,$id); 
                                                  if($check_input){
                                                    $checked = 'checked';
                                                  }
                                                  // else if(stripos($data->type_name,'reference')!==false && stripos($form_item->label_name,'Reference Type (Personal / Professional)')!==false)
                                                  // {
                                                  //   $checked = 'checked';
                                                  // }
                                                  else{
                                                    $checked =  '';
                                                  }
                                                @endphp
                                                <li>
                                                  @if(stripos($data->type_name,'drug_test_5')!==false || stripos($data->type_name,'drug_test_6')!==false || stripos($data->type_name,'drug_test_7')!==false || stripos($data->type_name,'drug_test_8')!==false || stripos($data->type_name,'drug_test_9')!==false || stripos($data->type_name,'drug_test_10')!==false)
                                                    @if(!(stripos($form_item->label_name,'Test Name')!==false))
                                                      <input type="checkbox"  name="check[]" id="{{$form_item->id}}" value="{{$form_item->id}}" {{$checked}}> <label for="{{$form_item->id}}">{{$form_item->label_name}}</label>
                                                    @endif
                                                  @else
                                                    <input type="checkbox"  name="check[]" id="{{$form_item->id}}" value="{{$form_item->id}}" {{$checked}}> <label for="{{$form_item->id}}">{{$form_item->label_name}}</label>
                                                  @endif
                                                </li>
                                              @endforeach

                                              {{-- List of Reference type Inputs --}}
                                              @if($data->id==17)
                                                @php
                                                  $personal_inputs = Helper::referenceServiceFormInputs1($data->id,'personal');
                                                  $professional_inputs = Helper::referenceServiceFormInputs1($data->id,'professional');
                                                @endphp
                                                
                                                  @if(count($personal_inputs)>0)
                                                    @foreach ($personal_inputs as $form_item)
                                                      @php
                                                        $check_input= Helper::check_item_input($form_item->id,$id); 
                                                        if($check_input){
                                                          $checked = 'checked';
                                                        }else{
                                                          $checked =  '';
                                                        }
                                                      @endphp 
                                                      <li>
                                                        <input type="checkbox"  name="check[]" id="{{$form_item->id}}" value="{{$form_item->id}}" {{$checked}}> <label for="{{$form_item->id}}">{{$form_item->label_name}} (Personal)</label>
                                                      </li>
                                                    @endforeach
                                                  @endif
                                                  @if(count($professional_inputs)>0)
                                                    @foreach ($professional_inputs as $form_item)
                                                      @php
                                                        $check_input= Helper::check_item_input($form_item->id,$id); 
                                                        if($check_input){
                                                          $checked = 'checked';
                                                        }else{
                                                          $checked =  '';
                                                        }
                                                      @endphp 
                                                      <li>
                                                        <input type="checkbox"  name="check[]" id="{{$form_item->id}}" value="{{$form_item->id}}" {{$checked}}> <label for="{{$form_item->id}}">{{$form_item->label_name}} (Professional)</label>
                                                      </li>
                                                    @endforeach
                                                  @endif
                                              @endif
                                            @endif
                                          </ul>
                                        </li>
                                    </div>
                                    @if ($t%2==0 && $t<=$result)
                                      <div class="col-12">
                                        <hr>
                                      </div>
                                    @endif
                                  @endforeach
                                </div>
                              @endif
                          </div>
                      </div>
                      {{-- <input type="hidden" name="role_id" value="{{$role_id}}">
                      <input type="hidden" name="business_id" value="{{$business_id}}"> --}}
                      <button type="submit" class="btn btn-info">Submit</button>
                      <a href="" class="btn  btn-danger" ><i class="metismenu-icon"></i>Cancel</a>

                                    </div>
                                    <div class="text-center">
                                        <div class="error"></div>
                                    </div>
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
      </div>
</div>
{{-- </div> --}}
<script type="text/javascript">


    $('input[type=checkbox]').click(function () {
      $(this).parent().find('li input[type=checkbox]').prop('checked', $(this).is(':checked'));
      var sibs = false;
      $(this).closest('ul').children('li').each(function () {
      if($('input[type=checkbox]', this).is(':checked')) sibs=true;
      })
      $(this).parents('ul').prev().prop('checked', sibs);
    });

      </script>
@endsection


