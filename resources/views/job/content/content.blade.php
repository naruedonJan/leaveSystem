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

@section('title', 'Digitalmerce || คอนเทนต์')


@section('content')

<div class="panel panel-primary">
    <div class="panel-heading"><h4>งานคอนเทนต์</h4></div>
    <div class="panel-body">
        <form action="{{ url('/job/content') }}" method="GET">
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
                                    @foreach ($Web as $web)
                                    <option value="{{$web->BLID}}">{{$web->Weblink}}</option>
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
                    <th class="text-center" scope="col" width = "10%">#</th>
                    <th class="text-center" scope="col" width = "10%">วันที่</th>
                    <th class="text-center" scope="col" width = "10%">เจ้าของงาน</th>
                    <th class="text-center" scope="col" width = "10%">ชื่อเว็บ</th>
                    <th class="text-center" scope="col" width = "10%">ชื่อบทความ</th>
                    <th class="text-center" scope="col" width = "10%">ลิงค์</th>
                    <th class="text-center" scope="col" width = "10%">ตรวจงาน</th>
                    <th class="text-center" scope="col" width = "10%">หมายเหตุ</th>
                    <th class="text-center" scope="col" width = "10%">ลบ</th>
                    @if(Auth::user()->department == 1 || Auth::user()->id == 50 || Auth::user()->department == 8)
                    <th class="text-center" scope="col" width = "10%">ยืนยัน</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($Job as $key => $job)
                <?php
                $job_date = date_create($job->job_date);
                if ($job->con_st == 0) {
                    $chkDate = NUll;
                }
                else {
                    $chkDate = date_create($job->chkDate);
                }
                $day = date("N",strtotime($job->job_date))
                ?>
                    @if($job->con_st == 1)
                    <tr style="background: #05ff80;">
                    @elseif($job->con_st == 2)
                    <tr style="background: #bf0000; color: #fff;">
                    @else
                    <tr>
                    @endif

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
                        <td> {{ $job->Weblink }} </td>
                        <td> {{ $job->c_name }} </td>
                        <td class="text-center"> <a href="{{ $job->c_url }}" target="_blank"> คลิก </a></td>

                        @if($job->con_st != 1 && (Auth::user()->department == 1 || Auth::user()->id == 50 || Auth::user()->department == 8))
                        <td class="text-center">
                            <a class="btn btn-warning" data-toggle="modal" data-target="#myModal{{ $job->CID }}"><i class="fas fa-pencil-alt"></i></a>
                        </td>
                        @elseif($job->con_st != 1 || $job->con_st == 2)
                        <td class="text-center"> - </td>
                        @else
                        <td class="text-center"> {{ date_format($chkDate,"d/m/Y") }} </td>
                        @endif

                        @if($job->con_st != 1 && (Auth::user()->department == 1 || Auth::user()->id == 50 || Auth::user()->department == 8))
                        <td> {{ $job->remark }} </td>
                        @else
                        <td> {{ $job->comment }} </td>
                        @endif

                        @if(Auth::user()->emp_id == $job->id)
                        <td class="text-center">
                        <form action="{{ url('/job/content') }}" method="GET">
                            @csrf
                            <input type="hidden" name="del_id" value="{{ $job->CID }}">
                            <button onclick= "return confirm('ยืนยันการลบ?');" type="submit" class="btn btn-danger">ลบ</button>
                        </form>
                        </td>
                        @else
                        <td class="text-center">  </td>
                        @endif

                        @if($job->con_st != 1 && (Auth::user()->department == 1 || Auth::user()->id == 50 || Auth::user()->department == 8))
                        <td class="text-center">
                            <form action="{{ url('/job/content') }}" method="GET">
                                @csrf
                                <input type="hidden" value="{{ $job->CID }}" name="CID">
                                <button onclick= "return confirm('ยืนยันการตรวจ?');" type="submit" class="btn btn-success">ยืนยัน</button>
                            </form>
                        </td>
                        @endif
                    </tr>

                    <!-- Modal -->
                    <div class="modal fade" id="myModal{{ $job->CID }}" role="dialog">
                        <div class="modal-dialog">
                            <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title">{{ $job->c_name }}</h4>
                                    </div>
                                    <div class="modal-body">
                                    <form action="{{ url('/job/content') }}" method="GET">
                                    @csrf
                                        <span class="input-group-text" id="basic-addon1">หมายเหตุ: </span>
                                        <input type="hidden" name="RE_CID" value="{{ $job->CID }}">
                                        <input type="text" class='form-control' name="remark">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-warning">แก้ไข</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                    </div>
                                    </form>
                                </div>
                        </div>
                    </div>
                    <!-- Modal -->
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@stop
