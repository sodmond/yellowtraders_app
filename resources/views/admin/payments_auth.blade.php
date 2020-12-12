@extends('layouts.dark-theme')

<title>Single Payment Page</title>

@section('page-header')
    <h3>Single Payment Page</h3>
@endsection

@section('content')
<style type="text/css">
    .btn{
        padding: 10px;
    }
</style>
<?php
$getTrader = App\Traders::where('trader_id', $payment->trader_id)->first();
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>Single Payment</h4></div>
                </div>

                <div class="card-body">
                    <div>
                        <a href="{{url('/admin/payments')}}">
                            <button class="btn btn-secondary">&crarr; Back to Payment Notifications</button>
                        </a>
                    </div>
                    @isset($_GET['msg'])
                        @if ($_GET['msg'] == 'success')
                            <div class="alert alert-success">Payment Confirmed Successfully!</div>
                        @endif
                        @if ($_GET['msg'] == 'error')
                            <div class="alert alert-danger">Payment has been rejected!</div>
                        @endif
                    @endisset
                    <div class="table-responsive">
                        <table class="table">
                            <tbody style="font-size:15px; font-weight:100;">
                                <tr>
                                    <td><strong>Log ID</strong></td>
                                    <td>{{ $payment->investment_log_id }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Trader ID</strong></td>
                                    <td><a href="{{ url('/admin/trader_profile/'.$getTrader->id) }}" style="color:#E2A921; text-decoration:underline;">
                                        {{ strtoupper($payment->trader_id) }}
                                    </a></td>
                                </tr>
                                <tr>
                                    <td><strong>Full Name</strong></td>
                                    <td>{{ strtoupper($payment->full_name) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Bank Name</strong></td>
                                    <td>{{ ucwords($payment->bank_name) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Number</strong></td>
                                    <td>{{ ucwords($payment->account_number) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Investment Type</strong></td>
                                    <td>{{ $payment->investment_type }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount</strong></td>
                                    <td>&#8358;{{ number_format($payment->amount) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Date</strong></td>
                                    <td>{{ $payment->created_at }}</td>
                                </tr>
                            </tbody>
                        </table>
                        {{-- print_r($payment) --}}
                    </div>
                    <div class="row">
                        <div class="col-md" style="padding: 15px 10px; text-align:center;">
                            <a href="{{ asset('storage/'.$payment->payment_proof) }}" target="_blank">
                                <img class="img-fluid" src="{{ asset('storage/'.$payment->payment_proof) }}">
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            @if ($payment->status == 1 && auth()->user()->role != 'cs-agent')
                            <form method="POST" action="{{ url('/admin/payments') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="payId" value="{{$payment->id}}">
                                <input type="hidden" name="logId" value="{{$payment->investment_log_id}}">
                                <input type="hidden" name="inv_type" value="{{$payment->investment_type}}">
                                <input type="hidden" name="authType" value="reject">
                                <button class="btn btn-danger">Reject</button>
                            </form>
                            @endif
                        </div>
                        <div class="col-md" style="text-align:center">
                            @if ($payment->status == 2)
                            <button class="btn btn-success" onclick="javascript:void(0)">Payment Confirmed</button>
                            @endif
                            @if ($payment->status == 0)
                                <button class="btn btn-danger" onclick="javascript:void(0)">Payment Rejected</button>
                            @endif
                        </div>
                        <div class="col-md" style="text-align:right;">
                            @if ($payment->status == 1 && auth()->user()->role != 'cs-agent')
                            <form class="form-inline" method="POST" action="{{ url('/admin/payments') }}">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="payId" value="{{$payment->id}}">
                                <input type="hidden" name="logId" value="{{$payment->investment_log_id}}">
                                <input type="hidden" name="inv_type" value="{{$payment->investment_type}}">
                                <input type="date" class="form-control" name="start_date" id="start_date" required>
                                <input type="hidden" name="authType" value="confirm">
                                <button type="submit" id="tSubBtn" class="btn btn-success">Confirm</button>
                                <span id="daates" class="text-warning"></span>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
    $(document).ready(function() {
        var today = new Date();
        $('#tSubBtn').prop("disabled", true);
        $('#start_date').change(function(){
            var nDate = $('#start_date').val();
            var sDate = new Date(nDate);
            var timeDiff = today.getTime() - sDate.getTime();
            var daysDiff = timeDiff / (1000 * 3600 * 24);
            var numDays = Math.round(daysDiff);
            if (numDays < 21 && numDays >= 0) {
                $('#tSubBtn').prop("disabled", false);
                $('#daates').html("");
            }
            if (numDays > 21) {
                $('#tSubBtn').prop("disabled", true);
                $('#daates').html("<strong>Warning!</strong> You can't backdate more than 3 weeks");
            }
            if (numDays < 0) {
                $('#tSubBtn').prop("disabled", true);
                $('#daates').html("<strong>Warning!</strong> You can't select a date in the future");
            }
        });
    });
</script>
@endsection
