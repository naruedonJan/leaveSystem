@extends('adminlte::page')

@section('title', 'Digitalmerce || กราฟฟิค')

@section('content_header')

@stop

@section('content')

<div class="panel panel-warning">
    <div class="panel-heading"><h4>กราฟฟิค</h4></div>
    <div class="panel-body">
        <?php $now = date("Y-m-d"); ?>
        <form action="{{ url('/job/graphic/addjob') }}" method="post">
        @csrf

            <div class="form-group">
                <label for="date">วันที่</label>
                <input type="date" name="job_date" class="form-control" placeholder="วันที่" value="{{ $now }}" required style="width: 100%;">
            </div>
            <div class="form-group">
                <label for="date">ชื่องาน</label>
                <input type="text" name="job_name" class="form-control" placeholder="ชื่องาน" required style="width:100%;">
            </div>
            <div class="form-group">
                <label for="date">ประเภท</label>
                <select name="job_type" class="form-control" required>
                    <option value="">เลือกประเภท</option>
                    <option value="1">รูปภาพ</option>
                    <option value="2">วิดีโอ</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">รายละเอียด</label>
                <input type="text" name="job_detail" class="form-control" placeholder="รายละเอียด" required style="width: 100%;">
            </div>
            <div class="form-group">
                <label for="date">ที่อยู่ไฟล์ภาพ</label>
                <input type="text" name="job_link" class="form-control" placeholder="ที่อยู่ไฟล์ภาพ" required style="width: 100%;">
            </div>
            <div class="form-group">
                <label for="date">หมายเหตุ</label>
                <textarea name="job_ps" cols="30" rows="10" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="บันทึก" style="width: 100%;">
            </div>
        </form>
    </div>
</div>
@stop
