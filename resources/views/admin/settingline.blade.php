@extends('adminlte::page')

@section('title', 'Digitalmerce || Line')

@section('content')
<div class="row">
    <div class="col-sm-8">
    <form action="/admin/savedataline" method="POST">
        @csrf
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h4>Line Login Setting</h4>
            </div>
            <div class="panel-body">
                <label for="type" style="width:30%;">CLIENT ID</label>
                <input class="form-control" name="CLIENT_ID" value="{{$dataline->LO_CLIENT_ID}}">
                <label for="type" style="width:30%;">CLIENT SECRET</label>
                <input class="form-control" name="CLIENT_SECRET" value="{{$dataline->LO_CLIENT_SECRET}}">
                <label for="type" style="width:30%;">CALLBACK URI</label>
                <div class="input-group" style=" margin-bottom: 10px; ">
                    <input type="url" class="form-control" placeholder="https://example.com/"
                        aria-describedby="basic-addon2" name="CALLBACK_URI" value="{{$dataline->LO_CALLBACK_URI}}">
                    <span class="input-group-addon" id="basic-addon2">chatbot/callbackdata</span>
                </div>
                <button type="submit" class="btn btn-success btn-sm btn-block">บันทึก</button>
            </div>
        </div>
        <div class="panel panel-warning">
            <div class="panel-heading">
                <h4>Line Bot Setting</h4>
            </div>
            <div class="panel-body">
                <label for="type" style="width:30%;">Token</label>
                <input class="form-control" name="TOKEN" value="{{$dataline->LB_TOKEN}}" style=" margin-bottom: 10px; ">
                <button type="submit" class="btn btn-success btn-sm btn-block mb-2">บันทึก</button>
            </div>
        </div>
    </form>
</div>
<div class="col-sm-4">
    <div class="panel panel-warning">
        <div class="panel-heading">
            <h4>เชื่อมต่อการแจ้งตอนกับบอท Line Bot</h4>
        </div>
        <div class="panel-body">
            @if($setline !== 'OK')
                <a href="https://access.line.me/oauth2/v2.1/authorize?response_type=code&client_id={{$dataline->LO_CLIENT_ID}}&redirect_uri={{urlencode(($dataline->LO_CALLBACK_URI).'chatbot/callbackdata')}}&bot_prompt=aggressive&scope=profile&state={{Auth::user()->name}}" class="btn btn-success col-md-2" style="width: 100% !important; display: flex; justify-content: center; align-items: center;">เชื่อมต่อ Line</a>
            @else
                <a href="/chatbot/disconnecttline?emp_id={{ Auth::user()->name }}" class="btn btn-success col-md-2" style="width: 100% !important; display: flex; justify-content: center; align-items: center;">ยกเลิกเชื่อต่อ Line</a>
            @endif
        </div>
    </div>
</div>
</div>


@stop