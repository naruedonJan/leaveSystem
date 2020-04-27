@extends('adminlte::page')

@section('title', 'Digitalmerce || พนักงาน')

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading"><h4>พนักงาน</h4></div>
        <div class="panel-body">

            @foreach ($countEmp as $item)
                <div class="panel panel-success">
                    <div class="panel-heading">
                        แผนก <b>{{$item->dep_name}} </b>  จำนวนคน <b>{{$item->user}}</b> คน
                    </div>
                    <div class="panel-body">

                        <table class="table">
                            <thead>
                                <tr>
                                    <th class="text-center"  width="5%">#</th>
                                    <th width="30%">ชื่อ-นามสกุล</th>
                                    <th class="text-center" width="5%">ชื่อเล่น</th>
                                    <th class="text-center" width="15%">แผนก</th>
                                    <th class="text-center">สถานะ</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach ($employee as $key => $emp)
                                    @if ($item->dept_id == $emp->dep_id)
                                    @php
                                        $i++;
                                    @endphp
                                        <tr>
                                            {{-- <td scope="row">{{ $key+$employee->firstItem()}}</td> --}}
                                            <td class="text-center">{{$i}}</td>
                                            <td>{{ $emp->name_th }}  {{ $emp->surname_th}}</td>
                                            <td class="text-center">{{ $emp->nickname}}</td>
                                            <td class="text-center">{{ $emp->dep}} @if( $emp->piority == 1 ) {{'(หัวหน้าแผนก)'}} @endif</td>
                                            <td class="text-center"><span class="st-active" style=" background-color:@if($emp->status == 1) #3bb309 @else #e02929 @endif;"></span></td>
                                            <td>
                                                <a href="/admin/profile?emp_id={{ $emp->eid }}&&user_id={{ $emp->uid }}" class='btn btn-info' style="margin-right: 5px;"><i class="far fa-user"></i></a>
                                                <a href="/changepassword?emp_id={{ $emp->uid }}&&emp_name={{ $emp->name_th}}&&emp_nickname={{ $emp->nickname}}" class="btn btn-success"><i class="fas fa-key"></i></a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>


@stop
