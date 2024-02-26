<style>
table.table.table-bordered.action-data>thead.thead-dark>tr>th {
    /* max-width: 200px; */
    min-width: 250px;
}
</style>
<div class="table-responsive"> 
    <table class="table table-bordered action-data">
        <thead class="thead-dark">
            <tr>
                <th scope="col" style='position: sticky;top:0px;'>S.no</th>
                <th scope="col" style='position: sticky;top:0px;'>Date of Receiving </th>
                <th scope="col" style='position: sticky;top:0px;'>Date of Punching</th>
                <th scope="col" style='position: sticky;top:0px;'>Punching Individual</th>
                <th scope="col" style='position: sticky;top:0px;'>Client Name</th>
                <th scope="col" style='position: sticky;top:0px;'>Client Code</th>
                <th scope="col" style='position: sticky;top:0px;'>Candidate Name</th>
                <th scope="col" style='position: sticky;top:0px;'>Case Reference No.</th>
                <th scope="col" style='position: sticky;top:0px;'>Bulk ID/Po No.</th>
                <th scope="col" style='position: sticky;top:0px;'>Bulk Billing Date</th>
                <th scope="col" style='position: sticky;top:0px;'>Receiving Checks</th>
                <th scope="col" style='position: sticky;top:0px;'>Check-Wise TAT (In Business Day)</th>
                <th scope="col" style='position: sticky;top:0px;'>Verification on Receiving Date</th>
                <th scope="col" style='position: sticky;top:0px;'>Case Wise TAT</th>
                <th scope="col" style='position: sticky;top:0px;'>Delivery Date</th>
                <th scope="col" style='position: sticky;top:0px;'>Vendor Name</th>
                <th scope="col" style='position: sticky;top:0px;'>Vendors Cost</th>
                <th scope="col" style='position: sticky;top:0px;'>MISC Cost</th>
                <th scope="col" style='position: sticky;top:0px;'>Client Penalty</th>
                <th scope="col" style='position: sticky;top:0px;'>Invoice Value</th>
                <th scope="col" style='position: sticky;top:0px;'>Submission Date of 1st Invoice</th>
                <th scope="col" style='position: sticky;top:0px;'>Due Date</th>
                <th scope="col" style='position: sticky;top:0px;'>Submission Date of 2nd Invoice</th>
                <th scope="col" style='position: sticky;top:0px;'>Payment Receipt Date</th>
                <th scope="col" style='position: sticky;top:0px;'>Quickbook Updation Date</th>
            </tr>
        </thead> 
        <tbody id="dummy_data">
            @if(count($imported_data)>0)
                @foreach ($imported_data as $key => $value)
                    <tr>
                        <td>{{$value[0]}}</td>
                        <td>{{$value[1]}}</td>
                        <td>{{$value[2]}}</td>
                        <td>{{$value[3]}}</td>
                        <td>{{$value[4]}}</td>
                        <td>{{$value[5]}}</td>
                        <td>{{$value[6]}}</td>
                        <td>{{$value[7]}}</td>
                        <td>{{$value[8]}}</td>
                        <td>{{$value[9]}}</td>
                        <td>{{$value[10]}}</td>
                        <td>{{$value[11]}}</td>
                        <td>{{$value[12]}}</td>
                        <td>{{$value[13]}}</td>
                        <td>{{$value[14]}}</td>
                        <td>{{$value[15]}}</td>
                        <td>{{$value[16]}}</td>
                        <td>{{$value[17]}}</td>
                        <td>{{$value[18]}}</td>
                        <td>{{$value[19]}}</td>
                        <td>{{$value[20]}}</td>
                        <td>{{$value[21]}}</td>
                        <td>{{$value[22]}}</td>
                        <td>{{$value[23]}}</td>
                        <td>{{$value[24]}}</td>
                        {{-- <td>{{$value[25]}}</td> --}}
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>