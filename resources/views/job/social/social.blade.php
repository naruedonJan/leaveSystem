<style>
.thead-dark{
    background: #3a3a3a;
    color: #fff;
}
.label-tuesday{
    background-color: #dd39c1 !important;
}
.label-thursday{
    background-color: #f56600 !important;
}
.label-saturday{
    background-color: #9e39dd !important;
}
</style>
@extends('adminlte::page')

@section('title', 'Digitalmerce || โซเชียล')


@section('content')

<div class="panel panel-primary">
    <div class="panel-heading"><h4>งานโซเชียล</h4></div>
    <div class="panel-body">
        <form action="{{ url('/job/social') }}" method="GET">
            @csrf
            <div class="row">
                <div class="col-sm-9" style="height: 60px;">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fas fa-laptop"></i>
                                </div>
                                <select name="web_id" class="form-control pull-right">
                                    <option value="">เลือกเว็บ</option>
                                    @foreach ($Page as $page)
                                    <option value="{{$page->SMID}}">{{$page->sm_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <select name="emp_id" class="form-control pull-right">
                                    <option value="">เลือกพนักงาน</option>
                                    @foreach ($Emp as $Emp)
                                    <option value="{{ $Emp->id }}">{{ $Emp->nickname }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="date" class="form-control pull-right" name="start_date">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="date" class="form-control pull-right" name="end_date">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3" style="height: 60px;">
                    <button type="submit" class="btn btn-success form-control">ค้นหา</button>
                </div>
            </div>
        </form>

        <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center" scope="col" width="5%">#</th>
                    <th class="text-center" scope="col" width="10%">วันที่</th>
                    <th class="text-center" scope="col" width="10%">เจ้าของงาน</th>
                    <th class="text-center" scope="col" width="10%">ชื่อเพจ</th>
                    <th class="text-center" scope="col" width="30%">ชื่องาน</th>
                    <th class="text-center" scope="col" width="10%">ลิงค์ที่นำไปโพสต์</th>
                    <th class="text-center" scope="col" width="20%">หมายเหตุ</th>
                    <th class="text-center" scope="col" width="5%">ลบ</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($Job as $key => $job)
                <?php
                $job_date = date_create($job->job_date);
                $day = date("N",strtotime($job->job_date))
                ?>
                    <tr>
                @switch( $day )
                    @case( $day == 1 )
                        <td class="text-center" style="vertical-align: middle;"> <span class="label label-warning">Mon</span> </td>
                    @break
                    @case( $day == 2 )
                        <td class="text-center" style="vertical-align: middle;"> <span class="label label-tuesday">Tue</span> </td>
                    @break
                    @case( $day == 3 )
                        <td class="text-center" style="vertical-align: middle;"> <span class="label label-success">Wed</span> </td>
                    @break
                    @case( $day == 4 )
                        <td class="text-center" style="vertical-align: middle;"> <span class="label label-thursday">Thu</span> </td>
                    @break
                    @case( $day == 5 )
                        <td class="text-center" style="vertical-align: middle;"> <span class="label label-info">Fri</span> </td>
                    @break
                    @case( $day == 6 )
                        <td class="text-center" style="vertical-align: middle;"> <span class="label label-saturday">Sat</span> </td>
                    @break
                    @case( $day == 7 )
                        <td class="text-center" style="vertical-align: middle;"> <span class="label label-danger">Sun</span> </td>
                    @break
                @endswitch

                        <td class="text-center"> {{ date_format($job_date,"d/m/Y") }} </td>
                        <td class="text-center"> {{ $job->nickname }} </td>
                        <td class="text-center"> {{ $job->sm_name }} </td>
                        <td> {{ $job->p_name }} </td>
                        <td class="text-center"> <a href="{{ $job->p_url }}" target="_blank"> คลิก </a></td>
                        <td> {{ $job->comment }} </td>
                        @if(Auth::user()->emp_id == $job->id)
                        <td class="text-center">
                        <form action="{{ url('/job/social') }}" method="GET">
                            @csrf
                            <input type="hidden" name="del_id" value="{{ $job->PID }}">
                            <button onclick= "return confirm('ยืนยันการลบ?');" type="submit" class="btn btn-danger">ลบ</button>
                        </form>
                        </td>
                        @else
                        <td class="text-center">  </td>
                        @endif
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop
