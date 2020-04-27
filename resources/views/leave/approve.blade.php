@extends('adminlte::page')

@section('title', 'Digitalmerce || อนุมัติการลา')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="panel panel-warning">
        <div class="panel-heading"><h4>อนุมัติการลา</h4></div>
        <div class="panel-body" style="overflow: auto;">
            <table class="table table-striped table-hover table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th class="text-center" width="3%">#</th>
                        <th class="text-center" width="5%">ชื่อ</th>
                        <th class="text-center" width="10%">วันที่ลา</th>
                        <th class="text-center" width="5%">ช่วงเวลา</th>
                        <th class="text-center" width="5%">ประเภท</th>
                        {{-- <th>จำนวนครั้ง</th> --}}
                        <th class="text-center" width="15%">เหตุผล</th>
                        <th  class="text-center"width="5%">เวลาที่ลงระบบ</th>
                        <th width="10%"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($events as $key => $event)
                        <tr id="{{ $event->id }}">
                            <td scope="row">{{ $key+$events->firstItem()}}</td>
                            <td class="text-center">{{ $event->name }}</td>
                            <td class="text-center">  @if($event->start_date == $event->end_date) {{ Carbon::parse($event->start_date)->format('d-M-Y') }} @else {{ Carbon::parse($event->start_date)->format('d-M-Y') }} ถึง {{ Carbon::parse($event->end_date)->format('d-M-Y') }} @endif </td>
                            <td class="text-center">@if($event->type == '1') ทั้งวัน @else ครึ่งวัน @endif</td>
                            <td class="text-center">{{ $event->leave_type }}</td>
                            {{-- <td>{{ $event->uid }}</td> --}}
                            <td class="text-center">{{ $event->note }}</td>
                            <td class="text-center">{{ $event->send_date }}</td>
                            <td class="text-center">
                                {{-- <button type="button" onclick="lvAllow({{$event->id}});" class="btn btn-success">อนุมัติ</button>
                                <button type="button" id="lv_disallow" class="btn btn-danger">ไม่อนุมัติ</button> --}}
                            @if ($event->status == 0)                                
                                <a href="/admin/approve/{{$event->id}}?status=allow" class='btn btn-success' style="margin-right: 5px;">อนุมัติ</a>
                                <a href="/admin/approve/{{$event->id}}?status=disallow" class='btn btn-danger'>ไม่อนุมัติ</a>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


@stop

@section('js')

<script>
    function lvAllow(id){
        console.log("successful, $this is now ");
        // console.dir($this);
        $this.closest("tr").remove();
    }
// $(document).ready(function() {

//     function lvAllow(id){
//         console.log(id);
//         // $("table tbody").find(id).each(function(){
//         //     if($(this).is(id)){
//         //         console.log(1);
//         //     }else{
//         //         console.log(0);
//         //     }
//         // });
//         // $.get( "/admin/approve/"+id+"?status=allow", function( data ) {
//         //     alert( "Load was performed." );
//         // });
//     }
// });
</script>

@endsection
