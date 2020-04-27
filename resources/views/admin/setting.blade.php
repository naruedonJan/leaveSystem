@extends('adminlte::page')

@section('title', 'Digitalmerce || ตั้งค่าระบบ')


@section('content')

    <div class="panel panel-danger">
        <div class="panel-heading ">ตั้งค่าระบบ</div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th colspan="2">ตั้งค่าการแจ้งเตือน</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-right" width="40%">ไลน์ Token</td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                </tbody>
                <thead>
                    <tr>
                        <th colspan="2">ตั้งค่าวันหยุด</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-right" width="40%">จำกัดจำนวนวันลากิจ</td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                    <tr>
                        <td class="text-right" width="40%">จำกัดจำนวนวันลาป่วย</td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                    <tr>
                        <td class="text-right" width="40%">จำนวนวันหยุดประจำสัปดาห์ที่ต้องรออนุมัติ</td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                </tbody>
                <thead>
                    <tr>
                        <th colspan="2">จำนวนคน/แผนก ที่สามารถหยุดได้พร้อมกัน</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="text-right" width="40%">โปรแกรมเมอร์</td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                    <tr>
                        <td class="text-right" width="40%">กราฟฟิค</td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                    <tr>
                        <td class="text-right" width="40%">อเวนโก้</td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                    <tr>
                        <td class="text-right" width="40%">ซอร์ทเกมมิ่ง</td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                    <tr>
                        <td class="text-right" width="40%">วิเคราะห์และรายการ </td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                    <tr>
                        <td class="text-right" width="40%">แบลคลิ้ง</td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                    <tr>
                        <td class="text-right" width="40%">ตรวจคอนเทนต์</td>
                        <td class="text-left" width="60%"></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


@stop
