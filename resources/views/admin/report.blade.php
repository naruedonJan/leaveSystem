@extends('adminlte::page')

@section('title', 'Digitalmerce || รายงาน')

@section('content')
@php
    use Illuminate\Http\Request;
    use Carbon\Carbon;
@endphp

@if (!empty($leaveEmp))
    @php
        $leaveEmp = 'active in';
        $leaveHistory = '';
        $infoEmp = '';
    @endphp
@elseif(!empty($leaveHistory))
    @php
        $leaveEmp = '';
        $leaveHistory = 'active in';
        $infoEmp = '';
    @endphp
@else
    @php
        $leaveEmp = '';
        $leaveHistory = '';
        $infoEmp = 'active in';
    @endphp
@endif
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h4>รายงาน</h4>
        </div>
        <div class="panel-body">
            <div class="container-fluid" >
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item {{ $infoEmp }}">
                        <a class="nav-link" id="pills-infoEmp-tab" data-toggle="pill" href="#pills-infoEmp" role="tab" aria-controls="pills-infoEmp" aria-selected="true">ข้อมูลพนักงาน</a>
                    </li>
                    <li class="nav-item {{ $leaveHistory }}">
                        <a class="nav-link" id="pills-leaveHistory-tab" data-toggle="pill" href="#pills-leaveHistory" role="tab" aria-controls="pills-leaveHistory" aria-selected="false">ประวัติการลา</a>
                    </li>
                    <li class="nav-item {{ $leaveEmp }}">
                        <a class="nav-link" id="pills-leaveEmp-tab" data-toggle="pill" href="#pills-leaveEmp" role="tab" aria-controls="pills-leaveEmp" aria-selected="false">สรุปการลา</a>
                    </li>
                </ul>
                <div class="tab-content" id="pills-tabContent">
                    {{-- info employee  --}}
                    <div class="tab-pane fade {{ $infoEmp }}" id="pills-infoEmp" role="tabpanel" aria-labelledby="pills-infoEmp-tab">
                        @foreach ($countEmp as $item)
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    แผนก <b>{{$item->dep_name}} </b>
                                </div>
                                <div class="panel-body">
                                    <table class="table">
                                        <thead>
                                            <th class="text-center" style="width: 20%;">ชื่อ</th>
                                            <th class="text-center" style="width: 10%;">ชื่อเล่น</th>
                                            <th class="text-center" style="width: 10%;">วันเกิด</th>
                                            <th class="text-center" style="width: 20%;">เลขบัญชี</th>
                                            <th class="text-center" style="width: 10%;">วันเริ่มงาน</th>
                                            <th class="text-center" style="width: 20%;">อายุงาน</th>
                                        </thead>
                                        <tbody>
                                            @foreach ($employees as $employee)
                                            @if ($item->dept_id == $employee->dept_id)
                                            @php
                                                $start = date_create($employee->emp_start_date);
                                                $today = date_create(Carbon::now());
                                                $dateDiff = $today->diff($start);
                                                $diff = $dateDiff->days;
                                                $years = floor($diff/365);
                                                $months = floor(($diff-($years*365))/30);
                                                $dates = $diff-(($years*365)+($months*30));
                                            @endphp
                                                <tr>
                                                    <td class="text-center">{{ $employee->title_th }} {{ $employee->name_th }}  {{ $employee->surname_th }}</td>
                                                    <td class="text-center">{{ $employee->nickname }}</td>
                                                    @if(Carbon::parse($employee->birthdate)->format('m-d') == Carbon::parse(Carbon::now())->format('m-d'))
                                                        <td class="text-center" style="background: #00c0ef">{{ Carbon::parse($employee->birthdate)->format('d-m-Y') }}</td>
                                                    @elseif(Carbon::parse($employee->birthdate)->format('m-d') > Carbon::parse(Carbon::now())->format('m-d'))
                                                        <td class="text-center" style="background: #dff0d8">{{ Carbon::parse($employee->birthdate)->format('d-m-Y') }}</td>
                                                    @else
                                                        <td class="text-center">{{ Carbon::parse($employee->birthdate)->format('d-m-Y') }}</td>
                                                    @endif
                                                    <td class="text-center">{{ $employee->bank_no }}</td>
                                                    <td class="text-center">{{ Carbon::parse($employee->emp_start_date)->format('d-m-Y') }}</td>
                                                    <td class="text-center">{{ $years }} ปี {{ $months }} เดือน {{ $dates }} วัน </td>
                                                </tr>
                                            @endif
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- leave history  --}}
                    <div class="tab-pane fade {{ $leaveHistory }}" id="pills-leaveHistory" role="tabpanel" aria-labelledby="pills-leaveHistory-tab">
                        <form action="/admin/report" method="post" style="width: 100%; display: flex; margin-bottom:10px;">
                        @csrf
                        <input type="hidden" name="leaveHistory" value="leaves-his">
                            <select name="emp_id" class="form-control" style="width: 90%;">
                                <option value="">เลือกพนักงาน</option>
                                @foreach ($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->nickname }}</option>
                                @endforeach
                            </select>
                            <input type="date" name="date_rep" class="form-control">
                            <button type="submit" class="btn btn-success" style="width: 10%;"> ยืนยัน </button>
                        </form>
                        @php
                            $thai_month_arr=array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
                        @endphp
                        <select name="month" id="month" class="form-control" style="width: 100%;" onchange="hideMonth(this.value)">
                            <option value="">เลือกเดือน</option>
                            <option value="1">มกราคม</option>
                            <option value="2">กุมภาพันธ์</option>
                            <option value="3">มีนาคม</option>
                            <option value="4">เมษายน</option>
                            <option value="5">พฤษภาคม</option>
                            <option value="6">มิถุนายน</option>
                            <option value="7">กรกฎาคม</option>
                            <option value="8">สิงหาคม</option>
                            <option value="9">กันยายน</option>
                            <option value="10">ตุลาคม</option>
                            <option value="11">พฤศจิกายน</option>
                            <option value="12">ธันวาคม</option>
                        </select>
                        @for ($month = 12; $month > 0 ; $month--)
                        <div id="{{$month}}" class="{{$month}}">
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    ประวัติการลา เดือน{{ $thai_month_arr[$month]}}
                                </div>
                                <div class="panel-body">
                                    <table class="table">
                                        <thead>
                                            <th class="text-center" style="width: 20%;">ชื่อ</th>
                                            <th class="text-center" style="width: 10%;">ชื่อเล่น</th>
                                            <th class="text-center" style="width: 10%;">ประเภท</th>
                                            <th class="text-center" style="width: 10%;">จำนวน</th>
                                            <th class="text-center" style="width: 10%;">วันลา</th>
                                            <th class="text-center" style="width: 10%;">วันที่บันทึก</th>
                                        </thead>
                                        <tbody>
                                        @foreach ($leaves as $leave)
                                            @if ($leave->type == '1')
                                                @php
                                                    $leave_type = 'ทั้งวัน'
                                                @endphp
                                            @else
                                                @php
                                                    $leave_type = 'ครึ่งวัน'
                                                @endphp
                                            @endif
                                            @if(Carbon::parse($leave->start_date)->format('m') == $month)
                                                @if($leave->status === 2)
                                                    <tr style="color: red;"> 
                                                        <td class="text-center">{{ $leave->title_th }} {{ $leave->name_th }}  {{ $leave->surname_th }}</td>
                                                        <td class="text-center">{{ $leave->nickname }}</td>
                                                        <td class="text-center">{{ $leave->name }}</td>
                                                        <td class="text-center">{{ $leave_type }}</td>
                                                        {{-- @if(Carbon::parse($leave->birthdate)->format('m-d') >= Carbon::parse(Carbon::now())->format('m-d')) --}}
                                                            {{-- <td class="text-center" style="background: #dff0d8">{{ Carbon::parse($leave->birthdate)->format('d-m-Y') }}</td> --}}
                                                        {{-- @else --}}
                                                            <td class="text-center">{{ Carbon::parse($leave->start_date)->format('d-m-Y') }} - {{ Carbon::parse($leave->end_date)->format('d-m-Y') }}</td>
                                                        {{-- @endif --}}
                                                        <td class="text-center">{{ Carbon::parse($leave->ev_created_at)->format('d-m-Y') }}</td>
                                                    </tr>
                                                @else
                                                    <tr> 
                                                        <td class="text-center">{{ $leave->title_th }} {{ $leave->name_th }}  {{ $leave->surname_th }}</td>
                                                        <td class="text-center">{{ $leave->nickname }}</td>
                                                        <td class="text-center">{{ $leave->name }}</td>
                                                        <td class="text-center">{{ $leave_type }}</td>
                                                        {{-- @if(Carbon::parse($leave->birthdate)->format('m-d') >= Carbon::parse(Carbon::now())->format('m-d')) --}}
                                                            {{-- <td class="text-center" style="background: #dff0d8">{{ Carbon::parse($leave->birthdate)->format('d-m-Y') }}</td> --}}
                                                        {{-- @else --}}
                                                            <td class="text-center">{{ Carbon::parse($leave->start_date)->format('d-m-Y') }} - {{ Carbon::parse($leave->end_date)->format('d-m-Y') }}</td>
                                                        {{-- @endif --}}
                                                        <td class="text-center">{{ Carbon::parse($leave->ev_created_at)->format('d-m-Y') }}</td>
                                                    </tr>
                                                @endif
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endfor
                    </div>

                    <div class="tab-pane fade {{ $leaveEmp }}" id="pills-leaveEmp" role="tabpanel" aria-labelledby="pills-leaveEmp-tab">
                        <form action="" method="post"  style="width: 100%; display: flex;" >
                        @csrf
                            <input type="hidden" name="leaveEmp" value="leaves">
                            <select name="year" style="width: 90%;">
                                <option value="{{ $year }}">{{ $year }}</option>
                                <option value="{{ Carbon::parse(Carbon::now())->format('Y') }}">{{ Carbon::parse(Carbon::now())->format('Y') }}</option>
                                <option value="{{ Carbon::parse(Carbon::now())->sub(1, 'years')->format('Y') }}">{{ Carbon::parse(Carbon::now())->sub(1, 'years')->format('Y') }}</option>
                                <option value="{{ Carbon::parse(Carbon::now())->sub(2, 'years')->format('Y') }}">{{ Carbon::parse(Carbon::now())->sub(2, 'years')->format('Y') }}</option>
                                <option value="{{ Carbon::parse(Carbon::now())->sub(3, 'years')->format('Y') }}">{{ Carbon::parse(Carbon::now())->sub(3, 'years')->format('Y') }}</option>
                            </select>
                            <button type="submit" class="btn btn-success" style="width: 10%;"> ยืนยัน </button>
                        </form>
                        @foreach ($countEmp as $item)
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    แผนก <b>{{$item->dep_name}} </b>
                                </div>
                                <div class="panel-body">
                                    <table class="table">
                                        <thead>
                                            <th class="text-center" style="width: 20%;">ชื่อ</th>
                                            <th class="text-center" style="width: 10%;">ชื่อเล่น</th>
                                            <th class="text-center" style="width: 10%;">ลากิจ</th>
                                            <th class="text-center" style="width: 10%;">ลาป่วย</th>
                                            <th class="text-center" style="width: 10%;">หยุดประจำสัปดาห์</th>
                                            <th class="text-center" style="width: 10%;">ลาบวช </th>
                                            <th class="text-center" style="width: 10%;">ลาคลอด </th>
                                            <th class="text-center" style="width: 10%;">หยุดกรณีพิเศษ </th>
                                            <th class="text-center" style="width: 10%;">เรียน </th>
                                        </thead>
                                        <tbody>
                                                @php
                                                    $num = 0;
                                                @endphp
                                            @foreach ($employees as $employee)
                                            @if ($item->dept_id == $employee->dept_id)
                                                <tr>
                                                    <td class="text-center">{{ $employee->title_th }} {{ $employee->name_th }}  {{ $employee->surname_th }}</td>
                                                    <td class="text-center">{{ $employee->nickname }}</td>

                                                    {{-- ลากิจ  --}}
                                                    @php
                                                        $sumerrand_leave = 0
                                                    @endphp
                                                    @foreach ($errand_leaves as $errand_leave)
                                                        @if ($employee->id == $errand_leave->uid)
                                                            @php
                                                                $sumerrand_leave = $errand_leave->count_date
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @foreach ($herrand_leaves as $herrand_leave)
                                                        @if ($employee->id == $herrand_leave->uid)
                                                            @php
                                                                $sumerrand_leave = $sumerrand_leave+($herrand_leave->count_date/2)
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    {{-- ลาป่วย  --}}
                                                    @php
                                                        $sumsick_leave = 0
                                                    @endphp
                                                    @foreach ($sick_leaves as $sick_leave)
                                                        @if ($employee->id == $sick_leave->uid)
                                                            @php
                                                                $sumsick_leave = $sick_leave->count_date
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @foreach ($hsick_leaves as $hsick_leave)
                                                        @if ($employee->id == $hsick_leave->uid)
                                                            @php
                                                                $sumsick_leave = $sumsick_leave+($hsick_leave->count_date/2)
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    {{-- หยุดประจำสัปดาห์  --}}
                                                    @php
                                                        $sumweek_leave = 0
                                                    @endphp
                                                    @foreach ($week_leaves as $week_leave)
                                                        @if ($employee->id == $week_leave->uid)
                                                            @php
                                                                $sumweek_leave = $week_leave->count_date
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @foreach ($hweek_leaves as $hweek_leave)
                                                        @if ($employee->id == $hweek_leave->uid)
                                                            @php
                                                                $sumweek_leave = $sumweek_leave+($hweek_leave->count_date/2)
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    {{-- ลาบวช  --}}
                                                    @php
                                                        $sumordained_leave = 0
                                                    @endphp
                                                    @foreach ($ordained_leaves as $ordained_leave)
                                                        @if ($employee->id == $ordained_leave->uid)
                                                            @php
                                                                $sumordained_leave = $ordained_leave->count_date
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @foreach ($hordained_leaves as $hordained_leave)
                                                        @if ($employee->id == $hordained_leave->uid)
                                                            @php
                                                                $sumordained_leave = $sumordained_leave+($hordained_leave->count_date/2)
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    {{-- ลาคลอด  --}}
                                                    @php
                                                        $summaternity_leave = 0
                                                    @endphp
                                                    @foreach ($maternity_leaves as $maternity_leave)
                                                        @if ($employee->id == $maternity_leave->uid)
                                                            @php
                                                                $summaternity_leave = $maternity_leave->count_date
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @foreach ($hmaternity_leaves as $hmaternity_leave)
                                                        @if ($employee->id == $hmaternity_leave->uid)
                                                            @php
                                                                $summaternity_leave = $summaternity_leave+($hmaternity_leave->count_date/2)
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    {{-- ลากรณีพิเศษ  --}}
                                                    @php
                                                        $sumspecial_leave = 0
                                                    @endphp
                                                    @foreach ($special_leaves as $special_leave)
                                                        @if ($employee->id == $special_leave->uid)
                                                            @php
                                                                $sumspecial_leave = $special_leave->count_date
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @foreach ($hspecial_leaves as $hspecial_leave)
                                                        @if ($employee->id == $hspecial_leave->uid)
                                                            @php
                                                                $sumspecial_leave = $sumspecial_leave+($hspecial_leave->count_date/2)
                                                            @endphp
                                                        @endif
                                                    @endforeach

                                                    {{-- ลาไปเรียน  --}}
                                                    @php
                                                        $sumlearn_leave = 0
                                                    @endphp
                                                    @foreach ($learn_leaves as $learn_leave)
                                                        @if ($employee->id == $learn_leave->uid)
                                                            @php
                                                                $sumlearn_leave = $learn_leave->count_date
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @foreach ($hlearn_leaves as $hlearn_leave)
                                                        @if ($employee->id == $hlearn_leave->uid)
                                                            @php
                                                                $sumlearn_leave = $sumlearn_leave+($hlearn_leave->count_date/2)
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    @if ($sumerrand_leave > $employee->emp_limitErrand)
                                                        @php
                                                            $limit = 'red';
                                                        @endphp
                                                    @else
                                                        @php
                                                            $limit = '#000';
                                                        @endphp
                                                    @endif
                                                    <td class="text-center" style="color: {{ $limit }}">{{ $sumerrand_leave }} / {{ $employee->emp_limitErrand }}</td>
                                                    <td class="text-center">{{ $sumsick_leave }} </td>
                                                    <td class="text-center">{{ $sumweek_leave }} </td>
                                                    <td class="text-center">{{ $sumordained_leave }}</td>
                                                    <td class="text-center">{{ $summaternity_leave }}</td>
                                                    <td class="text-center">{{ $sumspecial_leave }}</td>
                                                    <td class="text-center">{{ $sumlearn_leave }}</td>
                                                </tr>
                                            @endif
                                            @php
                                                $num++;
                                            @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
function hideMonth(val) {
    for (i = 1; i <= 12; i++) {
        if (i == val) {
            $('#'+i).show();
        }
        else{
            $('#'+i).hide();
        }
    }
}
</script>
@stop
