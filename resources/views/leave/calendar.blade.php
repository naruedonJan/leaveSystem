@extends('adminlte::page')

@section('title', 'Digitalmerce || ปฏิทิน')
@section('css')
  <!-- fullCalendar -->
  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
  <style>
    .switch {
      position: relative;
      display: inline-block;
      width: 45px;
      height: 25px;
    }

    .switch input {
      opacity: 0;
      width: 0;
      height: 0;
    }
    .slider {
      position: absolute;
      cursor: pointer;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background-color: #ccc;
      -webkit-transition: 0.4s;
      transition: 0.4s;
    }

    .slider:before {
      position: absolute;
      content: "";
      height: 20px;
      width: 20px;
      left: 3px;
      bottom: 3px;
      background-color: white;
      -webkit-transition: 0.4s;
      transition: 0.4s;
    }

    input:checked + .slider {
      background-color: #00a65a;
    }

    input:focus + .slider {
      box-shadow: 0 0 1px #00a65a;
    }

    input:checked + .slider:before {
      -webkit-transform: translateX(20px);
      -ms-transform: translateX(20px);
      transform: translateX(20px);
    }

    /* Rounded sliders */
    .slider.round {
      border-radius: 34px;
    }

    .slider.round:before {
      border-radius: 50%;
    }

    @-webkit-keyframes spin {
      0% {
        -webkit-transform: rotate(0deg);
      }
      100% {
        -webkit-transform: rotate(360deg);
      }
    }

    @keyframes spin {
      0% {
        transform: rotate(0deg);
      }
      100% {
        transform: rotate(360deg);
      }
    }
    </style>
@endsection
@php
    use Carbon\Carbon;
@endphp

@section('content_header')
    {{-- <h1>ลางานออนไลน์</h1> --}}
@stop

@section('content')
<div style="display:flex; margin-left: 20px;">
<label class="switch">
    <input type="checkbox" id="swapCalendar"/>
    <span class="slider round"></span>
