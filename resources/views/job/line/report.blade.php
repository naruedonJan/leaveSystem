@extends('adminlte::page')

@section('title', 'Digitalmerce || แบ็คลิงค์')

@section('css')
  <!-- fullCalendar -->
  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/fullcalendar/dist/fullcalendar.min.css">
  <link rel="stylesheet" href="https://adminlte.io/themes/AdminLTE/bower_components/fullcalendar/dist/fullcalendar.print.min.css" media="print">

@endsection
@php
    use Carbon\Carbon;
@endphp


@section('content')
<div class="row">
    {{-- <div class="col-md-4">
        <div class="box box-success">
            <div class="box-body">
            </div>
        </div>
    </div> --}}
    <div class="col-md-8">
        <div class="box box-primary">
            <div class="box-body">
                <!-- THE CALENDAR -->
                {!! $calendar->calendar() !!}
            </div>
            <!-- /.box-body -->
        </div>
        <!-- /. box -->
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
