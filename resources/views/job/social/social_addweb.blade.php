@extends('adminlte::page')

@section('title', 'Digitalmerce || โซเชียล')

@section('content_header')

@stop

@section('content')

<div class="panel panel-warning">
    <div class="panel-heading"><h4>โซเชียล</h4></div>
    <div class="panel-body">
        <?php $now = date("Y-m-d"); ?>
        <form action="{{ url('/job/social/addweb') }}" method="post">
        @csrf
            <div class="form-group">
                <label for="date">ชื่อเพจ/ช่อง</label>
                <input type="text" name="page_name" class="form-control" placeholder="ชื่อเพจ" required style="width:100%;">
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-success" value="บันทึก" style="width: 100%;">
            </div>
        </form>
    </div>
</div>

@stop
