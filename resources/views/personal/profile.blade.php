@extends('adminlte::page')

@section('title', 'Digitalmerce || ประวัติการลา')
@php
use Carbon\Carbon;

@endphp


@section('content')

    <div class="panel panel-success">
        <div class="panel-heading col-12" style="display: flex;">
            <h4 class="col-md-10">ข้อมูลส่วนตัว</h4>
        @if (Auth::user()->permission == 'employee')
            <a href="edit-profile" class="btn btn-success col-md-2" style="display: flex; justify-content: center; align-items: center;">แก้ไขข้อมูลส่วนตัว</a>
            @if(empty(auth()->user()->employee(Auth::user()->emp_id)->lineUUID))
            <a href="{{$linkLine}}&state={{ Auth::user()->emp_id }}" class="btn btn-success col-md-2" style="margin-left: 10px; display: flex; justify-content: center; align-items: center;">เชื่อมต่อ Line</a>
            @else
            <a href="/chatbot/disconnecttline?emp_id={{ Auth::user()->emp_id }}" class="btn btn-success col-md-2" style="margin-left: 10px; display: flex; justify-content: center; align-items: center;">ยกเลิกเชื่อต่อ Line</a>
            @endif
        @else
            <a href="/leave/edit-profile?emp_id={{ $_GET['emp_id'] }}" class="btn btn-warning col-md-1" style="margin-right: 10px;"><i class="fas fa-user-cog"></i></a>
            <a href="accept/{{$_GET['user_id']}}?status=disallow" class='btn btn-danger col-md-1' onclick= "return confirm('ยืนยัน?');"><i class="fas fa-user-times"></i></a>
            
        @endif
        </div>
        <div class="panel-body">

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="col-md-6 col-sm-12">
                        <h4>รูปโปรไฟล์</h4>
                        @if (!empty($_GET['emp_id']))
                            <img src="{{url('storage/profile/'.auth()->user()->employee($_GET['emp_id'])->photo)}}" style="max-width: 250px;max-height: 250px;">
                        @else
                            <img src="{{url('storage/profile/'.auth()->user()->employee(Auth::user()->emp_id)->photo)}}" style="max-width: 250px;max-height: 250px;">
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h4>รูปบัตรประชาชน</h4>
                        @if (!empty($_GET['emp_id']))
                            <img src="{{url('storage/id_card/'.auth()->user()->employee($_GET['emp_id'])->emp_idCard)}}" style="max-width: 250px;max-height: 250px;">
                        @else
                            <img src="{{url('storage/id_card/'.auth()->user()->employee(Auth::user()->emp_id)->emp_idCard)}}" style="max-width: 250px;max-height: 250px;">
                        @endif
                        <h4>เลขประจำตัวประชาชน : {{$employee->card_id}}</h4>
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="col-md-12">
                        <h4>ชื่อ-นามสกุล (ภาษาไทย) : {{$employee->title_th}} {{$employee->name_th}} {{$employee->surname_th}}</h4>
                    </div>
                    <div class="col-md-12">
                        <h4>ชื่อ-นามสกุล (ภาษาอังกฤษ) : {{$employee->title_en}} {{$employee->name_en}} {{$employee->surname_en}}</h4>
                    </div>
                    <div class="col-md-12">
                        <h4>ชื่อเล่น : {{$employee->nickname}}</h4>
                    </div>
                    <div class="col-md-12">
                        <h4>เบอร์โทรศัพท์ : {{$employee->mobile}}</h4>
                    </div>
                    <div class="col-md-12">
                        <h4>อีเมลล์ : {{$employee->email}}</h4>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h4>เพศ : {{$employee->gender}}</h4>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h4>วันเกิด : {{Carbon::parse($employee->birthdate)->format('d M Y')}}</h4>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h4>สัญชาติ : {{$employee->nationality}}</h4>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h4>ศาสนา : {{$employee->religion}}</h4>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h4>สถานะภาพ : {{$employee->marital_status}}</h4>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h4>การรับราชการทหาร : {{$employee->soldier}}</h4>
                    </div>
                    <div class="col-md-12">
                        <h4>ที่อยู่ : {{$employee->address}} {{$employee->DISTRICT_NAME}}  {{$employee->AMPHUR_NAME}}  {{$employee->PROVINCE_NAME}} {{$employee->zipcode}}</h4>
                    </div>
                    <div class="col-md-12">
                        <h4>ข้อมูลทางการเงิน :
                            <img src="https://kasikornbank.com/SiteCollectionDocuments/about/img/logo/logo.png" width="30">
                            : {{$employee->bank_no}}
                        </h4>
                    </div>
                </div>
            </div>

            <div class="row">
            </div>

            <hr>
            <div class="row">
                <div class="col-sm-4">
                    <h4>ลากิจ : @if(!empty($leaveResult1)) {{$leaveResult1}} @else 0 @endif / {{$employee->emp_limitErrand}} วัน</h4>
                </div>
                <div class="col-sm-4">
                    <h4>ลาป่วย : @if(!empty($leaveResult2)) {{$leaveResult2}} @else 0 @endif/30 วัน</h4>
                </div>
                <div class="col-sm-4">
                    <h4>หยุดประจำสัปดาห์ : @if(!empty($leaveResult3)) {{$leaveResult3}} @else 0 @endif วัน</h4>
                </div>
                <div class="col-sm-4">
                    <h4>ลาบวช : @if(!empty($leaveResult4)) {{$leaveResult4}} @else 0 @endif วัน</h4>
                </div>
                <div class="col-sm-4">
                    <h4>ลาคลอด : @if(!empty($leaveResult5)) {{$leaveResult5}} @else 0 @endif วัน</h4>
                </div>
                <div class="col-sm-4">
                    <h4>หยุดกรณีพิเศษ : @if(!empty($leaveResult6)) {{$leaveResult6}} @else 0 @endif  วัน</h4>
                </div>
            </div>

            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h4>ข้อมูลการทำงาน</h4>
                </div>

                <div class="col-md-6 col-sm-12">
                    <h4>แผนก : {{$employee->position}}</h4>
                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>ประเภทการจ้าง : {{$employee->emp_type}}</h4>
                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>วันเริ่มงาน : {{$employee->emp_start_date}}</h4>
                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>เงินเดือน : {{$employee->salary}}</h4>
                </div>
            </div>

            <hr>
            <div class="panel panel-success">
                <div class="panel-heading col-12" style="display: flex;">
                    <h4 class="col-md-12">วันลา</h4>
                </div>
                <div class="panel-body">

                    <div class="row">
                        @foreach ($events as $event)                            
                            <div class="col-md-3">
                                <div class="panel panel-info">
                                    <div class="panel-heading col-12" style="display: flex;">
                                        <h4 class="col-md-12 text-center">{{$event->name}}</h4>
                                    </div>
                                    <div class="panel-body">
                                        <h4 class="col-md-6">{{$event->start_date}}</h4><h4 class="col-md-6">{{$event->note}}</h4>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>

                </div>
            </div>
    </div>



@stop
@section('js')

@endsection
