@extends('adminlte::page')

@section('title', 'Digitalmerce || อนุมัติการลา')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading"><h4>ประวัติการลา</h4></div>
        <div class="panel-body" style="overflow: auto;">
            <form action="/admin/history-leave" method="post" style="width: 100%; display: flex; margin-bottom:10px;">
            @csrf
            <input type="hidden" name="leaveHistory" value="leaves-his">
                <select name="emp_id" class="form-control" style="width: 90%;" id="name">
                    <option value="">เลือกพนักงาน</option>
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->nickname }}</option>
                    @endforeach
                </select>
                <input type="date" name="date_rep" class="form-control">
                <button type="submit" class="btn btn-success" style="width: 10%;"> ยืนยัน </button>
            </form>
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" width="3%">#</th>
                        <th class="text-center" width="5%">ชื่อ</th>
                        <th class="text-center" width="10%">วันที่ลา</th>
                        <th class="text-center" width="5%">ช่วงเวลา</th>
                        <th class="text-center" width="5%">ประเภท</th>
                        {{-- <th>จำนวนครั้ง</th> --}}
                        <th class="text-center" width="15%">เหตุผล</th>
                        <th  class="text-center"width="5%">เวลาที่ลงระบบ</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $event_count = 0 ; ?>
                    @foreach ($events as $key => $event)
                        <?php $event_count++ ; ?> 
                        <tr id="{{ $event->id }}">
                            <td scope="row" class="text-center">{{ $key+$events->firstItem()}}</td>
                            <td class="text-center">{{ $event->name }}</td>
                            <td class="text-center">  @if($event->start_date == $event->end_date) {{ Carbon::parse($event->start_date)->format('d-M-Y') }} @else {{ Carbon::parse($event->start_date)->format('d-M-Y') }} ถึง {{ Carbon::parse($event->end_date)->format('d-M-Y') }} @endif </td>
                            <td class="text-center">@if($event->type == '1') ทั้งวัน @else ครึ่งวัน @endif</td>
                            <td class="text-center">{{ $event->leave_type }}</td>
                            {{-- <td>{{ $event->uid }}</td> --}}
                            <td class="text-center">{{ $event->note }}</td>
                            <td class="text-center">{{ $event->send_date }}</td>
                            <td class="text-center">
                                <div style="display: flex;">
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal{{ $event->id }}" style="margin-left: auto; margin-right: 5px; padding-top: 0px; padding-bottom: 0px; height: 35px;">แก้ไข</button>
                                    <form action="/admin/cancle-calender" method="post"  style="margin-right: auto;">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$event->id}}">
                                        <button type="submit" class="btn btn-danger" onclick= "return confirm('ยกเลิกวันหยุด?');">ยกเลิก</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        <!-- Modal edit event -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModal{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                <div class="panel panel-info">
                                    <div class="panel-heading"><h5>แก้ไขการลา</h5></div>
                                </div>
                                <div class="modal-body">

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <form class="row" method="POST" action="/admin/edit-calender">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$event->id}}">
                                                    <input type="hidden" name="dept_id" value="{{Auth::user()->department}}" class="form-control">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="reason">ประเภทการลา</label>
                                                        <select name="reason" id="reason" class="form-control">
                                                            <option value="{{$event->reason}}">{{$event->leave_type}}</option>
                                                            @foreach ($eventtype as $type)
                                                                <option value="{{$type->id}}">{{$type->name}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="type">ช่วงเวลา</label>
                                                        <select name="type" id="type" class="form-control">
                                                            @if($event->type == 1.0)
                                                            <option value="1.0">ทั้งวัน</option>
                                                            <option value="0.5">ครึ่งวัน</option>
                                                            @else
                                                            <option value="0.5">ครึ่งวัน</option>
                                                            <option value="1.0">ทั้งวัน</option>
                                                            @endif
                                                        </select>
                                                    </div>
                                                    <div class="form-group col-md-12 col-sm-12">
                                                        <label for="type">ตั้งแต่</label>
                                                        <input type="date" name="start" id="start" value="{{$event->start_date}}" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input type="checkbox" name="" onchange="myFunction({{ $event_count }})" class="longHoliday">
                                                        <label for="type">ต้องการหยุดต่อเนื่อง</label>
                                                    </div>
                                                    <div class="form-group col-md-12 col-sm-12" id="enddate{{ $event_count }}" style="display: none;">
                                                        <label for="type">ถึง</label>
                                                        <input type="date" name="end" id="end" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="type">หมายเหตุ</label>
                                                        <textarea name="note" id="note" class="form-control">{{$event->note}}</textarea>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <button type="submit" class="btn btn-success btn-sm btn-block">บันทึก</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Modal edit event -->
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@stop

@section('js')
    <!-- fullCalendar -->
<script src="https://adminlte.io/themes/AdminLTE/bower_components/moment/moment.js"></script>
<script src="https://adminlte.io/themes/AdminLTE/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>
<script src="{{ asset('js/locale/th.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>
    function myFunction(val) {
        var acc = document.getElementsByClassName("longHoliday");
        var i;
        for (i = 0; i < acc.length; i++) {

            if (i === val){
                $('#enddate'+i).fadeIn('slow');
                $('#end').prop("disabled", false);
            }
        }
    }
    
    $("#name").select2();
</script>
@endsection
