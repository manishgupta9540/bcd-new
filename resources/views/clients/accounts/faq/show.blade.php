@extends('layouts.client')
@section('content')
<div class="main-content-wrap sidenav-open d-flex flex-column">
<div class="main-content"> 
    <div class="row">
        <div class="col-sm-11">
            <ul class="breadcrumb">
            <li>
            <a href="{{ url('/my/home') }}">Dashboard</a>
            </li>
            <li>
                <a href="{{ url('/my/faq') }}">FAQ</a>
            </li>
            <li>Show</li>
            </ul>
        </div>
        <!-- ============Back Button ============= -->
        <div class="col-sm-1 back-arrow">
            <div class="text-right">
            <a href="{{ url()->previous() }}"><i class="fas fa-arrow-circle-left fa-2x"></i></a>
            </div>
        </div>
    </div>        
   {{-- <form action="{{url('/faq/update',['id'=>base64_encode($faq->id)])}}" method="POST">
      @csrf --}}
       
         <div class="form-body">
             <div class="card radius shadow-sm">

                 <div class="card-body">
                     <div class="row">
                        <div class="col-lg-4 col-sm-4 col-12">
                            <div class="form-group">
                                <label for="question" class="form-control-label font-weight-300">Question <span class="text-danger">*</span></label>
                                <input type="text" id="question" class="form-control question" name="question" value="{{$faq->question}}" placeholder="e.g. Question" readonly>
                                {{-- @error('question')
                                   <div class="text-danger">{{ $message }}</div>
                               @enderror --}}
                            </div>
                        </div>
                        <div class="col-lg-8 col-sm-8 col-12">
                           <div class="form-group">
                               <label for="answer" class="form-control-label font-weight-300">Answer <span class="text-danger">*</span></label>
                               <textarea id="answer" class="form-control answer " name="answer" placeholder="e.g. Answer" readonly>{{$faq->answer}}</textarea>
                               {{-- <input type="text" id="answer" class="form-control answer  @error('answer') is-invalid @enderror" name="answer" value="{{$faq->answer}}"  placeholder="e.g. Answer"> --}}
                               {{-- @error('answer')
                                   <div class="text-danger">{{ $message }}</div>
                               @enderror --}}
                           </div>
                       </div>
                     </div>
                 </div>
             </div>
         </div>
         
     </form>
</div>
</div>

<script>
$(document).ready(function() {
    $('#answer').summernote(
        // placeholder: 'e.g. Answer',
        // height: 100
    );
});
</script>
@endsection
