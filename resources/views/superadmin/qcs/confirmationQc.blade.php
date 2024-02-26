@extends('layouts.superadmin')
@section('content')

 <div class="main-content-wrap sidenav-open d-flex flex-column">

 <div class="main-content">             
 
                <div class="row">
                    <div class="card text-left">
                        <div class="card-body">
               
                            <div class="row">
                               
                                <div class="col-md-12 QC-data">
                                    <h3 class="card-title mb-3"> QC Form </h3>
                                   
                                   
                                        <div class="data">
                                            <p><strong>Name :</strong> {{$candidate->name}}</p>
                                        </div>
                                        <div class="data">
                                            <p><strong>Address :</strong> {{$candidate->full_address}}</p>
                                        </div>
                                        <div class="data">
                                            <p><strong>Mobile :</strong> {{$candidate->phone}}</p>
                                        </div>
                                   
                                </div>
                            </div>
                            <div class="row mt-30">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-collapsed">
                                        <tbody>
                                        <tr>
                                            <td></td>
                                            <td>Correct</td>
                                        </tr>
                                            <tr>
                                                <td><strong>Period of stay:</strong> 1995-07-09 to 2020-07-09</td>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="stay" id="inlineRadio1" value="option1">
                                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="stay" id="inlineRadio2" value="option2">
                                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><strong>Type of address:</strong> Current</td>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="address" id="inlineRadio1" value="option3">
                                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="address" id="inlineRadio2" value="option4">
                                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td><strong>Ownership-status:</strong> own</td>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="ownership" id="inlineRadio1" value="option5">
                                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="ownership" id="inlineRadio2" value="option6">
                                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="row mt-30">
                                <div class="col-md-12">
                                    <h3>Documents Uploaded :</h3>
                                </div>
                            </div>

                            <div class="row mt-30">
                                <div class="col-md-6">
                                    <div class="data-selfie">
                                        <p class="label">Selfie</p>
                                        <img src="images/selfie.png">
                                        <div class="img-data">
                                            <p><strong>Timestamp :</strong>2020-07-09 16:00:05</p>
                                            <p><strong>Location :</strong>28.00778866 , 25.00886766</p>
                                        </div>
                                    </div>

                                    <div class="checked-docs">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="prfoile-correct" id="inlineRadio1" value="option5">
                                            <label class="form-check-label" for="inlineRadio1">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="prfoile-correct" id="inlineRadio2" value="option6">
                                            <label class="form-check-label" for="inlineRadio2">No</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="data-selfie">
                                        <p class="label">Id Proof</p>
                                        <img src="images/id.jpg">
                                        <div class="img-data">
                                            <p><strong>Timestamp :</strong>2020-07-09 16:00:05</p>
                                            <p><strong>Location :</strong>28.00778866 , 25.00886766</p>
                                        </div>
                                        
                                    </div>
                                    <div class="checked-docs">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="id-correct" id="inlineRadio1" value="option5">
                                            <label class="form-check-label" for="inlineRadio1">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="id-correct" id="inlineRadio2" value="option6">
                                            <label class="form-check-label" for="inlineRadio2">No</label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-30">
                                <div class="col-md-12">
                                    <h3>Address shown on the map (Radius: 300m)</h3>
                                    <table class="table table-bordered table-collapsed">
                                        <tbody>
                                            <tr style="background:#ececec;">
                                                <th>Description</th>
                                                <th>Source</th>
                                                <th>Distance</th>
                                                <th>Location Resolution Logic</th>
                                                <th>Legend</th>
                                                <th>Action</th>
                                            </tr>
                                            <tr>
                                                <td>House no. 598, sector 12</td>
                                                <td>Input Address</td>
                                                <td>0 Km.</td>
                                                <td>Google Location Api</td>
                                                <td><span class="zone redzone">.</span></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td>28.99976756, 30.785656867 </td>
                                                <td>GPS</td>
                                                <td>0.00 km.</td>
                                                <td>Device Location Logic</td>
                                                <td><span class="zone greenzone">.</span></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="4">
                                                <form class="form-inline">
                                                    <p>Map QC</p>
                                                    <div class="form-group mx-sm-3 mb-2">
                                                        <label for="address1" class="sr-only">Password</label>
                                                        <input type="text" class="form-control" id="address1" placeholder="Add Document Address" style="width:235px;">
                                                    </div>
                                                    <button type="submit" class="btn btn-primary mb-2">Show On Map</button>
                                                </form> 
                                                </td>
                                                <td colspan="2">
                                                <div class="">
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="maper" id="inlineRadio1" value="option5">
                                                        <label class="form-check-label" for="inlineRadio1">Yes</label>
                                                    </div>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="maper" id="inlineRadio2" value="option6">
                                                        <label class="form-check-label" for="inlineRadio2">No</label>
                                                    </div>
                                                </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                <div class="maps">
                                    <div id="map" style=" height: 350px;">
                                    
                                    </div>
                                </div>
                                </div>
                            </div>

                            <div class="row mt-30 mb-50">
                                <div class="col-md-6 offset-3">
                                <h3>QC Decision</h3>
                                <div class="form-group">
                                    <label for="sel">Example select</label>
                                    <select class="form-control" id="sel">
                                    <option>Pass</option>
                                    <option>Fail</option>
                                    
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="comment">Comments</label>
                                    <textarea class="form-control" rows="4"></textarea>
                                </div>

                                <button type="submit" class="btn btn-primary mb-2 width-100">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
            </div><!-- Footer Start -->
            <div class="flex-grow-1"></div>
           
</div>
            

<script type="text/javascript">
  

var latlng;
var marker;
function initialize () {
var mapOptions = { 
      zoom: 10, 
      center: new google.maps.LatLng(28.557020,77.326240), 
      mapTypeId: google.maps.MapTypeId.TERRAIN
};

var map = new google.maps.Map(document.getElementById("map"),mapOptions  );
console.log("alex");

var data = [
            {"Latitude":"28.557020","Longitude":"77.326240"},
            {"Latitude":"28.589029","Longitude":"77.301613"}
          ];
  //console.log(latlngArray);
  var populationOptions = {
      strokeColor: '#FF0000',
      strokeOpacity: 0.2,
      strokeWeight: 6,
      fillColor: '#FF0000',
      fillOpacity: 0.35,
      map: map,
      center: latlng,
      radius: 20000,
      } 

  for (var i = 0; i < data.length; i++) {
    populationOptions.center = new google.maps.LatLng(data[i].Latitude,data[i].Longitude);
    cityCircle = new google.maps.Circle(populationOptions);  

  }
 
}

function loadScript() {
  var script = document.createElement('script');
  script.type = 'text/javascript';
  script.src = 'https://maps.googleapis.com/maps/api/js?key=AIzaSyCmMr84yU4TlsewCIOrN7pJnXQCHvlnHcU&sensor=false&' 
  + 'callback=initialize';
  document.body.appendChild(script);
  console.log("aaa");
}

window.onload = loadScript;

</script>

@endsection