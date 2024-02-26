@extends('layouts.admin')
@section('content')

<div class="main-content-wrap sidenav-open d-flex flex-column"> 
<div class="main-content">         
    <div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
            <li>
            <a href="{{ url('/home') }}">Dashboard</a>
            </li>
            <li>
                Help & Support
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
           </div>
              <!-- start right sec -->
              <div class="col-md-9 content-wrapper" style="background:#fff">
                 <div class="formCover py-2" style="height: 100vh;">
                    <!-- section -->
                    <section>
                        <form action="{{url('/help/update',['id'=>base64_encode($help->id)])}}"  method="POST" enctype="multipart/form-data">
                            @csrf
                                <div class="form-body">
                                    <div class="card radius shadow-sm">
                                        <div class="card-body">
                                            @if ($message = Session::get('success'))
                                                <div class="col-md-12">   
                                                    <div class="alert alert-success">
                                                        <strong>{{ $message }}</strong> 
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h4 class="card-title mb-1 mt-3">Help & Support  </h4>
                                                    {{-- <p class="pb-border"> Your billing overview/history  </p> --}}
                                                </div>
                                                <hr>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="question" class="form-control-label font-weight-300">Subject <span class="text-danger">*</span></label>
                                                        {{-- <textarea id="question" class="form-control question " name="question" placeholder="e.g. Question" readonly>{!! $help->content !!}</textarea> --}}
                                                        <div  class="form-control question " style="height: auto;">{{ $help->subject }}  </div>
                                                        {{-- <input type="text" id="answer" class="form-control answer  @error('answer') is-invalid @enderror" name="answer" placeholder="e.g. Answer"> --}}
                                                        
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="help" class="form-control-label font-weight-300">Help Content <span class="text-danger">*</span></label>
                                                        <textarea id="help" class="form-control help " name="help" placeholder="e.g. Help Content"  >@if ($helps)
                                                            
                                                        {{ $helps->response_content }}@endif</textarea>
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
                                                <button class="btn btn-success" type="submit">Submit</button>
                                            </div>
                                            <div class="text-center">
                                                <div class="error"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                        </form>
                    </section>
                 </div>
              </div>
             </div>
        </div>
    </div>


<script>
$(document).ready(function() {
    $('#help').summernote({
        minHeight: 400, 
    }
       
    );
  });
</script>
@endsection
