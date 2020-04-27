@extends('adminlte::page')

@section('title', 'Digitalmerce || แผนก')

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading"><h4>Line Notify<button type="button" class="btn btn-info" data-toggle="modal" data-target="#lineAdd" style="float: right;">เพิ่มโทเคน</button></h4></div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <th class="text-center">#</th>
                    <th class="text-center">โทเคน</th>
                    <th class="text-center">ฟังก์ชัน</th>
                    <th class="text-center">แก้ไข</th>
                    <th class="text-center">ลบ</th>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($lines as $line)
                    <?php $i++; ?>
                        <tr>
                            <td class="text-center">{{$i}}</td>
                            <td>{{$line->t_token}}</td>
                            <td class="text-center">{{$line->nt_name}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit-line{{$line->t_id}}">แก้ไข</button>
                            </td>
                            <td class="text-center">
                                <form action="/admin/setting-line" method="post">
                                    @csrf
                                    <input type="hidden" name="delete_token" value="{{$line->t_id}}">
                                    <button type="submit" class="btn btn-danger" onclick= "return confirm('ลบ?');">ลบ</button>
                                </form>
                            </td>
                        </tr>
<!-- Modal edit line -->
<div class="modal fade bd-example-modal-lg" id="edit-line{{$line->t_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel panel-warning">
                <div class="panel-heading"><h5>แก้ไขโทเคน</h5></div>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3 col-md-12">
                    <div class="input-group-prepend">
                        <form class="row" method="POST" action="/admin/setting-line">
                            @csrf
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">โทเคน</label>
                                <input class="form-control" type="hidden" name="t_id" value="{{$line->t_id}}">
                                <input class="form-control" type="text" name="edit_token" value="{{$line->t_token}}" style="width:70%;">
                            </div>
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">เลือกฟังก์ชัน</label>
                                <select name="nt_id" class="form-control" style="width:70%;">
                                    <option value="{{$line->nt_id}}">{{$line->nt_name}}</option>
                                    @foreach ($notifys_to as $notify_to)
                                        <option value="{{$notify_to->nt_id}}">{{$notify_to->nt_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success btn-sm btn-block">แก้ไขโทเคน</button>
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
<!-- Modal edit line -->
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
<!-- Modal add line -->
<div class="modal fade bd-example-modal-lg" id="lineAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel panel-warning">
                <div class="panel-heading"><h5>เพิ่มโทเคน</h5></div>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3 col-md-12">
                    <div class="input-group-prepend">
                        <form class="row" method="POST" action="/admin/setting-line">
                            @csrf
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">โทเคน</label>
                                <input class="form-control" type="text" name="add_token" required autocomplete="off" style="width:70%;">
                            </div>
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">โทเคน</label>
                                <select name="nt_id" class="form-control" style="width:70%;">
                                    <option value="">เลือกฟังก์ชัน</option>
                                    @foreach ($notifys_to as $notify_to)
                                        <option value="{{$notify_to->nt_id}}">{{$notify_to->nt_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success btn-sm btn-block">เพิ่มโทเคน</button>
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
<!-- Modal add line -->
@stop
