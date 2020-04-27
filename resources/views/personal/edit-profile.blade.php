@extends('adminlte::page')

@section('title', 'Digitalmerce || ประวัติการลา')
@php
use Carbon\Carbon;

@endphp


@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
    <div class="panel panel-success">
        <div class="panel-heading col-12" style="display: flex;">
            <h4 class="col-md-10">ข้อมูลส่วนตัว</h4>
        @if (Auth::user()->permission == 'employee')
            <a href="/leave/profile" class="btn btn-danger col-md-2">ย้อนกลับ</a>
        @else
            <a href="/admin/employee" class="btn btn-danger col-md-2">ย้อนกลับ</a>
        @endif
        </div>
        <div class="panel-body">
            <form action="/leave/edit-profile" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" class="form-control" value="{{$employee->id}}">

            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <div class="col-md-6 col-sm-12">
                        <h4>รูปโปรไฟล์</h4>
                        @if (!empty($_GET['emp_id']))
                            <img src="{{url('storage/profile/'.auth()->user()->employee($_GET['emp_id'])->photo)}}" style="max-width: 250px;max-height: 250px;">
                            <input type="file" name="profile-img" class="form-control">
                            <input type="text" name="old-profile-img" class="form-control" value="{{$employee->photo}}">
                        @else
                            <img src="{{url('storage/profile/'.auth()->user()->employee(Auth::user()->emp_id)->photo)}}" style="max-width: 250px;max-height: 250px;">
                            <input type="file" name="profile-img" class="form-control">
                            <input type="text" name="old-profile-img" class="form-control" value="{{$employee->photo}}">
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <h4>รูปบัตรประชาชน</h4>
                        @if (!empty($_GET['emp_id']))
                            <img src="{{url('storage/id_card/'.auth()->user()->employee($_GET['emp_id'])->emp_idCard)}}" style="max-width: 250px;max-height: 250px;">
                            <input type="file" name="idcard-img" class="form-control">
                            <input type="text" name="old-idcard-img" class="form-control" value="{{$employee->emp_idCard}}">
                        @else
                            <img src="{{url('storage/id_card/'.auth()->user()->employee(Auth::user()->emp_id)->emp_idCard)}}" style="max-width: 250px;max-height: 250px;">
                            <input type="file" name="idcard-img" class="form-control">
                            <input type="text" name="old-idcard-img" class="form-control" value="{{$employee->emp_idCard}}">
                        @endif
                        <h4>เลขประจำตัวประชาชน : </h4>
                        <input type="text" name="card_id" class="form-control" value="{{$employee->card_id}}">
                    </div>
                </div>
                <div class="col-md-6 col-sm-12">
                    <div class="col-md-12">
                        <h4 class="col-md-12">ชื่อ-นามสกุล (ภาษาไทย) </h4>
                    </div>
                    <div class="col-md-12" style="display:flex;">
                        <select name="title_th" class="col-md-1 form-control"  style="width:20%;">
                            <option value="{{$employee->title_th}}">{{$employee->title_th}}</option>
                            <option value="นาย">นาย</option>
                            <option value="นางสาว">นางสาว</option>
                            <option value="นาง">นาง</option>
                        </select>
                        <input type="text" name="name_th" class="col-md-4 form-control" value="{{$employee->name_th}}"  style="width:40%;">
                        <input type="text" name="surname_th" class="col-md-4 form-control" value="{{$employee->surname_th}}" style="width:40%;">
                    </div>
                    <div class="col-md-12">
                        <h4>ชื่อ-นามสกุล (ภาษาอังกฤษ)</h4>
                    </div>
                    <div class="col-md-12" style="display:flex;">
                        <select name="title_en" class="form-control" style="width:20%;">
                            <option value="{{$employee->title_en}}">{{$employee->title_en}}</option>
                            <option value="Mr.">Mr</option>
                            <option value="MISS">MISS</option>
                            <option value="Ms.">Ms.</option>
                        </select>
                        <input type="text" name="name_en" class="form-control" value="{{$employee->name_en}}" style="width:40%;">
                        <input type="text" name="surname_en" class="form-control" value="{{$employee->surname_en}}" style="width:40%;">
                    </div>
                    {{-- <div class="col-md-12" style="display:flex;">
                        <h4 style="width: 10%;">แผนก : </h4>
                        <select name="" id="" class="form-control" style="width: 90%;">
                            <option value="{{$employee->dept_id}}">{{$employee->position}}</option>
                            @foreach ($departments as $department)
                                <option value="{{ $department->dept_id }}">{{ $department->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <div class="col-md-12" style="display:flex;">
                        <h4 style="width: 20%;">ชื่อเล่น : </h4>
                        <input type="text" name="nickname" class="form-control" value="{{$employee->nickname}}" style="width: 80%;">
                    </div>
                    <div class="col-md-12" style="display:flex;">
                        <h4 style="width: 20%;">เบอร์โทรศัพท์ : </h4>
                        <input type="text" name="mobile" class="form-control" value="{{$employee->mobile}}" style="width: 80%;">
                    </div>
                    <div class="col-md-12" style="display:flex;">
                        <h4 style="width: 10%;">อีเมลล์ : </h4>
                        <input type="text" name="email" class="form-control" value="{{$employee->email}}" style="width: 90%;">
                    </div>
                    <div class="col-md-6 col-sm-12" style="display:flex;">
                        <h4 style="width: 20%;">เพศ : </h4>
                        <select name="gender" class="form-control" style="width: 80%;">
                            <option value="{{$employee->gender}}">{{$employee->gender}}</option>
                            <option value="ชาย">ชาย</option>
                            <option value="หญิง">หญิง</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12" style="display:flex;">
                        <h4 style="width: 20%;">วันเกิด : </h4>
                        {{-- {{Carbon::parse($employee->birthdate)->format('d M Y')}} --}}
                        <input type="date" name="birthdate" class="form-control" value="{{$employee->birthdate}}" style="width: 80%;">
                    </div>
                    <div class="col-md-6 col-sm-12" style="display:flex;">
                        <h4 style="width:40%;">สัญชาติ : {{$employee->nationality}}</h4>
                        <select class="form-control" name="nationality" style="width:60%;">
                            <option value="{{$employee->nationality}}">{{$employee->nationality}}</option>
                            <option value="ไทย">ไทย</option>
                            <option value="อังกฤษ">อังกฤษ</option>
                            <option value="เยอรมัน">เยอรมัน</option>
                            <option value="จีน">จีน</option>
                            <option value="พม่า">พม่า</option>
                            <option value="ลาว">ลาว</option>
                            <option value="เวียดนาม">เวียดนาม</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12" style="display:flex;">
                        <h4 style="width:20%;">ศาสนา : </h4>
                        <select class="form-control" name="religion" style="width:80%;">
                            <option value="{{$employee->religion}}">{{$employee->religion}}</option>
                            <option value="พุทธ">พุทธ</option>
                            <option value="คริสต์">คริสต์</option>
                            <option value="อิสลาม">อิสลาม</option>
                            <option value="ฮินดู">ฮินดู</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12" style="display:flex;">
                        <h4 style="width:50%;">สถานะภาพ : </h4>
                        <select class="form-control" name="marital_status" style="width:50%;">
                            <option value="{{$employee->marital_status}}">{{$employee->marital_status}}</option>
                            <option value="โสด">โสด</option>
                            <option value="แต่งงานแล้ว">แต่งงานแล้ว</option>
                            <option value="หม้าย">หม้าย</option>
                            <option value="หย่า หรือ แยกกันอยู่">หย่า หรือ แยกกันอยู่</option>
                        </select>
                    </div>
                    <div class="col-md-6 col-sm-12" style="display:flex;">
                        <h4 style="width: 50%;">การรับราชการทหาร : </h4>
                        <select name="soldier" class="form-control" style="width:50%;">
                            <option value="{{$employee->soldier}}">{{$employee->soldier}}</option>
                            <option value="รับราชการทหารแล้ว">รับราชการทหารแล้ว</option>
                            <option value="ได้รับการยกเว้น">ได้รับการยกเว้น</option>
                            <option value="จบรักษาดินแดน">จบรักษาดินแดน</option>
                        </select>
                    </div>
                    <div class="col-md-12" style="display:flex;">
                        <h4 style="width: 10%;">ที่อยู่ : </h4>
                        <input type="text" name="address" class="form-control" value="{{$employee->address}}" style="width: 90%;">
                    </div>
                    <div class="col-md-12" style="display:flex;">
                        <select name="district" class="form-control" style="width:50%;" id="district">
                            <option value="{{$employee->DISTRICT_ID}}">{{$employee->DISTRICT_NAME}}</option>
                            @foreach ($districts as $district)
                            <option value="{{$district->DISTRICT_ID}}">{{$district->DISTRICT_NAME}}</option>
                            @endforeach
                        </select>
                        <select name="amphur" class="form-control" style="width:50%;" id="amphur">
                            <option value="{{$employee->AMPHUR_ID}}">{{$employee->AMPHUR_NAME}}</option>
                            @foreach ($amphurs as $amphur)
                            <option value="{{$amphur->AMPHUR_ID}}">{{$amphur->AMPHUR_NAME}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-12" style="display:flex;">
                        <select name="province" class="form-control" style="width:50%;" id="province">
                            <option value="{{$employee->PROVINCE_ID}}">{{$employee->PROVINCE_NAME}}</option>
                            @foreach ($provinces as $province)
                            <option value="{{$province->PROVINCE_ID}}">{{$province->PROVINCE_NAME}}</option>
                            @endforeach
                        </select>
                        <input type="text" name="zipcode" class="form-control" value="{{$employee->zipcode}}" style="width: 50%;">
                    </div>
                    <div class="col-md-12" style="display:flex;">
                        <h4 style="display:flex; width:30%;">ข้อมูลทางการเงิน :
                                    <img src="https://kasikornbank.com/SiteCollectionDocuments/about/img/logo/logo.png" width="30">
                                    :
                        </h4>
                        <input type="text" class="form-control" name="bank_no" value="{{$employee->bank_no}}" style="width:70%;">
                    </div>
                </div>
            </div>

            <div class="row"></div>

            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h4>ข้อมูลการทำงาน</h4>
                </div>

                <div class="col-md-6 col-sm-12" style="display:flex;">
                    <h4 style="width: 20%;">แผนก : </h4>
                    <select name="dept_id" class="form-control" style="width:80%;">
                        <option value="{{$employee->dept_id}}">{{$employee->position}}</option>
                        @foreach ($departments as $department)
                        <option value="{{$department->dept_id}}">{{$department->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-sm-12" style="display:flex;">
                    <h4 style="width: 20%;">ตำแหน่ง : </h4>
                    <select name="position" class="form-control" style="width:80%;">
                        <option value="{{$employee->position}}">{{$employee->position}}</option>
                        @foreach ($departments as $department)
                        <option value="{{$department->name}}">{{$department->name}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6 col-sm-12" style="display:flex;">
                    <h4 style="width: 20%;">ประเภทการจ้าง : </h4>
                    <select name="emp_type" class="form-control" style="width:80%;">
                        <option value="{{$employee->emp_type}}">{{$employee->emp_type}}</option>
                        <option value="พนักงานประจำรายเดือน">พนักงานประจำรายเดือน</option>
                        <option value="พนักงานประจำรายวัน">พนักงานประจำรายวัน</option>
                        <option value="พาร์ทไทม์">พาร์ทไทม์</option>
                        <option value="ฝึกงาน">ฝึกงาน</option>
                        <option value="ทดลองงาน">ทดลองงาน</option>
                    </select>
                </div>
                <div class="col-md-6 col-sm-12" style="display:flex;">
                    <h4 style="width: 20%;">วันเริ่มงาน : </h4>
                    <input type="date" name="emp_start_date" class="form-control" value="{{$employee->emp_start_date}}" style="width: 80%;">
                </div>
                <div class="col-md-6 col-sm-12">
                    <h4>เงินเดือน : {{$employee->salary}}</h4>
                </div>
                @if (Auth::user()->permission == 'boss' || Auth::user()->permission == 'admin' )
                <div class="col-md-6 col-sm-12" style="display:flex;">
                    <h4 style="width: 20%;">ลากิจ : </h4>
                    <input type="text" name="emp_limitErrand" class="form-control" value="{{$employee->emp_limitErrand}}" style="width: 80%;">
                </div>
                @else
                    <input type="hidden" name="emp_limitErrand" class="form-control" value="{{$employee->emp_limitErrand}}">
                @endif
            </div>

        <button type="submit" class="col-md-12 btn btn-success">บันทึก</button>
        </div>
    </form>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>

$("#province").select2();
$("#amphur").select2();
$("#district").select2();

</script>

@stop
@section('js')
@endsection
