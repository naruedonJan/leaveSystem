@extends('adminlte::page')

@section('title', 'Digitalmerce || Line')

@section('content')
<div class="col-sm-12">
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h4>จัดการแอดมิน<button type="button" class="btn btn-info" data-toggle="modal" data-target="#department"
                    style="float: right;">เพิ่มแอดมิน</button></h4>
        </div>
        <div class="panel-body">
            <table class="table table-light">
                <thead class="thead-light">
                    <tr>
                        <th style="width: 25px;">#</th>
                        <th>ชื่อ</th>
                        <th>ต่ำแหน่ง</th>
                        <th>Line Name</th>
                        <th style="width: 300px;"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                    $i = 1;
                    @endphp
                    @foreach($dataget as $dataUser)
                    <tr>
                        <td>{{$i}}</td>
                        <td>{{$dataUser->name}}</td>
                        @if($dataUser->department === 2)
                        <td>HR</td>
                        @else
                        <td>Boss</td>
                        @endif
                        <td>{{$dataUser->line_name}}</td>
                        <td style="display: flex;">
                            <button class="btn btn-primary" type="button" data-toggle="modal" data-target="#edit-admin{{$dataUser->id}}"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-warning" style="margin-left: 2px" type="button" data-toggle="modal" data-target="#edit-pass{{$dataUser->id}}"><i class="fa fa-key"></i></button>
                            <form action="/admin/deleteadmin" method="post" style="margin-left: 2px">
                                @csrf
                                <input type="hidden" name="id" value="{{$dataUser->id}}">
                                <button class="btn btn-danger" type="submit" onclick= "return confirm('ยืนยันการลบ?');"><i class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @php
                    $i++;
                    @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal add admin -->
<div class="modal fade bd-example-modal-lg" id="department" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h5>เพิ่มแอดมิน</h5>
                </div>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3 col-md-12">
                    <div class="input-group-prepend">
                        <form class="row" method="POST" action="/admin/addadmin">
                            @csrf
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">อีเมล์</label>
                                <input class="form-control" type="email" name="email" required autocomplete="off"
                                    style="width:70%;">
                            </div>
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">ชื่อ</label>
                                <input class="form-control" type="text" name="name" required autocomplete="off"
                                    style="width:70%;">
                            </div>
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">รหัสผ่าน</label>
                                <input class="form-control" type="password" name="password" required autocomplete="off"
                                    style="width:70%;">
                            </div>
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">ยืนยันรหัสผ่าน</label>
                                <input class="form-control" type="password" name="confirmpassword" required
                                    autocomplete="off" style="width:70%;">
                            </div>
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">ตำแหน่ง</label>
                                <select id="my-select" class="custom-select" name="rank">
                                    <option value="1">Boss</option>
                                    <option value="2">HR</option>
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success btn-sm btn-block">เพิ่มแอดมิน</button>
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

<!-- Modal Edit admin -->
@foreach($dataget as $dataUser)
<div class="modal fade bd-example-modal-lg" id="edit-admin{{$dataUser->id}}" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h5>แก้ไขแอดมิน</h5>
                </div>
            </div>
            <div class="modal-body">
                <div class="input-group mb-3 col-md-12">
                    <div class="input-group-prepend">
                        <form class="row" method="POST" action="/admin/editadmin">
                            @csrf
                            <input type="hidden" name="id" value="{{$dataUser->id}}">
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">อีเมล์</label>
                                <input class="form-control" type="email" name="email" required autocomplete="off" value="{{$dataUser->email}}"
                                    style="width:70%;">
                            </div>
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">ชื่อ</label>
                                <input class="form-control" type="text" name="name" required autocomplete="off" value="{{$dataUser->name}}"
                                    style="width:70%;">
                            </div>
                            <div class="form-group col-md-12" style="display:flex;">
                                <label for="type" style="width:30%;">ตำแหน่ง</label>
                                <select id="my-select" class="custom-select" name="rank">
                                    @if($dataUser->department === 2)
                                    <option value="1">Boss</option>
                                    <option value="2"selected>HR</option>
                                    @else
                                    <option value="1" selected>Boss</option>
                                    <option value="2">HR</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-success btn-sm btn-block">บันทึก</button>
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

<!-- Modal Edit Password -->
<div class="modal fade bd-example-modal-lg" id="edit-pass{{$dataUser->id}}" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        <h5>แก้ไขรหัสผ่าน</h5>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3 col-md-12">
                        <div class="input-group-prepend">
                            <form class="row" method="POST" action="/admin/editadminpassword">
                                @csrf
                                <input type="hidden" name="id" value="{{$dataUser->id}}">
                                <div class="form-group col-md-12" style="display:flex;">
                                    <label for="type" style="width:30%;">รหัสผ่านใหม่</label>
                                    <input class="form-control" type="password" name="newpass" required autocomplete="off"
                                        style="width:70%;">
                                </div>
                                <div class="form-group col-md-12" style="display:flex;">
                                    <label for="type" style="width:30%;">ยืนยันรหัสผ่านใหม่</label>
                                    <input class="form-control" type="password" name="comfirmnewpass" required autocomplete="off"
                                        style="width:70%;">
                                </div>
                                <div class="form-group col-md-12">
                                    <button type="submit" class="btn btn-success btn-sm btn-block">บันทึก</button>
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
@endforeach
@stop