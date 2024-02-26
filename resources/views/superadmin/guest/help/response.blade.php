@extends('layouts.superadmin')
@section('content')
<style>
    .disabled-link
    {
        pointer-events: none;
    }
</style>
<div class="main-content-wrap sidenav-open d-flex flex-column">
   <!-- ============ Body content start ============= -->
   <div class="main-content">
      <div class="row">
      <div class="col-md-12">

        <div class="row">
            <div class="col-sm-11">
                <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/app/home') }}">Dashboard</a>
                </li>
                <li>
                   <a href="{{url('/app/guest/help')}}"> Guest Help & Support</a>
                </li>
                <li>
                    Response
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
         @if ($message = Session::get('success'))
            <div class="alert alert-success">
            <strong>{{ $message }}</strong> 
            </div>
         @endif
         <div class="card text-left">
            <div class="card-body">
                @include('superadmin.guest.menu')
               <div class="row">
                  @if ($message = Session::get('success'))
                    <div class="col-md-12">   
                        <div class="alert alert-success">
                            <strong>{{ $message }}</strong> 
                        </div>
                    </div>
                  @endif
                  <div class="col-md-12">
                    <form action="{{url('/app/guest/help/update',['id'=>base64_encode($help->id)])}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-body">
                            <div class="card radius shadow-sm">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4 class="card-title mb-1 mt-3">Guest Help & Support  </h4>
                                        </div>
                                        <hr>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="question" class="form-control-label font-weight-300">Subject <span class="text-danger">*</span></label>
                                                <div class="form-control question " style="height: auto;">{{ $help->subject }}  </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="help" class="form-control-label font-weight-300">Help Content <span class="text-danger">*</span></label>
                                                <textarea id="help" class="form-control help" name="help" placeholder="e.g. Help Content">@if($helps){{$helps->response_content}}@endif</textarea>
                                                {{-- <input type="text" id="answer" class="form-control answer  @error('answer') is-invalid @enderror" name="answer" placeholder="e.g. Answer"> --}}
                                                @error('help')
                                                    <div class="text-danger">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <div class="text-right">
                                        <button class="btn btn-primary" type="submit">Submit</button>
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
@stack('scripts')
<script type="text/javascript">
   //
$(document).ready(function() {
    $('#help').summernote({
        minHeight: 400, 
    });
});
    
   
                     
</script>
@endsection
