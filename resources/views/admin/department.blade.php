@extends('adminlte::page')

@section('title', 'Digitalmerce || แผนก')

@section('content')
<div class="panel panel-warning">
    <div class="panel-heading">
        <h4>รายชื่อแผนก<button type="button" class="btn btn-info" data-toggle="modal" data-target="#department"
                style="float: right;">เพิ่มแผนก</button></h4>
    </div>
    <div class="panel-body">
        <table class="table">
            <thead>
                <th class="text-center">#</th>
                <th class="text-center">แผนก</th>
                <th class="text-center">จำนวนคนหยุด/วัน</th>
                <th class="text-center">แก้ไข</th>
                <th class="text-center">ลบ</th>
            </thead>
            <tbody>
                <?php $i = 0; ?>
                @foreach ($departments as $department)
                <?php $i++; ?>
                <tr>
                    <td class="text-center">{{$i}}</td>
                    <td>{{$department->name}}</td>
                    <td class="text-center">{{$department->limit_event}}</td>
                    <td class="text-center">
                        <button type="button" class="btn btn-warning" data-toggle="modal"
                            data-target="#edit-department{{$department->dept_id}}">แก้ไขแผนก</button>
                    </td>
                    <td class="text-center">
                        <form action="/admin/delete-department" method="post">
                            @csrf
                            <input type="hidden" name="id" value="{{$department->dept_id}}">
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('ยกเลิกแผนก?');">ยกเลิก</button>
                        </form>
                    </td>
                </tr>
                <!-- Modal edit department -->
                <div class="modal fade bd-example-modal-lg" id="edit-department{{$department->dept_id}}" tabindex="-1"
                    role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="panel panel-warning">
                                <div class="panel-heading">
                                    <h5>แก้ไขแผนก</h5>
                                </div>
                            </div>
                            <div class="modal-body">
                                <div class="input-group mb-3 col-md-12">
                                    <div class="input-group-prepend">
                                        <form class="row" method="POST" action="/admin/edit-department">
                                            @csrf
                                            <div class="form-group col-md-12" style="display:flex;">
                                                <label for="type" style="width:30%;">แผนก</label>
                                                <input class="form-control" type="hidden" name="id"
                                                    value="{{$department->dept_id}}">
                                                <input class="form-control" type="text" name="name"
                                                    value="{{$department->name}}" style="width:70%;">
                                            </div>
                                            <div class="form-group col-md-12" style="display:flex;">
                                                <label for="type" style="width:30%;">จำนวนคนหยุด/วัน</label>
                                                <input class="form-control" type="text" name="limit_event"
                                                    value="{{$department->limit_event}}" style="width:70%;">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <button type="submit"
                                                    class="btn btn-success btn-sm btn-block">แก้ไขแผนก</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Modal edit department -->
                @endforeach
            </tbody>
        </table>

    </div>
</div>
<!-- Modal add department -->
<div class="modal fade bd-example-modal-lg" id="department" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h5>เพิ่มแผนก</h5>
                </div>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3 col-md-12">
                    <div class="input-group-prepend">
                        <form class="row" method="POST" action="/admin/add-department">
                            @csrf
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">แผนก</label>
                                <input class="form-control" type="text" name="name" required autocomplete="off"
                                    style="width:70%;">
                            </div>
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">จำนวนคนหยุด/วัน</label>
                                <input class="form-control" type="text" name="limit_event" required autocomplete="off"
                                    style="width:70%;">
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success btn-sm btn-block">เพิ่มแผนก</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal add department -->
@stop