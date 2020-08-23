@extends('layouts.dark-theme')

<title>Received Payments</title>

@section('page-header')
    <h3>Received Payments</h3>
@endsection

@section('content')
<style type="text/css">
    .btn{
        padding: 10px;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>Unconfirmed Payments</h4></div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Log ID</th>
                                    <th>Trader ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:14px; font-weight:100;">
                                @foreach($r_pay as $pay)
                                <tr>
                                    <td>{{ $pay->investment_log_id }}</td>
                                    <td>{{ strtoupper($pay->trader_id) }}</td>
                                    <td>{{ $pay->investment_type }}</td>
                                    <td>{{ $pay->amount }}</td>
                                    <td>{{ $pay->created_at }}</td>
                                    <td>
                                        <a href="{{ url('/admin/payments/'.$pay->id) }}"><button class="btn btn-info" style="padding:7px;">View</button></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-center">{{ $r_pay->links() }}</div>
                    <?php
                    #$user = auth()->user()->username;
                    #print_r($r_pay);
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>All Payments</h4></div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Log ID</th>
                                    <th>Trader ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Admin</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:14px; font-weight:100;">
                                @foreach($all_pay as $pay)
                                <tr>
                                    <td>{{ $pay->investment_log_id }}</td>
                                    <td>{{ strtoupper($pay->trader_id) }}</td>
                                    <td>{{ $pay->investment_type }}</td>
                                    <td>{{ $pay->amount }}</td>
                                    <td>{{ $pay->created_at }}</td>
                                    <td>
                                        <a href="{{ url('/admin/payments/'.$pay->id) }}"><button class="btn btn-info" style="padding:7px;">View</button></a>
                                    </td>
                                    <td>{{ $pay->admin }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-center">{{ $all_pay->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
