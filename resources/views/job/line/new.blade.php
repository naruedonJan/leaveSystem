@extends('adminlte::page')

@section('title', 'Digitalmerce || แบ็คลิงค์')

@section('content_header')

@stop

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading"><h4>เพิ่มLine@ใหม่ </h4></div>
        <div class="panel-body">
            <form action="postLine" method="post" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="Name">Line @</label>
                    <input type="text" name="line" placeholder="Line @ ex. @mm88th" class="contact-name form-control">
                </div>
                <button type="submit" class="btn btn-success btn-block">เพิ่ม</button>
            </form>
        </div>
    </div>


@stop
