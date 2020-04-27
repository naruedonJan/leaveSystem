@extends('adminlte::page')

@section('title', 'Digitalmerce || แผนก')

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading"><h4>Line Function<button type="button" class="btn btn-info" data-toggle="modal" data-target="#functionAdd" style="float: right;">เพิ่มฟังก์ชัน</button></h4></div>
        <div class="panel-body">
            <table class="table">
                <thead>
                    <th class="text-center">#</th>
                    <th class="text-center">ฟังก์ชัน</th>
                    <th class="text-center">แก้ไข</th>
                    <th class="text-center">ลบ</th>
                </thead>
                <tbody>
                    <?php $i = 0; ?>
                    @foreach ($notifys_to as $notify_to)
                    <?php $i++; ?>
                        <tr>
                            <td class="text-center">{{$i}}</td>
                            <td class="text-center">{{$notify_to->nt_name}}</td>
                            <td class="text-center">
                                <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#edit-function{{$notify_to->nt_id}}">แก้ไข</button>
                            </td>
                            <td class="text-center">
                                <form action="/admin/function-line" method="post">
                                    @csrf
                                    <input type="hidden" name="delete_id" value="{{$notify_to->nt_id}}">
                                    <button type="submit" class="btn btn-danger" onclick= "return confirm('ลบ?');">ลบ</button>
                                </form>
                            </td>
                        </tr>
                        <!-- Modal edit function -->
                        <div class="modal fade bd-example-modal-lg" id="edit-function{{$notify_to->nt_id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="panel panel-warning">
                                        <div class="panel-heading"><h5>แก้ไขฟังก์ชัน</h5></div>
                                    </div>
                                    <div class="modal-body">
                                        <div class="input-group mb-3 col-md-12">
                                            <div class="input-group-prepend">
                                                <form class="row" method="POST" action="/admin/function-line">
                                                    @csrf
                                                    <div class="form-group col-md-12" style="display:flex;">
                                                        <label for="type" style="width:30%;">ฟังก์ชัน</label>
                                                        <input class="form-control" type="hidden" name="edit_id" value="{{$notify_to->nt_id}}">
                                                        <input class="form-control" type="text" name="nt_name" value="{{$notify_to->nt_name}}" style="width:70%;">
                                                    </div>
                                                    <div class="form-group col-md-12">
                                                        <button type="submit" class="btn btn-success btn-sm btn-block">แก้ไขฟังก์ชัน</button>
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
                        <!-- Modal edit function -->
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
<!-- Modal add function -->
<div class="modal fade bd-example-modal-lg" id="functionAdd" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel panel-warning">
                <div class="panel-heading"><h5>เพิ่มฟังก์ชัน</h5></div>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3 col-md-12">
                    <div class="input-group-prepend">
                        <form class="row" method="POST" action="/admin/function-line">
                            @csrf
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">ฟังก์ชัน</label>
                                <input class="form-control" type="text" name="addnt_name" required autocomplete="off" style="width:70%;">
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success btn-sm btn-block">เพิ่มฟังก์ชัน</button>
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
<!-- Modal add function -->
@stop