</label>
<p style="margin: 5px 5px 10px;">สลับโหมด</p>
</div>
    <div class="row container-fluid">
        <div class="col-md-4 col-sm-12" id="Leave">
            @if (Auth::user()->permission == 'admin' ||Auth::user()->permission == 'boss' )
            {{-- Admin/Boss's view --}}
                <div class="panel panel-warning">
                    <div class="panel-heading"><h4>ตัวเลือกการมองเห็น</h4></div>
                    <div class="panel-body">
                        <form method="GET" class="row">
                            <div class="col-sm-12">
                                <select name="emp" id="choose_emp" class="form-control">
                                    <option value="">เลือกชื่อพนักงาน</option>
                                    @foreach ($user as $u)
                                    <option value="{{$u->id}}" @if(!empty($_GET['emp']) && $_GET['emp'] == $u->id) {{'selected'}} @endif>{{$u->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-success">ค้นหา</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading"><h4>ลงลาออนไลน์</h4></div>
                <div class="panel-body">
                        <form class="row" method="POST" action="/admin/leave/admin-add-calender">
                        @csrf
                        <div class="form-group col-md-12 col-sm-12">
                            <label for="emp_id">เลือกชื่อพนักงาน</label>
                            <select name="emp_id" id="emp_id" class="form-control" required>
                                <option value="">เลือกชื่อพนักงาน</option>
                                @foreach ($user as $user)
                                    <option value="{{$user->emp_id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="reason">ประเภทการลา</label>
                            <select name="reason" id="reason" class="form-control" required>
                                <option value="">เลือกประเภทการลา</option>
                                @foreach ($leavetype as $type)
                                    <option value="{{$type->id}}">{{$type->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <label for="type">ช่วงเวลา</label>
                            <select name="type" id="type" class="form-control">
                                <option value="1.0">ทั้งวัน</option>
                                <option value="0.5">ครึ่งวัน</option>
                            </select>
                        </div>
                        <div class="form-group col-md-12 col-sm-12">
                            <label for="type">ตั้งแต่</label>
                            <input type="date" name="start" id="start" value="{{$startDate}}" class="form-control">
                        </div>
                        <div class="form-group col-md-6 col-sm-12">
                            <input type="checkbox" name="" id="longHoliday">
                            <label for="type">ต้องการหยุดต่อเนื่อง</label>
                        </div>
                        <div class="form-group col-md-12 col-sm-12" id="enddate" style="display: none;">
                            <label for="type">ถึง</label>
                            <input type="date" name="end" id="end" value="{{$endDate}}" class="form-control" disabled>
                        </div>
                        <div class="form-group col-md-12">
                            <label for="type">หมายเหตุ</label>
                            <textarea name="note" id="note" class="form-control"></textarea>
                        </div>
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-success btn-sm btn-block">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>

            @else
            {{-- Employee's view --}}
                <div class="panel panel-info">
                        <div class="panel-heading"><h4>ลงลาออนไลน์</h4></div>
                    <div class="panel-body">
                            <form class="row" method="POST" action="/leave/add-calender">
                            @csrf
                            <input type="hidden" name="dept_id" value="{{Auth::user()->department}}" class="form-control">
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="reason">ประเภทการลา</label>
                                <select name="reason" id="reason" class="form-control" required>
                                    <option value="">เลือกประเภทการลา</option>
                                    @foreach ($leavetype as $type)
                                        @if (Carbon::now()->isoFormat('dddd') == "Sunday")
                                            <option value="{{$type->id}}">{{$type->name}}</option>
                                        @else
                                            @if ($type->id != 3)
                                                <option value="{{$type->id}}">{{$type->name}}</option>
                                            @endif
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <label for="type">ช่วงเวลา</label>
                                <select name="type" id="type" class="form-control">
                                    <option value="1.0">ทั้งวัน</option>
                                    <option value="0.5">ครึ่งวัน</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12 col-sm-12">
                                <label for="type">ตั้งแต่</label>
                                <input type="date" name="start" id="start" value="{{$startDate}}" class="form-control">
                            </div>
                            <div class="form-group col-md-6 col-sm-12">
                                <input type="checkbox" name="" id="longHoliday">
                                <label for="type">ต้องการหยุดต่อเนื่อง</label>
                            </div>
                            <div class="form-group col-md-12 col-sm-12" id="enddate" style="display: none;">
                                <label for="type">ถึง</label>
                                <input type="date" name="end" id="end" value="{{$endDate}}" class="form-control" disabled>
                            </div>
                            <div class="form-group col-md-12">
                                <label for="type">หมายเหตุ</label>
                                <textarea name="note" id="note" class="form-control" required></textarea>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success btn-sm btn-block">บันทึก</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading col-md-12">
                        <h4 class="col-md-8">วันหยุดบริษัทเดือนนี้ </h4>
                        <button type="button" class="btn btn-info col-md-4" data-toggle="modal" data-target="#holiday">ดูวันหยุดทั้งหมด</button>
                    </div>
                    <div class="panel-body">
                        <div style="height:150px;padding:10px;border:1px solid #ccc;overflow:auto;background-color:#eee;">
                            @foreach ($holidays_m as $holiday)
                                <p>{{Carbon::parse($holiday->date)->format('l d M Y')}} : {{$holiday->name}}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Modal holiday -->
                <div class="modal fade bd-example-modal-lg" id="holiday" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="panel panel-info">
                                <div class="panel-heading"><h5>วันหยุดปีนี้</h5></div>
                            </div>
                            <div class="modal-body">
                                @foreach ($holidays_y as $holiday)
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            @if ($holiday->date >= Carbon::now())
                                                <p style="color:#004eff">{{Carbon::parse($holiday->date)->format('l d M Y')}} : {{$holiday->name}}</p>
                                            @else
                                                <p>{{Carbon::parse($holiday->date)->format('l d M Y')}} : {{$holiday->name}}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal holiday -->

                <div class="panel panel-danger">
                    <div class="panel-heading col-md-12">
                        <h4 class="col-md-8">วันห้ามหยุดบริษัทเดือนนี้</h4>
                        <button type="button" class="btn btn-danger col-md-4" data-toggle="modal" data-target="#cannotHoliday">ดูวันห้ามหยุดทั้งหมด</button>
                    </div>
                    <div class="panel-body">
                        <div style="height:150px;padding:10px;border:1px solid #ccc;overflow:auto;background-color:#eee;">
                            @foreach ($cannotholidays_m as $holiday)
                                <p>{{Carbon::parse($holiday->date)->format('l d M Y')}} : {{$holiday->name}}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Modal cannot holiday -->
                <div class="modal fade bd-example-modal-lg" id="cannotHoliday" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="panel panel-danger">
                                <div class="panel-heading"><h5>วันห้ามหยุดปีนี้</h5></div>
                            </div>
                            <div class="modal-body">
                                @foreach ($cannotholidays_y as $holiday)
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            @if ($holiday->date >= Carbon::now())
                                                <p style="color:#fd0000">{{Carbon::parse($holiday->date)->format('l d M Y')}} : {{$holiday->name}}</p>
                                            @else
                                                <p>{{Carbon::parse($holiday->date)->format('l d M Y')}} : {{$holiday->name}}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal cannot holiday -->

                <div class="panel panel-success">
                    <div class="panel-heading"><h4> ข่าวสารจากบริษัท</h4></div>
                    <div class="panel-body">
                        <div style="height:150px;padding:10px;border:1px solid #ccc;overflow:auto;background-color:#eee;">
                            @foreach ($all_news as $news)
                                <p>{{Carbon::parse($news->ns_date)->format('d M Y')}} : {{$news->ns_text}}</p>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="panel panel-warning">
                    <div class="panel-heading"><h4>ตัวเลือกการมองเห็น</h4></div>
                    <div class="panel-body">
                        <form method="GET" class="row">
                            <div class="col-sm-9">
                                <select name="emp" id="choose_emp2" class="form-control">
                                    <option value="">เลือกชื่อพนักงาน</option>
                                    @foreach ($user as $u)
                                    <option value="{{$u->id}}">{{$u->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-sm-3">
                                <button type="submit" class="btn btn-success">ค้นหา</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-md-8" id="Calendar">
            <label for="" style="padding-right:30px; background:red; height: 15px; margin-bottom: 0px;"></label>ลากิจ
            <label for="" style="padding-right:30px; background:green; height: 15px; margin-bottom: 0px;"></label>ลาป่วย
            <label for="" style="padding-right:30px; background:blue; height: 15px; margin-bottom: 0px;"></label>หยุดประจำสัปดาห์
            <label for="" style="padding-right:30px; background:black; height: 15px; margin-bottom: 0px;"></label>ลากรณีพิเศษ
            <label for="" style="padding-right:30px; background:#696969; height: 15px; margin-bottom: 0px;"></label>เรียน
            <label for="" style="padding-right:30px; background:pink; height: 15px; margin-bottom: 0px;"></label>ทำงานนอกสถานที่
            <label for="" style="padding-right:30px; background:#ed4bca; height: 15px; margin-bottom: 0px;"></label>ลาบวช,ลาคลอด
            <label for="" style="padding-right:30px; background:orange; height: 15px; margin-bottom: 0px;"></label>ลากิจครึ่งวัน
            <label for="" style="padding-right:30px; background:#b32be5; height: 15px; margin-bottom: 0px;"></label>ลาป่วยครึ่งวัน
            <div class="box box-primary">
            <div class="box-body">
                <!-- THE CALENDAR -->
                {!! $calendar->calendar() !!}
            </div>
            <!-- /.box-body -->
            </div>
            <!-- /. box -->
        </div>
        <!-- /.col -->
    </div>
      <!-- /.row -->
@if (Auth::user()->permission == 'admin' ||Auth::user()->permission == 'boss' )
<div class="row col-md-12">
    <div class="col-md-4">
        <div class="panel panel-info">
            <div class="panel-heading"><h4>จัดการวันหยุดบริษัท </h4></div>
            <div class="panel-body">
                <form class="row" method="POST" action="/admin/leave/add-holiday">
                    @csrf
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="type">วันที่</label>
                        <input type="date" name="start" id="start" value="{{$startDate}}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="type">ชื่อวันหยุด</label>
                        <input type="text" class="form-control" name="holiday_name">
                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-success btn-sm btn-block">เพิ่มวันหยุด</button>
                    </div>
                </form>
            </div>
            {{-- <div class="panel-heading"><h4>วันหยุดบริษัท </h4></div> --}}
            <div class="panel-body">
                <div style="height:200px;overflow:auto;">
                <table class="table">
                    <thead style="background: #d9edf7;">
                        <th class="text-center">วันที่</th>
                        <th class="text-center">ชื่อวันหยุด</th>
                        <th class="text-center">แก้ไข</th>
                        <th class="text-center">ลบ</th>
                    </thead>
                    <tbody>
                    @foreach ($holidays_y as $holiday)
                        <tr>
                            <td class="text-center">{{Carbon::parse($holiday->date)->format('d M Y')}}</td>
                            <td>{{$holiday->name}}</td>
                        @if ($holiday->date < Carbon::now())
                            <td colspan="2"></td>
                        @else
                            <td class="text-center">
                                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editHoliday{{$holiday->id}}">แก้ไข</button>
                            </td>
                            <td class="text-center">
                                <form action="/admin/leave/cancle-holiday" method="post">
                                    @csrf
                                    <input type="hidden" name="id" value="{{$holiday->id}}">
                                    <button type="submit" class="btn btn-danger" onclick= "return confirm('ยกเลิกวันหยุด?');">ยกเลิก</button>
                                </form>
                            </td>
                        @endif
                        </tr>
                        <!-- Modal edit holiday -->
                        <div class="modal fade bd-example-modal-lg" id="editHoliday{{$holiday->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="panel panel-info">
                                        <div class="panel-heading"><h5>แก้ไขวันหยุด</h5></div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <form class="row" method="POST" action="/admin/leave/edit-holiday">
                                                    @csrf
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="type">วันที่</label>
                                                        <input type="hidden" name="id" value="{{$holiday->id}}">
                                                        <input type="date" name="start" id="start" value="{{$holiday->date}}" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6">
                                                        <label for="type">ชื่อวันหยุด</label>
                                                        <input type="text" class="form-control" name="holiday_name" value="{{$holiday->name}}">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <button type="submit" class="btn btn-success btn-sm btn-block">แก้ไขวันหยุด</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Modal edit holiday -->
                    @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-danger ">
            <div class="panel-heading"><h4>จัดการวันห้ามหยุด </h4></div>
            <div class="panel-body">
                <form class="row" method="POST" action="/admin/leave/add-cannot-holiday">
                    @csrf
                    <div class="form-group col-md-6 col-sm-12">
                        <label for="type">วันที่</label>
                        <input type="date" name="start" id="start" value="{{$startDate}}" class="form-control">
                    </div>
                    <div class="form-group col-md-6">
                        <label for="type">เหตุผล</label>
                        <input type="text" class="form-control" name="holiday_name">
                    </div>
                    <div class="form-group col-md-12">
                        <button type="submit" class="btn btn-success btn-sm btn-block">เพิ่มวันห้ามหยุด</button>
                    </div>
                </form>
            </div>
            <div class="panel-body">
                <div style="height:200px;overflow:auto;">
                    <table class="table">
                        <thead style="background: #f2dede;">
                            <th class="text-center">วันที่</th>
                            <th class="text-center">เหตุผล</th>
                            <th class="text-center">แก้ไข</th>
                            <th class="text-center">ลบ</th>
                        </thead>
                        <tbody>
                        @foreach ($cannotholidays_y as $holiday)
                            <tr>
                                <td class="text-center">{{Carbon::parse($holiday->date)->format('d M Y')}}</td>
                                <td>{{$holiday->name}}</td>
                            @if ($holiday->date < Carbon::now())
                                <td colspan="2"></td>
                            @else
                                <td class="text-center">
                                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editHoliday{{$holiday->id}}">แก้ไข</button>
                                </td>
                                <td class="text-center">
                                    <form action="/admin/leave/cancle-holiday" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$holiday->id}}">
                                        <button type="submit" class="btn btn-danger" onclick= "return confirm('ยกเลิกวันห้ามหยุด?');">ยกเลิก</button>
                                    </form>
                                </td>
                            @endif
                            </tr>
                            <!-- Modal edit cannot holiday -->
                            <div class="modal fade bd-example-modal-lg" id="editHoliday{{$holiday->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="panel panel-danger">
                                            <div class="panel-heading"><h5>แก้ไขวันห้ามหยุด</h5></div>
                                        </div>
                                        <div class="modal-body">
                                            <div class="input-group mb-3">
                                                <div class="input-group-prepend">
                                                    <form class="row" method="POST" action="/admin/leave/edit-holiday">
                                                        @csrf
                                                        <div class="form-group col-md-6 col-sm-12">
                                                            <label for="type">วันที่</label>
                                                            <input type="hidden" name="id" value="{{$holiday->id}}">
                                                            <input type="date" name="start" id="start" value="{{$holiday->date}}" class="form-control">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <label for="type">ชื่อวันห้ามหยุด</label>
                                                            <input type="text" class="form-control" name="holiday_name" value="{{$holiday->name}}">
                                                        </div>
                                                        <div class="form-group col-md-12">
                                                            <button type="submit" class="btn btn-success btn-sm btn-block">แก้ไขวันห้ามหยุด</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Modal edit cannot holiday -->
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h4>จัดการข่าวสาร
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#news" style="float: right; padding:0px 10px">เพิ่มข่าวสาร</button>
                    <!-- Modal add news -->
                    <div class="modal fade bd-example-modal-lg" id="news" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="panel panel-success">
                                    <div class="panel-heading"><h5>ข่าวสารจากบริษัท</h5></div>
                                </div>
                                <div class="modal-body">
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <form class="row" method="POST" action="/admin/leave/add-news">
                                                @csrf
                                                <div class="form-group col-md-12 col-sm-12">
                                                    <label for="type">วันที่</label>
                                                    <input type="date" name="ns_date" id="start" value="{{$startDate}}" class="form-control">
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <label for="type">ข่าวสารจากบริษัท</label>
                                                    <textarea class="form-control" name="ns_text" id="" cols="30" rows="10"></textarea>
                                                </div>
                                                <div class="form-group col-md-12">
                                                    <button type="submit" class="btn btn-success btn-sm btn-block">เพิ่มข่าวสารจากบริษัท</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal add news -->
                </h4>
            </div>
            <div class="panel-body">
                <div style="height:150px;overflow:auto;">
                    <div style="height:200px;overflow:auto;">
                        <table class="table">
                            <thead style="background: #dff0d8;">
                                <th class="text-center">วันที่</th>
                                <th class="text-center">ข่าวสารจากบริษัท</th>
                                <th class="text-center">แก้ไข</th>
                                <th class="text-center">ลบ</th>
                            </thead>
                            <tbody>
                                @foreach ($all_news as $news)
                                <tr>
                                    <td class="text-center">{{Carbon::parse($news->ns_date)->format('d M Y')}}</td>
                                    <?php
                                    $len_news = mb_strlen($news->ns_text, 'utf-8');
                                    ?>
                                    @if($len_news > 20)
                                        <td><a data-toggle="modal" data-target="#News{{$news->ns_id}}">{{mb_substr($news->ns_text,0,20,'UTF-8')}}...</a></td>
                                        <!-- Modal news -->
                                        <div class="modal fade bd-example-modal-lg" id="News{{$news->ns_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="panel panel-success">
                                                        <div class="panel-heading"><h5>ข่าวสารจากบริษัท</h5></div>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="input-group mb-3">
                                                            <div class="input-group-prepend">
                                                                    <div class="form-group col-md-6 col-sm-12">
                                                                        <label for="type">วันที่</label>
                                                                        {{Carbon::parse($news->ns_date)->format('d M Y')}}
                                                                    </div>
                                                                    <div class="form-group col-md-6">
                                                                        {{$news->ns_text}}
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Modal news -->
                                    @else
                                        <td>{{$news->ns_text}}</td>
                                    @endif
                                @if ($news->ns_date < Carbon::now())
                                    <td colspan="2"></td>
                                @else
                                    <td class="text-center"> <button type="button" class="btn btn-info" data-toggle="modal" data-target="#editNews{{$news->ns_id}}">แก้ไข</button> </td>
                                    <td class="text-center">
                                        <form action="/admin/leave/cancle-news" method="post">
                                            @csrf
                                            <input type="hidden" name="ns_id" value="{{$news->ns_id}}">
                                            <button type="submit" class="btn btn-danger" onclick= "return confirm('ยกเลิกข่าวสาร?');">ยกเลิก</button>
                                        </form>
                                    </td>
                                @endif
                                </tr>
                                <!-- Modal edit news -->
                                <div class="modal fade bd-example-modal-lg" id="editNews{{$news->ns_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="panel panel-success">
                                                <div class="panel-heading"><h5>แก้ไขข่าวสารจากบริษัท</h5></div>
                                            </div>
                                            <div class="modal-body">
                                                <div class="input-group mb-3">
                                                    <div class="input-group-prepend">
                                                        <form class="row" method="POST" action="/admin/leave/edit-news">
                                                            @csrf
                                                            <div class="form-group col-md-6 col-sm-12">
                                                                <label for="type">วันที่</label>
                                                                <input type="hidden" name="ns_id" value="{{$news->ns_id}}">
                                                                <input type="date" name="ns_date" id="start" value="{{$news->ns_date}}" class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label for="type">ข่าวสารจากบริษัท</label>
                                                                <textarea class="form-control" name="ns_text" id="" cols="30" rows="10">{{$news->ns_text}}</textarea>
                                                            </div>
                                                            <div class="form-group col-md-12">
                                                                <button type="submit" class="btn btn-success btn-sm btn-block">แก้ไขข่าวสารจากบริษัท</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal edit news -->
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endif
@stop
@section('js')
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

    <!-- fullCalendar -->
    <script src="https://adminlte.io/themes/AdminLTE/bower_components/moment/moment.js"></script>
    <script src="https://adminlte.io/themes/AdminLTE/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

    <script src="{{ asset('js/locale/th.js') }}"></script>
    {!! $calendar->script()!!}
    <script>
        // $('#choose_emp').select2({
        //     placeholder: 'สามารถพิมพ์ได้'
        // });

    $(document).ready(function() {

        $('#longHoliday').change(function() {
            if (this.checked){
                //  ^
                $('#enddate').fadeIn('slow');
                $('#end').prop("disabled", false);
            }else{
                $('#enddate').fadeOut('slow');
                $('#end').prop("disabled", true);
            }
        });

        $('#swapCalendar').change(function () {
            if (this.checked){
            //  ^
                $('#Leave').fadeOut(1);
                $('#Calendar').addClass('col-md-12');
                $('#Calendar').removeClass('col-md-8');
            }
            else{
                $('#Leave').fadeIn(1);
                $('#Calendar').addClass('col-md-8');
                $('#Calendar').removeClass('col-md-12');
            }
        });

        $(window).load(function(){
            $('#rules').modal('show');
        });
    });

    </script>
@endsection
