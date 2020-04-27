@extends('adminlte::page')

@section('title', 'Digitalmerce || รายการ')

@section('content_header')

@stop

@section('content')

<div class="panel panel-warning">
    <div class="panel-heading"><h4>รายการ</h4></div>
    <div class="panel-body">
        <?php $now = date("Y-m-d"); ?>
        <form action="{{ url('/job/program/addprogram') }}" method="post">
        @csrf
            <div class="form-group">
                <input type="text" name="pd_name" class="form-control" placeholder="ชื่อรายการ" required autocomplete="off">
            </div>
            <div class="form-group">
                <label for="date">วันที่</label>
                <input type="date" name="pd_date" class="form-control" placeholder="วันที่" required autocomplete="off" style="width: 30%;" value="{{$now}}">
            </div>
            <div class="form-group">
                <label for="date">เวลาเริ่ม</label>
                <input type="time" name="pd_start_time" class="form-control" placeholder="เวลาเริ่ม" autocomplete="off" style="width: 30%;" value="00:00">
            </div>
            <div class="form-group">
                <label for="date">เวลาสิ้นสุด</label>
                <input type="time" name="pd_end_time" class="form-control" placeholder="เวลาสิ้นสุด" autocomplete="off" style="width: 30%;" value="00:00">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="บันทึก">
            </div>
        </form>
    </div>
</div>

@stop
