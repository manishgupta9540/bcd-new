
<br><br>
<form action="{{route('excelfileimport')}}" method="post" enctype="multipart/form-data">
    @csrf
    <div class="row">
        <div class="col-6">
            <label for="">Excel shett</label><br>
            <input type="file" name="excel_file" class="form-control">
        </div>
    </div>
    <br>
    <input type="submit" value="submit">
</form>