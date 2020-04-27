@extends('adminlte::page')

@section('title', 'Digitalmerce || ประวัติการลา')
@php
use Carbon\Carbon;

@endphp
@section('content_header')
    <h1>ประวัติการลา  </h1>
@stop

@section('content')

    <div class="panel panel-success">
        <div class="panel-heading"><h4>สรุปวันลาในปีนี้</h4><p>รวมทั้งหมด : @if(!empty($leaveResult)) {{$leaveResult}} @else 0 @endif วัน</p></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-sm-4">
                    <p>ลากิจ : @if(!empty($leaveResult1)) {{$leaveResult1}} @else 0 @endif/{{$emp[0]->emp_limitErrand}} วัน</p>
                </div>
                <div class="col-sm-4">
                    <p>ลาป่วย : @if(!empty($leaveResult2)) {{$leaveResult2}} @else 0 @endif/30 วัน</p>
                </div>
                <div class="col-sm-4">
                    <p>หยุดประจำสัปดาห์ : @if(!empty($leaveResult3)) {{$leaveResult3}} @else 0 @endif วัน</p>
                </div>
                <div class="col-sm-4">
                    <p>ลาบวช : @if(!empty($leaveResult4)) {{$leaveResult4}} @else 0 @endif วัน</p>
                </div>
                <div class="col-sm-4">
                    <p>ลาคลอด : @if(!empty($leaveResult5)) {{$leaveResult5}} @else 0 @endif วัน</p>
                </div>
                <div class="col-sm-4">
                    <p>หยุดกรณีพิเศษ : @if(!empty($leaveResult6)) {{$leaveResult6}} @else 0 @endif  วัน</p>
                </div>
            </div>
        </div>
    </div>


    <div class="panel panel-primary">
        <div class="panel-heading"><h4>ประวัติการลา</h4></div>
        <div class="panel-body">
            <table class="table table-hover table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">ประเภท</th>
                        <th scope="col">ช่วงเวลา</th>
                        <th scope="col">จากวันที่</th>
                        <th scope="col">ถึงวันที่</th>
                        <th scope="col">หมายเหตุ</th>
                        <th scope="col">สถานะ</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $event_count = 0 ; ?>
                    @foreach ($leaves as $l => $leave)
                        <?php $event_count++ ; ?>                        <tr>
                            <td scope="row">{{$l+$leaves->firstItem()}}</td>
                            <td>{{$leave->tname}}</td>
                            <td>@if($leave->type == '1') ทั้งวัน @else ครึ่งวัน @endif</td>
                            <td>{{Carbon::parse($leave->start_date)->format('d/m/Y')}}</td>
                            <td>{{Carbon::parse($leave->end_date)->format('d/m/Y')}}</td>
                            <td>{{$leave->note}}</td>
                            <td>@if($leave->status == '1') อนุมัติ @else @if($leave->status == '0') รออนุมัติ @else ไม่อนุมัติ @endif @endif</td>
                            <td>

                                @if ($leave->start_date >= Carbon::now() || $leave->status == '0')
                                <div style="display: flex;">
                                    <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#exampleModal{{ $leave->id }}" style="margin-right: 5px;">แก้ไข</button>
                                    <form action="/leave/cancle-calender" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{$leave->id}}">
                                        <button type="submit" class="btn btn-danger" onclick= "return confirm('ยกเลิกวันหยุด?');">ยกเลิก</button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>

                        <!-- Modal edit event -->
                        <div class="modal fade bd-example-modal-lg" id="exampleModal{{ $leave->id }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                <div class="panel panel-info">
                                    <div class="panel-heading"><h5>แก้ไขการลา</h5></div>
                                </div>
                                <div class="modal-body">

                                        <div class="input-group mb-3">
                                            <div class="input-group-prepend">
                                                <form class="row" method="POST" action="/leave/edit-calender">
                                                    @csrf
                                                    <input type="hidden" name="id" value="{{$leave->id}}">
                                                    <input type="hidden" name="dept_id" value="{{Auth::user()->department}}" class="form-control">
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <label for="reason">ประเภทการลา</label>
                                                        <select name="reason" id="reason" class="form-control">
                                                            <option value="{{$leave->reason}}">{{$leave->tname}}</option>
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
                                                            @if($leave->type == 1.0)
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
                                                        <input type="date" name="start" id="start" value="{{$leave->start_date}}" class="form-control">
                                                    </div>
                                                    <div class="form-group col-md-6 col-sm-12">
                                                        <input type="checkbox" name="" onchange="myFunction({{ $event_count }})" class="longHoliday">
                                                        <label for="type">ต้องการหยุดต่อเนื่อง</label>
                                                    </div>
                                                    <div class="form-group col-md-12 col-sm-12" id="enddate{{ $event_count }}" style="display: none;">
                                                        <label for="type">ถึง</label>
                                                        <input type="date" name="end" id="end" value="{{$leave->end_date}}" class="form-control" disabled>
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <label for="type">หมายเหตุ</label>
                                                        <textarea name="note" id="note" class="form-control">{{$leave->note}}</textarea>
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
                    <?php $sum_event = $event_count; ?>
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
    <script>
    // var sum_event = "<?php echo $sum_event; ?>";
    // $(document).ready(function() {

    //     $('#longHoliday'+sum_event).change(function() {
    //         if (this.checked){
    //             //  ^
    //             $('#enddate').fadeIn('slow');
    //             $('#end').prop("disabled", false);
    //         }else{
    //             $('#enddate').fadeOut('slow');
    //             $('#end').prop("disabled", true);
    //         }
    //     });
    // });
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
    </script>
@endsection

