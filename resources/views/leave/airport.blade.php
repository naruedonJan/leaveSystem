@extends('adminlte::page')

@section('title', 'Digitalmerce || จองตั๋ว')

@php
    use Carbon\Carbon;
    
    // path to your JSON file
    $url = '/json/airport.json'; 

    // put the contents of the file into a variable
    $path = File::get(storage_path($url));

    // decode the JSON feed
    $datas = json_decode($path);
@endphp

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading"><h4>จองตั๋วเครื่องบิน</h4></div>        
        <form action="/leave/flightAdd" method="post">
            @csrf
            <div class="panel-body">   
                
                <div class="row" id="flight-go-main">
                    <div class="col-lg-6 col-sm-6">
                        <div class="panel panel-info">                          
                            <div class="panel-heading" style="display: flex;">
                                <h4>ขาไป</h4>
                                <div style="margin-left: auto;">
                                    <input type="checkbox" name="" id="go-con-flight"> ต่อเครื่อง
                                </div>
                            </div>
                            <div class="panel-body">   
                                <div id="flight-go">
                                    <label>ต้นทาง</label>
                                    <input type="text" class="form-control" value="เชียงราย - ท่าอากาศยานแม่ฟ้าหลวง" name="bp_go_start_airportName" required readonly> 
                                    <label>ปลายทาง</label>
                                    <select name="bp_go_end_airportName" class="form-control" required>
                                        <option value="">สนามบินปลายทาง</option>
                                        @foreach ($datas as $data)
                                        <option value="{{$data->State . ' - ' . $data->Name}}">{{$data->State . ' - ' . $data->Name}}</option>
                                        @endforeach
                                    </select>
                                    <label>วันที่</label>
                                    <input type="date" name="bp_go_date" class="form-control" required>
                                    <label>เวลา</label>
                                    <input type="time" name="bp_go_time" class="form-control" required>
                                </div> 
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-lg-6 col-sm-6" id="flight-go-con" style="display: none;">                        
                        <div class="panel panel-info">                          
                            <div class="panel-heading"><h4>ต่อเครื่องขาไป</h4></div>
                            <div class="panel-body">
                                <div>
                                    <label>ต้นทาง</label>
                                    <select name="bp_go_con_start_airportName" class="form-control">
                                        <option value="">สนามบินต้นทาง</option>
                                        @foreach ($datas as $data)
                                        <option value="{{$data->State . ' - ' . $data->Name}}">{{$data->State . ' - ' . $data->Name}}</option>
                                        @endforeach
                                    </select>
                                    <label>ปลายทาง</label>
                                    <select name="bp_go_con_end_airportName" class="form-control">
                                        <option value="">สนามบินปลายทาง</option>
                                        @foreach ($datas as $data)
                                        <option value="{{$data->State . ' - ' . $data->Name}}">{{$data->State . ' - ' . $data->Name}}</option>
                                        @endforeach
                                    </select>
                                    <label>วันที่</label>
                                    <input type="date" name="bp_go_con_date" class="form-control">
                                    <label>เวลา</label>
                                    <input type="time" name="bp_go_con_time" class="form-control">
                                </div> 
                            </div>
                        </div>  
                    </div>
                </div>

                <div class="col-12">
                    <input type="checkbox" name="" id="back-flight" checked> ขากลับ
                </div>

                <div class="row" id="flight-back-main">
                    <div class="col-lg-6 col-sm-6">
                        <div class="panel panel-primary">
                            <div class="panel-heading" style="display: flex;">
                                <h4>ขากลับ</h4>                                
                                <div style="margin-left: auto;">
                                    <input type="checkbox" name="" id="back-con-flight"> ต่อเครื่อง
                                </div>
                            </div>
                            <div class="panel-body">       
                                <div id="flight-back">
                                    <label>ต้นทาง</label>
                                    <select name="bp_back_start_airportName" class="form-control">
                                        <option value="">สนามบินต้นทาง</option>
                                        @foreach ($datas as $data)
                                        <option value="{{$data->State . ' - ' . $data->Name}}">{{$data->State . ' - ' . $data->Name}}</option>
                                        @endforeach
                                    </select>
                                    <label>ปลายทาง</label>
                                    <select name="bp_back_end_airportName" class="form-control">
                                        <option value="">สนามบินปลายทาง</option>
                                        @foreach ($datas as $data)
                                        <option value="{{$data->State . ' - ' . $data->Name}}">{{$data->State . ' - ' . $data->Name}}</option>
                                        @endforeach
                                    </select>
                                    <label>วันที่</label>
                                    <input type="date" name="bp_back_date" class="form-control">
                                    <label>เวลา</label>
                                    <input type="time" name="bp_back_time" class="form-control">
                                </div>    
                            </div>
                        </div>
                    </div>   

                    <div class="col-lg-6 col-sm-6" id="flight-back-con" style="display: none;">
                        <div class="panel panel-primary">                          
                            <div class="panel-heading"><h4>ต่อเครื่องขากลับ</h4></div>
                            <div class="panel-body">       
                                <div>
                                    <label>ต้นทาง</label>
                                    <select name="bp_back_con_start_airportName" class="form-control">
                                        <option value="">สนามบินต้นทาง</option>
                                        @foreach ($datas as $data)
                                        <option value="{{$data->State . ' - ' . $data->Name}}">{{$data->State . ' - ' . $data->Name}}</option>
                                        @endforeach
                                    </select>
                                    <label>ปลายทาง</label>
                                    <select name="bp_back_con_end_airportName" class="form-control">
                                        <option value="">สนามบินปลายทาง</option>
                                        @foreach ($datas as $data)
                                        <option value="{{$data->State . ' - ' . $data->Name}}">{{$data->State . ' - ' . $data->Name}}</option>
                                        @endforeach
                                    </select>
                                    <label>วันที่</label>
                                    <input type="date" name="bp_back_con_date" class="form-control">
                                    <label>เวลา</label>
                                    <input type="time" name="bp_back_con_time" class="form-control">
                                </div>  
                            </div>
                        </div>
                    </div>  
                </div>
                <button type="submit" class="btn btn-success">ยืนยัน</button>
            </div>            
        </form> 
    </div>
@stop

@section('js')

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script>
$('#go-con-flight').change(function () {    
    if (this.checked){                
        $('#flight-go-con').show();
    }
    else{     
        $('#flight-go-con').hide();
    }
});

$('#back-con-flight').change(function () {    
    if (this.checked){                
        $('#flight-back-con').show();
    }
    else{     
        $('#flight-back-con').hide();
    }
});

$('#back-flight').change(function () {    
    var r = confirm("คุณต้องการตั๋วขากลับ");
    if (r == true) {    
        document.getElementById("back-flight").checked = true; 
    } else {
        document.getElementById("back-flight").checked = false; 
    }
    if (this.checked){                
        $('#flight-back-main').show();
    }
    else{     
        $('#flight-back-main').hide();
    }
});

$("#province").select2();
$("#amphur").select2();
$("#district").select2();
</script>

@endsection



