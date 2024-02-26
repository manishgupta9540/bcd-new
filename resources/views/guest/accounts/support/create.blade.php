@extends('layouts.guest')
@section('content')

{{-- <div class="main-content-wrap sidenav-open d-flex flex-column"> 
<div class="main-content">         
    <div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
                <li>
                    <a href="{{ url('/verify/home') }}">Dashboard</a>
                </li>
                <li>
                    <a href="{{ url('/verify/profile') }}">Accounts</a>
                </li>
                <li>
                    <a href="{{url('/verify/help')}}">Help & Support</a>
                </li>
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
    <div class="row"> --}}
        <div class="col-md-3 content-container mt-5">
            <!-- left-sidebar -->
            @include('guest.accounts.left-sidebar') 
        </div>
        <!-- start right sec -->
        <div class="col-md-8 content-wrapper">
            <div class="formCover" style="height: 100vh;">
               <!-- section -->
                  <div class="col-sm-10 offset-md-1">
                    <div class="row profile-block">
                        <div class="col-md-12">
                            <h4 class="card-title mb-1 mt-3">Help & Support </h4>
                            <p class="pb-border"> Create Your Queries  </p>
                        </div>
                        <div class="col-md-12">
                            <form action="{{url('/verify/help/save')}}" method="POST" enctype="multipart/form-data">
                                @csrf
                                    <div class="form-body">
                                        <div class="card radius shadow-sm">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="subject" class="form-control-label font-weight-300">Subject <span class="text-danger">*</span></label>
                                                            <input type="text" id="subject" class="form-control subject @error('subject') is-invalid @enderror" name="subject" placeholder="e.g. Subject">
                                                            @error('subject')
                                                                <div class="text-danger">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="form-group">
                                                            <label for="question" class="form-control-label font-weight-300">Comments <span class="text-danger">*</span></label>
                                                            <textarea id="question" class="form-control question " name="question" placeholder="e.g. Question"></textarea>
                                                            {{-- <input type="text" id="answer" class="form-control answer  @error('answer') is-invalid @enderror" name="answer" placeholder="e.g. Answer"> --}}
                                                            @error('question')
                                                                <div class="text-danger pt-2">{{ $message }}</div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="text-right">
                                                    <button class="btn btn-info updatebtn" type="submit">Save</button>
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
    {{-- </div>
</div>
</div> --}}


<script>
$(document).ready(function() {
    $('#question').summernote({
        minHeight: 400, 
    }
    );
  });
</script>
@endsection
