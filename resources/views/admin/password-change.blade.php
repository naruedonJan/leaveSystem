@extends('adminlte::page')

@section('title', 'Digitalmerce || เปลี่ยนรหัสผ่าน')


@section('content')

<div class="row">
    <div class="col-sm-6">
        <div class="panel panel-success">
            <div class="panel-heading ">เปลี่ยนรหัสผ่าน</div>
            <div class="panel-body">
                <form action="change-password" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="password" class="control-label">รหัสผ่านใหม่
                        @if(!empty($_GET['emp_id']))
                            {{$_GET['emp_nickname']}} : {{$_GET['emp_name']}}
                        @endif
                        </label>
                        @if(!empty($_GET['emp_id']))
                            <input type="hidden" class="form-control" name="id" value="{{$_GET['emp_id']}}">
                        @endif
                        <input type="text" class="form-control" name="password" placeholder="เปลี่ยนรหัสผ่าน" value="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary pull-right" value="">เปลี่ยนรหัสผ่าน</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@stop
