<style>
.thead-dark{
    background: #3a3a3a;
    color: #fff;
}
h3{
    margin-top: 0px !important;
    margin-bottom: 0px !important;
}
</style>
@extends('adminlte::page')

@section('title', 'Digitalmerce || โปรโมทเพจ')

@section('content_header')

@stop

@section('content')

<div class="panel panel-primary">
    <div class="panel-heading"><h4>รายการโปรโมท</h4></div>
    <div class="panel-body">
        <form action="{{ url('/job/pagecost') }}" method="GET">
            @csrf
            <div class="row">
                <div class="col-sm-9" style="height: 60px;">
                    <div class="row">
                        <div class="col-sm-4">
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
                        <div class="col-sm-4">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="date" class="form-control pull-right" name="start_boost">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group date">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input type="date" class="form-control pull-right" name="end_boost">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3" style="height: 60px;">
                    {{-- <label for="owner_start_date" class="control-label"><a href="/report/monthly">เรียกดูข้อมูลตัดรอบเดือน</a></label> --}}
                    <button type="submit" class="btn btn-success form-control">ค้นหา</button>
                </div>
            </div>
        </form>

        <table class="table table-hover table-striped">
            <thead class="thead-dark">
                <tr>
                    <th class="text-center" scope="col">#</th>
                    <th class="text-center" scope="col">เจ้าของงาน</th>
                    <th class="text-center" scope="col">ชื่อเพจ</th>
                    <th class="text-center" scope="col">ลิงค์</th>
                    <th class="text-center" scope="col">ชื่อคอนเทนต์</th>
                    <th class="text-center" scope="col">จากวันที่</th>
                    <th class="text-center" scope="col">ถึงวันที่</th>
                    <th class="text-center" scope="col">ราคา</th>
                    <th class="text-center" scope="col">ลบ</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sum = 0 ;
                ?>
                @foreach ($Job as $key => $page)
                <?php
                $sum += $page->boost_cost;
                ?>
                    <tr>
                        <td class="text-center" scope="row">{{ $key+1 }}</td>
                        <td class="text-center">{{ $page->nickname }}</td>
                        <td class="text-center">{{ $page->page_name }}</td>
                        @if (!empty($page->pc_link))
                            <td class="text-center"><a href="{{ $page->pc_link }}">link</a></td>
                        @else
                            <td></td>
                        @endif
                        <td class="text-center">{{ $page->pc_content }}</td>

                        <?php
                        $start_Boost = date_create($page->start_boost);
                        ?>
                        <td class="text-center">{{ date_format($start_Boost,"d/m/Y") }}</td>

                        <?php
                        $end_Boost = date_create($page->end_boost);
                        ?>
                        <td class="text-center">{{ date_format($end_Boost,"d/m/Y") }}</td>

                        <td class="text-center">{{ $page->boost_cost }}</td>

                        @if(Auth::user()->emp_id == $page->emp_id)
                        <td class="text-center">
                        <form action="{{ url('/job/pagecost') }}" method="GET">
                            @csrf
                            <input type="hidden" name="del_id" value="{{ $page->pcid }}">
                            <button onclick= "return confirm('Are you sure you want to delete this item?');" type="submit" class="btn btn-danger">ลบ</button>
                        </form>
                        </td>
                        @else
                        <td class="text-center">  </td>
                        @endif
                    </tr>
                @endforeach
                    <tr>
                        <td colspan="6"></td>
                        <td class="text-center"><h3 style="color: red;">ผลรวม</h3></td>
                        <td class="text-center"><h3 style="color: red;">{{ $sum }}</h3></td>
                        <td></td>
                    </tr>
            </tbody>
        </table>
    </div>
</div>

@stop
