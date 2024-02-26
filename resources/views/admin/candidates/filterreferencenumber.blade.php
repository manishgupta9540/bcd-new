@foreach ($candidates as $candidate)
    <option value="{{$candidate->id }}"> {{$candidate->display_id }} </option>
@endforeach

<script>
var uriNum = location.hash;
pageNumber = uriNum.replace("#", "");
// alert(pageNumber);
getData(pageNumber);
</script>

