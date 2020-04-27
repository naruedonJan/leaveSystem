@extends('adminlte::master')

@section('adminlte_css')
    @yield('css')
    <style>
        body{
            background-color: #eee;
        }
    </style>
@stop


@section('body')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

<div class="register-box" style="width:70% !important;">
    {{-- <div class="box box-primary">
        <div class="box-body"> --}}
            <form action="/register-emp" method="POST" enctype="multipart/form-data">
            @csrf
                <div style="padding:20px;">
                    <div class="box box-primary">
                        <div class="box-body" style="padding:0 20%;">
                                <h3>Email & Password</h3>
                            <div class="form-group has-feedback">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                            </div>

                            <div class="form-group has-feedback">
                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                            </div>

                            {{-- <div class="form-group has-feedback">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Retype password">
                                <span class="glyphicon glyphicon-log-in form-control-feedback"></span>
                            </div> --}}
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-body">
                            <h3>ข้อมูลส่วนตัว</h3>
                            <div class="row">

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">คำนำหน้าชื่อ</label>
                                          <select class="form-control" name="title_th" required>                                              
                                            <option value=""></option>
                                            <option value="นาย">นาย</option>
                                            <option value="นาง">นาง</option>
                                            <option value="นางสาว">นางสาว</option>
                                          </select>
                                    </div>
                                </div>

                                <div class="col-md-3 ">
                                    <div class="form-group has-feedback">
                                        <label for="">ชื่อ</label>
                                        <input type="text" name="name_th" class="form-control" placeholder="ชื่อ" required>
                                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">นามสกุล</label>
                                        <input type="text" name="surname_th" class="form-control" placeholder="นามสกุล" required>
                                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">ชื่อเล่น</label>
                                        <input type="text" name="nickname" class="form-control" placeholder="ชื่อเล่น" required>
                                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">prefix</label>
                                          <select class="form-control" name="title_en" required> 
                                            <option value="">กรุณาเลือก</option>                        
                                            <option value="Mr">Mr</option>                                           
                                            <option value="MISS">MISS</option>                                           
                                            <option value="Ms">Ms</option>
                                          </select>
                                    </div>
                                </div>

                                <div class="col-md-3 ">
                                    <div class="form-group has-feedback">
                                        <label for="">name</label>
                                        <input type="text" name="name_en" class="form-control" placeholder="Full name" required>
                                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">นามสกุล</label>
                                        <input type="text" name="surname_en" class="form-control" placeholder="Last name" required>
                                        <span class="glyphicon glyphicon-user form-control-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">เพศ</label>
                                          <select class="form-control" name="gender" id="" required> 
                                            <option value="">กรุณาเลือก</option>                                    
                                            <option value="ชาย">ชาย</option>                                            
                                            <option value="หญิง">หญิง</option>                                            
                                            <option value="ไม่ระบุ">ไม่ระบุ</option>
                                          </select>
                                    </div>
                                </div>                           

                                <div class="col-md-3 ">
                                    <div class="form-group has-feedback">
                                        <label for="">เบอร์โทรศัพท์</label>
                                        <input type="text" name="mobile" class="form-control" placeholder="เบอร์โทรศัพท์" required>
                                        <span class="glyphicon glyphicon-phone form-control-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">วันเกิด</label>
                                        <input type="date" name="birthdate" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">สัญชาติ</label>
                                          <select class="form-control" name="nationality" required>  
                                            <option value="">กรุณาเลือก</option>                           
                                            <option value="ไทย">ไทย</option>
                                            <option value="อังกฤษ">อังกฤษ</option>
                                            <option value="เยอรมัน">เยอรมัน</option>
                                            <option value="จีน">จีน</option>
                                            <option value="พม่า">พม่า</option>
                                            <option value="ลาว">ลาว</option>
                                            <option value="เวียดนาม">เวียดนาม</option>
                                          </select>
                                    </div>
                                </div>    

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">ศาสนา</label>
                                          <select class="form-control" name="religion" required>    
                                            <option value="">กรุณาเลือก</option>
                                            <option value="พุทธ">พุทธ</option>
                                            <option value="คริสต์">คริสต์</option>
                                            <option value="อิสลาม">อิสลาม</option>
                                            <option value="ฮินดู">ฮินดู</option>
                                          </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">สถานะภาพ</label>
                                          <select class="form-control" name="marital_status" required>   
                                            <option value="">กรุณาเลือก</option>
                                            <option value="โสด">โสด</option>
                                            <option value="แต่งงานแล้ว">แต่งงานแล้ว</option>
                                            <option value="หม้าย">หม้าย</option>
                                            <option value="หย่า หรือ แยกกันอยู่">หย่า หรือ แยกกันอยู่</option>
                                          </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">การรับราชการทหาร</label>
                                          <select class="form-control" name="soldier" required>     
                                            <option value="">กรุณาเลือก</option>
                                            <option value="รับราชการทหารแล้ว">รับราชการทหารแล้ว</option>
                                            <option value="ได้รับการยกเว้น">ได้รับการยกเว้น</option>
                                            <option value="จบรักษาดินแดน">จบรักษาดินแดน</option>
                                          </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">เลขประจำตัวประชาชน</label>
                                        <input type="text" name="card_id" class="form-control" placeholder="เลขประจำตัวประชาชน" required>
                                        <span class="glyphicon glyphicon-credit-card form-control-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-md-8">
                                    <div class="form-group has-feedback">
                                        <label for="">ที่อยู่</label>
                                        <input type="text" name="address" class="form-control" placeholder="ที่อยู่" required>
                                        <span class="glyphicon glyphicon-home form-control-feedback"></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group has-feedback">
                                        <label for="">รหัสไปรษณี</label>
                                        <input type="text" name="zipcode" class="form-control" placeholder="รหัสไปรษณี" required>
                                        <span class="glyphicon glyphicon-home form-control-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">แขวง</label>
                                        <select name="district" class="form-control" id="district" required>
                                            <option value="">กรุณาเลือก</option>
                                            @foreach ($districts as $district)
                                            <option value="{{$district->DISTRICT_ID}}">{{$district->DISTRICT_NAME}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">เขต</label>
                                        <select name="amphur" class="form-control" id="amphur" required>
                                            <option value="">กรุณาเลือก</option>
                                            @foreach ($amphurs as $amphur)
                                            <option value="{{$amphur->AMPHUR_ID}}">{{$amphur->AMPHUR_NAME}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">จังหวัด</label>
                                        <select name="province" class="form-control" id="province" required>
                                            <option value="">กรุณาเลือก</option>
                                            @foreach ($provinces as $province)
                                            <option value="{{$province->PROVINCE_ID}}">{{$province->PROVINCE_NAME}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">รูป</label>
                                        <input type="file" name="profile-img" class="form-control" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="">รูปบัตรประชาชน</label>
                                        <input type="file" name="idcard-img" class="form-control" required>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="box box-primary">
                        <div class="box-body">
                            <h3>ข้อมูลการทำงาน</h3>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">แผนก</label>
                                          <select class="form-control" name="dept_id" required>
                                            <option value="">กรุณาเลือก</option>
                                            @foreach ($departments as $department)
                                            <option value="{{$department->dept_id}}">{{$department->name}}</option>
                                            @endforeach
                                          </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">ตำแหน่ง</label>
                                          <select class="form-control" name="position" required>
                                            <option value="">กรุณาเลือก</option>
                                            @foreach ($departments as $department)
                                            <option value="{{$department->name}}">{{$department->name}}</option>
                                            @endforeach
                                          </select>
                                    </div>
                                </div>

                                <div class="col-md-3 ">
                                    <div class="form-group">
                                        <label for="">วันเริ่มงาน</label>
                                        <input type="date" name="emp_start_date" class="form-control" placeholder="วันเริ่มงาน" required>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">เลขบัญชี</label>
                                        <input type="text" name="bank_no" class="form-control" placeholder="เลขบัญชี" required>
                                        <span class="glyphicon glyphicon-usd form-control-feedback"></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group has-feedback">
                                        <label for="">ประเภทการจ้าง</label>
                                          <select class="form-control" name="emp_type" required>
                                            <option value="">กรุณาเลือก</option>
                                            <option value="พนักงานประจำรายเดือน">พนักงานประจำรายเดือน</option>
                                            <option value="พนักงานประจำรายวัน">พนักงานประจำรายวัน</option>
                                            <option value="พาร์ทไทม์">พาร์ทไทม์</option>
                                            <option value="ฝึกงาน">ฝึกงาน</option>
                                            <option value="ทดลองงาน">ทดลองงาน</option>
                                          </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <button type="submit" class="col-md-12 btn btn-success">บันทึก</button>
                    <a href="/login" class="col-md-12 btn btn-danger">ย้อนกลับ</a>
                </div>
            </form>
</div>

<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

<script>

$("#province").select2();
$("#amphur").select2();
$("#district").select2();

</script>

@stop