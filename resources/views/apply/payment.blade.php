@extends('layouts.app')

<title>Payments Confirmation</title>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

@section('content')
<?php
$investment = DB::table('investment_logs')->where('investment_id', 103)->first();;
print_r($investment);
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header"><h3>Payment Confirmation</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="payment" enctype="multipart/form-data">
                        @if (count($errors))
                        <div class="alert alert-danger">
                            <strong>Whoops!</strong> There are some problems with your input.<br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                        @isset($suc_msg)
                            <div class="alert alert-success"><strong>Success!</strong> {{ $suc_msg }}</div>
                        @endisset
                        @isset($err_msg)
                            <div class="alert alert-danger"><strong>Error!</strong> {{ $err_msg }}</div>
                        @endisset
                        <div class="row">
                            <div class="col">
                                <p><strong>Note:</strong> Check your email for your transaction ID and trader ID. You can only use your transaction ID once.</p>
                                <p></p>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        @if (isset($_GET['trader_id']) && isset($_GET['trans_id']))
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="trans_num" class="required"><strong>Transaction ID:</strong></label>
                                <input type="text" class="form-control" id="trans_num" name="trans_num" value="{{ $_GET['trans_id'] }}" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="trader_num" class="required"><strong>Trader ID:</strong></label>
                                <input type="text" class="form-control" id="trader_num" name="trader_num" value="{{ $_GET['trader_id'] }}" readonly>
                            </div>
                        </div>
                        @else
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="trans_num" class="required"><strong>Transaction ID:</strong></label>
                                <input type="text" class="form-control" id="trans_num" name="trans_num" placeholder="trans#000000000" value="{{ old('trans_num') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="trader_num" class="required"><strong>Trader ID:</strong></label>
                                <input type="text" class="form-control" id="trader_num" name="trader_num" value="{{ old('trader_num') }}" required>
                            </div>
                        </div>
                        @endif
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="proof" class="required"><strong>Upload Payment Proof:</strong></label>
                                <input type="file" class="form-control form-control-file border" id="proof" name="proof" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col" style="text-align:center;">
                                <input type="submit" class="btn btn-warning" value="Submit">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
