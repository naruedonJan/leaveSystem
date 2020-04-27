@extends('adminlte::page')

@section('title', 'Digitalmerce || คอนเทนต์')

@section('content_header')

@stop

@section('content')

<div class="panel panel-warning">
    <div class="panel-heading"><h4>คอนเทนต์</h4></div>
    <div class="panel-body">
        <?php $now = date("Y-m-d"); ?>
        <form action="{{ url('/job/content/addjob') }}" method="post">
        @csrf

            <div class="form-group">
                <label for="date">วันที่</label>
                <input type="date" name="job_date" class="form-control" placeholder="วันที่" value="{{ $now }}" required style="width: 100%;">
            </div>
            <div class="form-group">
                <label for="date">เว็บ</label>
                <select name="web_id" class="form-control" required>
                    <option value="">เลือกเว็บ</option>
                    @foreach ($Web as $web)
                    <option value="{{$web->BLID}}">{{$web->Weblink}}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="date">ชื่อบทความ</label>
                <input type="text" name="job_name" class="form-control" placeholder="ชื่องาน" required style="width:100%;">
            </div>
            <div class="form-group">
                <label for="date">ลิงค์บทความ</label>
                <input type="text" name="job_link" class="form-control" placeholder="ลิงค์" required style="width: 100%;">
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
