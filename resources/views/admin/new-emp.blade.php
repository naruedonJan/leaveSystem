@extends('adminlte::page')

@section('title', 'Digitalmerce || พนักงานใหม่')

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading"><h4>พนักงานใหม่ที่สมัครเข้าระบบ</h4></div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ชื่อ-นามสกุล</th>
                        <th>ชื่อเล่น</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employee as $key => $emp)
                    <tr>
                        <td scope="row">{{ $key+$employee->firstItem()}}</td>
                        <td>{{ $emp->name_th }}  {{ $emp->surname_th}}</td>
                        <td>{{ $emp->nickname}}</td>
                        <td>
                            <a href="accept/{{$emp->uid}}?status=allow" class='btn btn-success' style="margin-right: 5px;">อนุมัติ</a>
                            <a href="accept/{{$emp->uid}}?status=disallow" class='btn btn-danger'>ไม่อนุมัติ</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@stop
