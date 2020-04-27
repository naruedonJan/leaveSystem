@extends('adminlte::page')

@section('title', 'DIGITALMERCE')

@section('content')
@php
    use Carbon\Carbon;
    $thai_month_arr=array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
@endphp
    {{-- <div class="panel panel-success">
        <div class="panel-heading">
            <h4>dashboard</h4>
        </div> --}}
        <div class="panel-body">
            <div class="container-fluid" >
                {{-- <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item active">
                        <a class="nav-link" id="pills-dgmnews-tab" data-toggle="pill" href="#pills-dgmnews" role="tab" aria-controls="pills-dgmnews" aria-selected="true">ข่าวสารบริษัท</a>
                    </li>
                </ul> --}}
                <div class="tab-content" id="pills-tabContent">
                    <div class="tab-pane fade active in" id="pills-dgmnews" role="tabpanel" aria-labelledby="pills-dgmnews-tab">
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                ข่าวสารบริษัท
                            </div>
                            <div class="panel-body">
                                @foreach ($news as $new)
                                   <ul>
                                       <li>{{ $new->ns_text }}</li>
                                       <p>ประกาศวันที่ : {{ Carbon::parse($new->ns_date)->format('d') }} {{ $thai_month_arr[Carbon::parse($new->ns_date)->format('n')] }} {{ (Carbon::parse($new->ns_date)->format('Y')+543) }}</p>
                                   </ul>
                                   <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{-- </div> --}}

    {{-- birthday popup  --}}
        @if ($count_bd != 0)
            @php
                $pop = 'pop'
            @endphp
        @else
            @php
                $pop = ''
            @endphp
        @endif
        <a href='#myModal' data-toggle='modal' data-target='#popaa' style="color:white;text-decoration:none;">
            <div id="{{$pop}}" style="color:white;text-decoration:none;">.</div>
        </a>

        <div class='modal' id='popaa' role='dialog'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content' style="position:relative;">
                    <img src="/images/birthday2.jpg" alt="" style="width:624px"/>
                    <h2 style="background:#212121;position:absolute;top: 30px;left: 370px;color:#fff; padding:10px; font-size:26px;">พนักงานที่เกิดวันนี้</h2>
                    <h3 style="position:absolute;font-size:30px;font-weight:bold;top: 100px;left: 420px;">
                    </h3>
                    <h3 style="background:#212121;position:absolute;bottom: 10px;color:#fff; padding:10px;">Happy Birth day to...
                    @foreach ($birthdays as $birthday)
                        {{$birthday->nickname}}
                    @endforeach
                    </h3>
                    <button style="position:absolute;bottom: 20px;left: 510px;" type='button' class='btn btn-info' data-dismiss='modal'>Close</button>
                </div>
            </div>
        </div>

<script type="text/javascript">
    window.onload = function() {
        popupFunction();
    }
    function popupFunction()
    {
        var pp = document.getElementById("pop").innerHTML;
        console.log(pp);
        if (pp === ".")
        {
            $('#pop').trigger('click');
        }
    }
</script>
@stop
