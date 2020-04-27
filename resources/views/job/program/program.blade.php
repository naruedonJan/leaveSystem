@extends('adminlte::page')
@section('css')
<!-- fullCalendar -->
<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/fullcalendar/dist/fullcalendar.min.css">
<link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">
<style>
.fc-title{
    font-size: 18px;
}
</style>
@endsection

@section('title', 'Digitalmerce || รายการ')

@section('content_header')

@stop

@section('content')

    <div class="panel panel-primary">
        <div class="panel-heading"><h4>รายการ</h4></div>
        <div class="panel-body">
            <div class="col-md-12" id="Calendar">
                <!-- THE CALENDAR -->
                {!! $calendar->calendar() !!}
            </div>
        </div>
    </div>

@stop

@section('js')
<!-- fullCalendar -->
<script src="https://adminlte.io/themes/AdminLTE/bower_components/moment/moment.js"></script>
<script src="https://adminlte.io/themes/AdminLTE/bower_components/fullcalendar/dist/fullcalendar.min.js"></script>

<script src="{{ asset('js/locale/th.js') }}"></script>
{!! $calendar->script()!!}

@endsection
