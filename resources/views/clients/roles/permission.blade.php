@extends('layouts.client')

@section('content')
<style type="text/css">
    ul,li
    {
      list-style-type: none;
    }
    </style>
<div class="main-content-wrap sidenav-open d-flex flex-column"> 
<div class="main-content"> 
   <!-- ============Breadcrumb ============= -->
 <div class="row">
  <div class="col-sm-11">
      <ul class="breadcrumb">
      <li>
      <a href="{{ url('/my/home') }}">Dashboard</a>
      </li>
      <li>
        <a href="{{ url('/my/roles') }}">Roles</a> </li>
      <li> @if ($role_data == null)
        Permission  
      @else
        {{$role_data->role}}
      @endif
    </li>
      </ul>
  </div>
  <!-- ============Back Button ============= -->
  <div class="col-sm-1 back-arrow">
      <div class="text-right">
        <a href="{{ url()->previous() }}"> <i class="fas fa-arrow-circle-left fa-2x"></i></a>
      </div>
  </div>
</div>        

    <form action="{{url('my/roles/permission/update')}}"  method="post" >
        @csrf
         
         
        <div class="form-row">
            <div class="form-group col-md-12">
              <div class="row">
                  <div class="col-md-12" style="margin-bottom:10px;">
                    <label ><h4>Role Name :- {{$role_data->role}}</h4></label>
                  </div>
              </div>
                 @if(count($permission)>0)
                 @foreach($permission as $data)
                 <?php
                    $action = DB::table('action_masters')->where(['route_group'=>'/my','status'=>'1','parent_id'=>$data->id])->get();
                    ?>
                @php
                if($action_route_count==0){
                              $checked = '';
                            }else{
                $route_link = json_decode($action_route->permission_id);
                $checked = in_array($data->id,$route_link)  ? 'checked' : '';
                 }
                @endphp
                    <li>
                    <input type="checkbox" name="permissions[]"  style="margin-bottom: 10px;" value="{{$data->id}}" {{$checked}}> {{$data->action_title}}
                    <ul>
                      @if(count($action)>0)
                        @foreach($action as $premission)
                          @php
                            if($action_route_count==0){
                              $checked = '';
                            }else{
                             $route_link = json_decode($action_route->permission_id);
                             $checked = in_array($premission->id,$route_link)  ? 'checked' : '';
                            }
                          @endphp
                          <li>
                            <input type="checkbox"  name="permissions[]" style="margin-top: 5px;" value="{{$premission->id}}" {{$checked}}> {{$premission->action_title}}
                          </li>
                        @endforeach
                      @endif
                    </ul>
                  </li><hr>
                 @endforeach
                 @endif

        
            </div>
        </div>
        <input type="hidden" name="role_id" value="{{$role_id}}">
        <input type="hidden" name="business_id" value="{{$business_id}}">
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
