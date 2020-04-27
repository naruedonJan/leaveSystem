@extends('adminlte::page')

@section('title', 'Digitalmerce || ประวัติการลา')
@php
use Carbon\Carbon;

@endphp


@section('content')

    <div class="panel panel-success">
        <div class="panel-heading"><h4>เปลี่ยนรหัสผ่าน</h4></div>
        <div class="panel-body container">
            <form action="personal/changepassword" method="post">
                <table class="table">
                    <tbody>
                        <tr>
                            <td class="text-right" width="50%"><p>อีเมลล์ :</p></td>
                            <td>
                                <p></p>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right" width="50%">รหัสผ่านเดิม :</td>
                            <td>
                                <input type="text" class="form-controll" name="old_password">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right" width="50%">รหัสผ่านใหม่ :</td>
                            <td>
                                <input type="text" class="form-controll" name="new_password" id="new_password">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right" width="50%">ยืนยันรหัสผ่านใหม่ :</td>
                            <td>
                                <input type="text" class="form-controll" id="confirm_password">
                            </td>
                        </tr>
                        <tr>
                            <td class="text-right" width="50%"><button class="btn btn-success btn-sm">ตกลง</button></td>
                            <td><button class="btn btn-danger btn-sm">ยกเลิก</button></td>
                        </tr>
                    </tbody>
                </table>
            </form>
        </div>
    </div>

@stop
@section('js')
<script>
    $(document).ready(function() {
        $('#new_password').change(function() {
            var confirm_p = $('#confirm_password').val();
            var new_p = $('#new_password').val();
            if (new_p == confirm_p) {
                alert(confirm_p);
            }

            // if (new_p.localeCompare(confirm_p)) {
            //     $('#massage').innerHTML = "รหัสผ่านใหม่ตรงกัน";
            // }else{
            //     $('#massage').innerHTML = "รหัสผ่านใหม่ไม่ตรงกัน";
            // }
        });
    });
</script>
@endsection
