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
                <a href="{{ url('/faq') }}">FAQ</a>
            </li>
            <li>Edit</li>
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
        <div class="col-md-9 content-wrapper">
            <div class="formCover" style="height: 100vh; background:#fff">
               <!-- section -->
               <div class="col-sm-12 ">
                    <div class="row">
                        <div class="col-md-12">
                            <h4 class="card-title mb-1 mt-3">Edit FAQ </h4>
                            {{-- <p class="pb-border"> Edit the FAQ.  </p> --}}
                        </div>
                        <div class="col-md-12">        
                            <form action="{{url('/faq/update',['id'=>base64_encode($faq->id)])}}" method="POST">
                                @csrf
                                    <div class="form-body">
                                        <div class="card radius shadow-sm">

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-8">
                                                        <div class="form-group">
                                                            <label for="question" class="form-control-label font-weight-300">Question <span class="text-danger">*</span></label>
                                                            <input type="text" id="question" class="form-control question @error('question') is-invalid @enderror" name="question" value="{{$faq->question}}" placeholder="e.g. Question">
                                                            @error('question')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-12">
                                                    <div class="form-group">
                                                        <label for="answer" class="form-control-label font-weight-300">Answer <span class="text-danger">*</span></label>
                                                        <textarea id="answer" class="form-control answer  @error('answer') is-invalid @enderror" name="answer" placeholder="e.g. Answer">{{$faq->answer}}</textarea>
                                                        {{-- <input type="text" id="answer" class="form-control answer  @error('answer') is-invalid @enderror" name="answer" value="{{$faq->answer}}"  placeholder="e.g. Answer"> --}}
                                                        @error('answer')
                                                            <div class="text-danger">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="text-right">
                                                    <button class="btn btn-success" type="submit">Save</button>
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

<script>
$(document).ready(function() {
    $('#answer').summernote({
        // placeholder: 'e.g. Answer',
        // height: 100
        minHeight: 200,
    });
});
</script>
@endsection
