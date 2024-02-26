<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Id Verification</title>
    <meta content="" name="description">
    <meta content="" name="keywords">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Poppins', sans-serif;
    }
    table {
        width: 100%;
        margin: auto;
        padding: 0px
    }
    h5 {
        font-size: 10px;
        font-weight: 500;
    }
    p {
        color: #666666;
    }
    td p{
        font-size: 10px !important;
    }
</style>

<body>
    @php
        $data_array = [];
        if ($master_data->data_response != null) {
            $data_array = json_decode($master_data->data_response, true);
            //dd($data_array);
        }
    @endphp

    <table>
        <tr>
            <th style="width: 20%"><img src="{{ asset('admin/Vital4.png') }}" style=" width: 130px; float: left; padding: 10px;"></th>
            <td style="width: 20%">
                <h5 style="margin: 0px; ">Subject</h5>
                <p style="margin: 0px; line-height: 10px; font-size:14px; color:#000;" >{{ $master_data->subject }}</p>
            </td>
            <td style="width: 20%"> 
                <h5 style="margin: 0px;">Subject ID</h5>
                <p style="margin: 0px; line-height: 10px;">{{ $master_data->subjectId }}</p>
            </td>
            <td style="width: 20%">
                <h5 style="margin: 0px;">Aliases</h5>
                <p style="margin: 0px; line-height: 10px;">xxxxxxxxxxxx</p>
            </td>
            @if(isset($data_array['wlsResults']) && $data_array['wlsResults']!=null && count($data_array['wlsResults'])>0)
                <td style="width: 20%">
                    <h5 style="margin: 0px;">Run Date</h5>
                    <p style="margin: 0px; line-height: 10px;">{{ $data_array['wlsResults'][0]['remarks'] }}</p>
                </td>
            @endif
        </tr>
    </table>

    <table style="background-color: #ecececb7; padding: 10px; margin-bottom:5px;" >
        <tr>
            <th style="text-align: left;  padding: 10px; font-size: large;">
                Parameters</th>
        </tr>
        <tr style="padding: 2px 0px; height: 10px;"></tr>
        <td style="padding: 5px 10px;">
            <h5 style=" margin: 0px;">Address 1</h5>
            <p style="margin: 0px;">{{ $master_data->address_line_1 }}</p>
        </td>
        <td style="padding: 5px 10px;">
            <h5 style=" margin: 0px;">Address 2</h5>
            <p style="margin: 0px;">{{ $master_data->address_line_1 }}</p>
        </td>
        <td style="padding: 5px 10px;">
            <h5 style=" margin: 0px;">City</h5>
            <p style="margin: 0px;">{{ $master_data->city_district }}</p>
        </td>
        <td colspan="2" style="padding: 5px 10px;">
            <h5 style=" margin: 0px;">State</h5>
            <p style="margin: 0px;">{{ $master_data->state }}</p>
        </td>
        </tr>
        <tr style="padding: 2px 0px; height: 10px;"></tr>
        <td style="padding: 5px 10px;">
            <h5 style=" margin: 0px;">Country/Region</h5>
            <p style="margin: 0px;">{{ $master_data->country }}</p>
        </td>
        <td style="padding: 5px 10px;">
            <h5 style=" margin: 0px;">Postal Code</h5>
            <p style="margin: 0px;">{{ $master_data->postal_code }}</p>
        </td>
        <td style="padding: 5px 10px;">
            <h5 style=" margin: 0px;">Date of Birth</h5>
            <p style="margin: 0px;">{{ $master_data->dateOfBirth }}</p>
        </td>
        <td colspan="2" style="padding: 5px 10px;">
            <h5 style=" margin: 0px;">ID Number</h5>
            <p style="margin: 0px;">{{ $master_data->subjectId }}</p>
        </td>
        </tr>

        {{-- <tr style="padding: 2px 0px; height: 10px;"></tr>
        <td style="padding: 5px 10px;">
            <h5 style=" margin: 0px;">Keywords</h5>
            <p style="margin: 0px;">xxxxxxxxxxxx</p>
        </td>
        </tr> --}}
    </table>

        {{-- wls result report --}}
    @if(isset($data_array['wlsResults']) && $data_array['wlsResults']!=null && count($data_array['wlsResults'])>0)
        <table style="background-color: #ecececb7; padding: 10px; margin-bottom:5px;">
            <tr>
                <th>Watch Lists & Sanctions - 2 Results Categories Searched</th>
            </tr>

            <tr style="padding: 2px 0px; height: 10px;"></tr>
            <td style="padding: 5px 10px;">
                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(ALLWL) All WLS categories</h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(ACTIV) Activist</h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(AMLKC ) AML/KYC Compliance </h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(EXDEB ) Exclusion/Debarment </h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(FINAN )Financial </h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(GLCRM ) Global Criminal</h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(GLHLC) Global Healthcare</h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(GWLAS) Global Watch Lists and Sanctions</h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(IMEXP) Import/Export</h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(NATCF) National Criminal File </h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(NATHC) National Healthcare</h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(OIGHS) Office of the Inspector General</h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(RLEST)Real Estate</h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(SAMGE ) SAM.gov Exclusions</h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(SAMGV ) SAM.gov </h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(SXOFF) Sex Offenders </h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(WCORR )Corruption </h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(WOFAC ) OFAC </h5>

                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(WTERR ) Terrorism </h5>

            </td>
            </tr>
        </table>

       <div style="page-break-before:always"> &nbsp;</div>
        @php
            $data_array = [];
            if ($master_data->data_response != null) {
                $data_array = json_decode($master_data->data_response, true);
                $wlsResults = $data_array['wlsResults'];
                //dd($wlsResults);
            }
        @endphp
        

        @if(count($data_array) > 0)
            @foreach ($wlsResults as $key => $value)
                <table class="for_test">
                        <tr style="padding: 2px 0px; height: 10px;">
                            <td style="padding: 5px 10px; width:50%;">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Score</h5>
                                <?php 
                                    $decimalValue = number_format($value['score'],2);
                                    $percentageValue = $decimalValue * 100;       
                                ?>
                                <p style="margin: 0px; font-size: 28px; background-color:rgb(0, 148, 0); color:#fff; padding:10px; width:50px; text-align:center; border-radius:10px;">{{ $percentageValue }} %</p>
                            </td>
                            
                            <td style="padding: 5px 10px; width:50%;">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Source Agency Name</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['sourceAgencyName']}}</p>
                            </td>
                        </tr>
                        <tr style="padding: 2px 0px; height: 10px;">
                            <td style="padding: 5px 10px;">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Address</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$master_data->address_line_1}}</p>
                            </td>
                        </tr>
                        <tr style="padding: 2px 0px; height: 10px;">
                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Individual Name</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['individualName']}}</p>
                            </td>
                        
                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Identifier</h5>
                                <p style="margin: 0px; font-size: 14px; ">{{$value['identifier']}}</p>
                            </td>
                            <td  style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Date of Birth</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['dateOfBirth']}}</p>
                            </td>
                        </tr>
                        
                    

                    <tr style="padding: 2px 0px; height: 10px;">
                        <td style="padding: 5px 10px; width:33%">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">SourceList Type</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['sourceListType']}}</p>
                        </td>
                        <td style="padding: 5px 10px; width:33%" >
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Source Agency Name</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['sourceAgencyName']}}</p>
                        </td>
                        <td  style="padding: 5px 10px; width:33%">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Source Parent Agency</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['sourceParentAgency']}}</p>
                        </td>
                    </tr>

                        <tr style="padding: 2px 0px; height: 10px;">
                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Source Region</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['sourceRegion']}}</p>
                            </td>

                            <td style="padding: 5px 10px;   width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Categories</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['category']}}</p>
                            </td>
                        
                            <td  style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Remarks</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['remarks']}}</p>
                            </td>
                        </tr>
                        <tr style="padding: 2px 0px; height: 10px;">
                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Alias List</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['aliasList']}}</p>
                            </td>
                        
                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Program</h5>
                                <p style="margin: 0px;">{{$value['program']}}</p>
                            </td>
                    
                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Entity Name</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['entityName']}}</p>
                            </td>
                        </tr>
                    <tr style="padding: 2px 0px; height: 10px;">
                        <td style="padding: 5px 10px; width:33%">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Hair Color</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['hairColor']}}</p>
                        </td>
                    
                        {{-- <td style="padding: 5px 10px;">
                            <h5 style=" margin: 0px;">Weight</h5>
                            <p style="margin: 0px;">xxxxxxxxxxxx</p>
                        </td> --}}

                        <td  style="padding: 5px 10px; width:33%">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Height</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['height']}}</p>
                        </td>
                     
                       
                        <td style="padding: 5px 10px; width:33%">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Eye Color</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['eyeColor']}}</p>
                        </td>
                    </tr>
                    <tr style="padding: 2px 0px; height: 10px;">
                        <td style="padding: 5px 10px; width:33%">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Race</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['race']}}</p>
                        </td>
                   
                        <td  style="padding: 5px 10px; width:33%">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Nationality</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['nationality']}}</p>
                        </td>
                   
                      
                        <td style="padding: 5px 10px; width:33%">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Gender</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['gender']}}</p>
                        </td>
                    </tr>
                    <tr style="padding: 2px 0px; height: 10px;">
                        <td style="padding: 5px 10px; width:33%">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Age</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['age']}}</p>
                        </td>
                    
                        <td  style="padding: 5px 10px; width:33%">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Place of Birth</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['placeOfBirth']}}</p>
                        </td>
                        
                    </tr>
                        <tr style="padding: 2px 0px; height: 10px;">
                        <td  style="padding: 5px 10px;">
                            <h5 style=" margin: 0px; font-size: 22px; color:#000">Address</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['address']}}</p>
                        </td>
                    </tr>

                    <tr style="padding: 2px 0px; height: 10px;">
                        <td  style="padding: 5px 10px;">
                            <h2 style=" margin: 0px; ">URL</h2>
                            <p style="margin: 0px; font-size: 28px;">{{$value['url']}}</p>
                        </td>
                    </tr>
                        <tr style="padding: 2px 0px; height: 10px;">
                        <td  style="padding: 5px 10px;">
                            <h5 style=" margin: 0px;font-size: 14px;">Caution</h5>
                            <p style="margin: 0px;font-size: 14px;">{{$value['caution']}}</p>
                        </td>
                    </tr>   
                        <tr style="padding: 2px 0px; height: 10px;">
                        <td  style="padding: 5px 10px;">
                            <h2 style=" margin: 0px; ">Text</h2>
                            <p style="margin: 0px; font-size: 28px;">{{$value['text']}}</p>
                        </td>
                        </tr>   
                </table>

                @if(count($wlsResults)!=$key+1)
                    <pagebreak/>
                @endif

                
            @endforeach

        @endif 
    @endif

    {{-- wls result report end--}}
   {{-- @if(count($wlsResults) > 0)
         <pagebreak/>
    @endif --}}
    
    {{-- nm result report --}}
    @if(isset($data_array['nmResults']) && $data_array['nmResults']!=null && count($data_array['nmResults'])>0)
        <table style="background-color: #ecececb7; padding: 10px; margin-bottom:5px;">
            <tr>
                <th>Negative Media - 2 Results<br>Categories searched</th>
            </tr>

            <tr style="padding: 2px 0px; height: 10px;"></tr>
            <td style="padding: 5px 10px;">
                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(ALLNM ) All NM categories</h5>
                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(ANRTS )Animal Rights</h5>
                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(BUFIN ) Business & Financial </h5>
                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(CRCRT ) Crime & Courts </h5>
                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(GNEWS )General News </h5>
                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(MISEC ) Military & Security</h5>
                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(NMCOR ) Corruption </h5>
                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(POGOV) Politics & Government</h5>
            </td>
            </tr>
        </table>

        @php
            $data_array = [];
            if ($master_data->data_response != null) {
                $data_array = json_decode($master_data->data_response, true);
                $nmResults = $data_array['nmResults'];
               // dd($nmResults);
            }
        @endphp
    
        @if(count($nmResults) > 0)
            @foreach ($nmResults as $key => $value)
                <table class="for_test">
                        <tr style="padding: 2px 0px; height: 10px;"></tr>
                        <td style="padding: 5px 10px;">
                            <h5 style=" margin: 0px;  font-size: 20px;">Score</h5>
                            <?php 
                                    $decimalValue = number_format($value['score'],2);
                                    $percentageValue = $decimalValue * 100;       
                            ?>
                            <p style="margin: 0px; font-size: 28px; background-color:rgb(0, 148, 0); color:#fff; padding:10px; width:50px; text-align:center; border-radius:10px;">{{$percentageValue }} %</p>
                        </td>
                        </tr>
                        {{-- <td style="padding: 5px 10px;">
                            <h5 style=" margin: 0px; font-size: 20px;">Source Agency Name</h5>
                            <p style="margin: 0px; font-size: 20px;">{{$value['sourceAgencyName']}}</p>
                        </td> --}}
                    </tr>
                        <tr style="padding: 2px 0px; height: 10px;"></tr>
                        <td style="padding: 5px 10px; width:50%;">
                            <h5 style=" margin: 0px; font-size: 20px;">Category</h5>
                            <p style="margin: 0px; font-size: 28px;">{{$value['category']}}</p>
                        </td>
                    </tr>

                        <tr style="padding: 2px 0px; height: 10px;"></tr>
                        <td style="padding: 5px 10px;">
                            <h5 style=" margin: 0px; font-size: 20px;">SourceId</h5>
                            <p style="margin: 0px; font-size: 20px;">{{$value['sourceId']}}</p>
                        </td>
                    </tr>
                        
                        <tr style="padding: 2px 0px; height: 10px;"></tr>
                        <td style="padding: 5px 10px;">
                            <h2 style="margin: 0px; font-size: 20px;">URL</h2>
                            <p style="margin: 0px; font-size: 20px;">{{$value['url']}}</p>
                        </td>
                
                    </tr>   
                        <tr style="padding: 2px 0px; height: 10px;"></tr>
                        <td style="padding: 5px 10px;">
                            <h2 style="margin: 0px; font-size: 20px;">Text</h2>
                            <p style="margin: 0px; font-size: 20px;">{{$value['text']}}</p>
                        </td>
                        </tr>   
                </table>

                @if(count($nmResults)!=$key+1)
                    <pagebreak/>
                @endif

                {{-- @if(count($nmResults) > 0)
                    <pagebreak/>
                @endif
                 --}}
            @endforeach
        @endif 
    @endif
    {{-- nm result report end--}}
    
    {{-- @if(count($nmResults) > 0)
        <pagebreak/>
    @endif --}}

    @if(isset($data_array['pepResults']) && $data_array['pepResults']!=null && count($data_array['pepResults'])>0)
        <table style="background-color: #ecececb7; padding: 10px; margin-bottom:5px;">
            <tr>
                <th>Politically Exposed Persons (PEP) - Results <br> Categories searched</th>
            </tr>

            <tr style="padding: 2px 0px; height: 10px;"></tr>
            <td style="padding: 5px 10px;">
                <h5 style=" margin: 0px; font-size:10px; color:#000; font-weight:500;">(ALLPP  ) All PEP categories</h5>
            </td>
            </tr>

            <tr style="padding: 2px 0px; height: 10px;"></tr>
            <td style="padding: 5px 10px;">
                <h5 style=" margin: 0px;">Countries Searched</h5>
            </td>
            </tr>
        </table>

        @php
            $data_array = [];
            if ($master_data->data_response != null) {
                $data_array = json_decode($master_data->data_response, true);
                $pepResults = $data_array['pepResults'];
                // dd($pepResults);
            }
        @endphp
    

        @if(count($pepResults) > 0)
            @foreach ($pepResults as $key => $value)
                
                <table class="for_test">
                        <tr style="padding: 2px 0px; height: 10px;">
                            <td style="padding: 5px 10px; width:50%;">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000">Score</h5>
                                <p style="margin: 0px; font-size: 28px; background-color:rgb(0, 148, 0); color:#fff; padding:10px; width:50px; text-align:center; border-radius:10px;">{{number_format($value['score'],2) }} %</p>
                            </td>
                        </tr>
                       
                    
                        <tr style="padding: 2px 0px; height: 10px;">
                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000" >Birth Name</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['birthName']}}</p>
                            </td>

                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000" >Date of Birth</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['dateOfBirth']}}</p>
                            </td>
                        
                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000" >Place of Birth</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['placeOfBirth']}}</p>
                            </td>
                        </tr>
                      
                        <tr style="padding: 2px 0px; height: 10px;">
                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000" >Country</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['country']}}</p>
                            </td>

                            <td style="padding: 5px 10px; width:33%">
                                <h5 style=" margin: 0px; font-size: 22px; color:#000" >Date of Death</h5>
                                <p style="margin: 0px; font-size: 28px;">{{$value['dateOfDeath']}}</p>
                            </td>
                        </tr>
            
                        <?php
                            $professional = $value['professionalHistory'];
                            //dd($professional);
                        ?>  
                        @if(count($professional)>0) 
                            <tr>
                                <td style="padding: 5px 10px;">
                                    <h5 style=" margin: 0px; ">Professional History</h5>
                                    @for($pr=0; $pr<count($professional)-1; $pr++)
                                        <tr>
                                            <td colspan="2" style="padding: 5px 15px;">
                                                <ul class="list_style_custom">
                                                    <li ><span>{{$professional[$pr]}}</span></li>
                                                </ul>
                                            </td>
                                            <td colspan="2" style="padding: 5px 15px;">
                                                @if(array_key_exists($pr+1,$professional))
                                                    <ul class="list_style_custom" >
                                                        <li ><span>{{$professional[$pr+1]}}</span></li>
                                                    </ul>
                                                @endif
                                            </td>
                                            <?php $pr++;?>
                                        </tr>
                                    @endfor
                                </td>
                            </tr>
                        @endif
                    </tr>
                    <tr>
                        <?php
                            $memberOfs = $value['memberOf'];
                        ?>   
                        <td style="padding: 5px 10px;">
                            @if(count($memberOfs)>0)
                                <h5 style=" margin: 0px; ">Member Of</h5>
                                @foreach ($memberOfs as $member)
                                    <ul>
                                        <li>{{$member}}</li>
                                    </ul>
                                @endforeach
                            @endif
                        </td>
                    </tr>

                    <tr>
                        <?php
                            $awardsReceived = $value['awardsReceived'];
                        ?>   
                        <td style="padding: 5px 10px;">
                            @if(count($awardsReceived)>0)
                                <h5 style=" margin: 0px; ">Awards Received</h5>
                                @foreach ($awardsReceived as $awards)
                                    <ul>
                                        <li>{{$awards}}</li>
                                    </ul>
                                @endforeach
                            @endif
                        </td>
                    </tr>
                </table>

                @if(count($pepResults)!=$key+1)
                    <pagebreak/>
                @endif
            @endforeach
        @endif 
    @endif

</body>

</html>
