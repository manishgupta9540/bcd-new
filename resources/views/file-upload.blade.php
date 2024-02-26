<! DOCTYPE html>  
<html lang="en">  
<head>  
  <title>  File upload Example </title>  
  <meta charset="utf-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1">  
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">  
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"> </script>  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"> </script>  
   <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">  
</head>  
<style>  
.input-file {  
  position: relative;  
  display: block;  
  font-weight: 400;  
}  
  
.input-file .button {  
  position: absolute;  
  top: 4px;  
  right: 4px;  
  float: none;  
  height: 22px;  
  margin: 0;  
  padding: 0 14px;  
  font-size: 13px;  
  line-height: 22px;  
  background-color: #3276B1;  
  opacity: .8;  
  transition: opacity .2s;  
  -o-transition: opacity .2s;  
  -ms-transition: opacity .2s;  
  -moz-transition: opacity .2s;  
  -webkit-transition: opacity .2s;  
  outline: 0;  
  border: 0;  
  text-decoration: none;  
  color: #fff;  
  cursor: pointer;  
}  
body {  
  margin: 0;  
  padding: 0;  
  background-color: var(--clr-light);  
  color: var(--clr-black);  
  font-family: 'Poppins', sans-serif;  
  font-size: 1.125rem;  
  justify-content: center;  
  align-items: center;  
}  
h1 {  
font-family: 'Indie Flower', cursive;  
font-size: 32px;  
  color: #03A9F4;  
  font-weight: bold;  
  margin-bottom: 20px;  
}  
.input-file .button input {  
  position: absolute;  
  top: 0;  
  right: 0;  
  padding: 0;  
  font-size: 30px;  
  cursor: pointer;  
  opacity: 0;  
}  
.input input {  
    display: block;  
    box-sizing: border-box;  
    -moz-box-sizing: border-box;  
    width: 100%;  
    height: 28px;  
    padding: 8px 10px;  
    outline: 0;  
    border-width: 1px;  
    border-style: solid;  
    border-radius: 0;  
    background: #fff;  
    font: 13px/16px 'Open Sans', Helvetica,Arial, sans-serif;  
    color: #404040;  
    appearance: normal;  
    -moz-appearance: none;  
    -webkit-appearance: none;  
}   
</style>  
<body>  
    @foreach ($errors->all() as $error)
        {{  $error }}
    @endforeach
<div class="container">  
  <br>  
  <h1> File upload </h1> 
  <form method="post" action="{{route('/test-file-upload')}}" enctype="multipart/form-data">
      @csrf
        <div class="input-group">  
            <div class="input-group-prepend">  
                <span class="input-group-text" id="inputGroupFileAddon01"> File Upload </span>  
            </div>  
            <div class="custom-file">  
                <input type="file" class="custom-file-input" id="inputGroupFile01" name="image[]" multiple>  
                <label class="custom-file-label" for="inputGroupFile01"> Choose file ..</label>  
            </div>  
        </div> 
        <br>
        <div class="text-center">
            <button type="submit" class="btn btn-outline-primary">Submit</button> 
        </div>
  </form>
<br>                
</div>
<div class="row">
    @if ($file = Session::get('file-upload'))
        <div class="col-md-12">  
            <pre>
                @php
                    var_dump($file);die;
                @endphp
            </pre>
        </div>
    @endif
</div>
<script>
    $(".custom-file-input").on("change", function() {
        var files = Array.from(this.files)
        var fileName = files.map(f =>{return f.name}).join(", ")
        $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
</body>  
</html>  