@extends('adminlte::page')

@section('title', 'Digitalmerce || ลงงาน')

@section('content_header')
@stop

@section('content')

@php
$user = array();
$job = array();
$count_emp = 0;
foreach ($emps as $emp) {
    $count = 0;
    foreach ($backlink_details as $backlink_detail) {
        if ($backlink_detail->UID == $emp->id) {
            $count = $count + $backlink_detail->c_job;
        }
        else {
            $count = $count + 0;
        }
    }
    foreach ($content_details as $content_detail) {
        if ($content_detail->UID == $emp->id) {
            $count = $count + $content_detail->c_job;
        }
        else {
            $count = $count + 0;
        }
    }
    foreach ($graphic_details as $graphic_detail) {
        if ($graphic_detail->UID == $emp->id) {
            $count = $count + $graphic_detail->c_job;
        }
        else {
            $count = $count + 0;
        }
    }
    foreach ($programmer_details as $programmer_detail) {
        if ($programmer_detail->UID == $emp->id) {
            $count = $count + $programmer_detail->c_job;
        }
        else {
            $count = $count + 0;
        }
    }
    foreach ($social_details as $social_detail) {
        if ($social_detail->UID == $emp->id) {
            $count = $count + $social_detail->c_job;
        }
        else {
            $count = $count + 0;
        }
    }
    foreach ($page_costs as $page_cost) {
        if ($page_cost->emp_id == $emp->id) {
            $count = $count + $page_cost->c_job;
        }
        else {
            $count = $count + 0;
        }
    }
    if ($count != 0) {
        array_push($user, $emp->nickname);
        array_push($job, $count);
        $count_emp++;
    }
}
$dataPoints = array();
for ($i = 0; $i < $count_emp ; $i++) {
    $dataPoints1 = array("label"=>$user[$i], "y"=>$job[$i]);
    array_push($dataPoints, $dataPoints1);
}
@endphp

<div id="chartContainer" style="height: 500px; width: 100%;"></div>

<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
<script>
window.onload = function () {
    CanvasJS.addColorSet("greenShades",
    [//colorSet Array
    "#2F4F4F",
    "#008080",
    "#2E8B57",
    "#3CB371",
    "#90EE90"
    ]);
    var chart = new CanvasJS.Chart("chartContainer", {
        animationEnabled: true,
        colorSet: "greenShades",
        theme: "dark1", // "light1", "light2", "dark1", "dark2"
        title: {
            text: "จำนวนงานประจำเดือนนี้"
        },
        axisY: {
            title: "จำนวนงาน",
            includeZero: false
        },
        axisX: {
            title: "ชื่อพนักงาน",
            labelFontColor: "tranparent",
        },
        data: [{
            type: "column",
            indexLabel: "{label}",
            dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
        }]
    });
    chart.render();
}
</script>
@stop
