@extends('adminlte::page')

@section('title', 'Digitalmerce || แบ็คลิงค์')

@php
use Carbon\Carbon;

@endphp


@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading"><h4>รายการตรวจสอบ {{$thai_date_return}} @if(Carbon::now()->format('H:i') < '12:00') ช่วงเช้า @else ช่วงบ่าย @endif</h4></div>
        <div class="panel-body">
            <table class="table">
                 <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="30%" class="text-center">ชื่อ Line @</th>
                        <th width="20%" class="text-center">ลิ้งค์ตรวจสอบ</th>
                        <th class="text-center">ยืนสถานะ</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $i = 0;
                    @endphp

                    @foreach ($lineat as $line)
                        @php
                            $i++;
                        @endphp
                        <tr>
                            <th class="text-center">{{$i}}</th>
                            <td class="text-center">{{$line->name}}</td>
                            <td class="text-center">
                                <a href="//line.me/ti/p/{{$line->name}}" target="_blank">https://line.me/ti/p/{{$line->name}}</a>
                            </td>
                            <td class="text-center">
                                <a href="checked?line={{$line->id}}&status=1" class="btn btn-success">ปกติ</a>
                                <a href="checked?line={{$line->id}}&status=2" class="btn btn-danger">ปลิว</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@stop
