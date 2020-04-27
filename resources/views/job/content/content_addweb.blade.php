@extends('adminlte::page')

@section('title', 'Digitalmerce || คอนเทนต์')

@section('content_header')

@stop

@section('content')

<div class="panel panel-warning">
    <div class="panel-heading"><h4>คอนเทนต์</h4></div>
    <div class="panel-body">
        <?php $now = date("Y-m-d"); ?>
        <form action="{{ url('/job/content/addweb') }}" method="post">
        @csrf

            <div class="form-group">
                <label for="date">ลิงค์เว็บ</label>
                <input type="text" name="link_web" class="form-control" placeholder="ลิงค์เว็บ" required style="width:100%;">
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-success" value="บันทึก" style="width: 100%;">
            </div>
        </form>
    </div>
</div>
@stop

