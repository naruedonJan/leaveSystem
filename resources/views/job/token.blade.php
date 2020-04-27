@extends('adminlte::page')

@section('title', 'Digitalmerce || แบ็คลิงค์')

@section('content_header')

@stop

@section('content')

<div class="panel panel-warning">
    <div class="panel-heading"><h4>โทเคน</h4></div>
    <div class="panel-body">
        <?php $now = date("Y-m-d"); ?>
        <form action="{{ url('/job/token/add') }}" method="post">
        @csrf
            @foreach ($Token as $token)
            <div class="form-group">
                <label for="date">โทเคนเดิม</label>
                <input type="text" class="form-control" style="width:100%;" readonly value="{{ $token->t_token }}">
                <input type="hidden" name="t_id" class="form-control" value="{{ $token->t_id }}">
            </div>
            @endforeach

            <div class="form-group">
                <label for="date">โทเคน</label>
                <input type="hidden" name="t_deptid" class="form-control" value="{{ $Dept }}">
                <input type="text" name="t_token" class="form-control" placeholder="โทเคน" required style="width:100%;">
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-success" value="บันทึก" style="width: 100%;">
            </div>
        </form>
    </div>
</div>
@stop

