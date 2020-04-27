@extends('adminlte::page')

@section('title', 'Digitalmerce || โปรโมทเพจ')

@section('content_header')

@stop

@section('content')

<div class="panel panel-warning">
    <div class="panel-heading"><h4>โปรโมทเพจ</h4></div>
    <div class="panel-body">
        <?php $now = date("Y-m-d"); ?>
        <form action="{{ url('/job/pagecost/addcost') }}" method="post">
        @csrf
            <div class="form-group">
                <input type="text" name="page_name" class="form-control" placeholder="ชื่อเพจ" required autocomplete="off">
            </div>
            <div class="form-group">
                <input type="number" step="0.01" name="boost_cost" class="form-control" placeholder="ราคา" required autocomplete="off">
            </div>
            <div class="form-group">
                <input type="text" name="pc_link" class="form-control" placeholder="ลิงค์" autocomplete="off">
            </div>
            <div class="form-group">
                <input type="text" name="pc_content" class="form-control" placeholder="ชื่อคอนเทนต์" autocomplete="off">
            </div>
            <div class="form-group">
                <label for="date">วันเริ่มโปรโมท</label>
                <input type="date" name="start_boost" class="form-control" placeholder="วันเริ่มโปรโมท" required autocomplete="off" style="width: 30%;">
            </div>
            <div class="form-group">
                <label for="date">วันสิ้นสุดโปรโมท</label>
                <input type="date" name="end_boost" class="form-control" placeholder="วันสิ้นสุดโปรโมท" autocomplete="off" style="width: 30%;">
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-success" value="บันทึก">
            </div>
        </form>
    </div>
</div>

@stop
