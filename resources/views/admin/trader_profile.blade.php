@extends('layouts.dark-theme')

<title>Trader Profile</title>

@section('page-header')
    <h3>Trader Profile</h3>
@endsection

@section('content')
<?php
$trader_type = DB::table('trader_types')->where('id', $trader->trader_type)->value('name');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-profile">
                <div class="card-avatar">
                    <img class="img" src="{{ asset('storage/'.$trader->image)}}">
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ strtoupper($trader->full_name) }}</h4>
                    <h5 class="card-category">Trader ID: <strong>{{ strtoupper($trader->trader_id) }}</strong></h5>
                    <p class="card-description">Account type is a <strong>{{ ucwords($trader_type) }} Trader</strong></p>
                    <div>
                        @if(auth()->user()->role == 'superuser')
                        <a href="{{ url('/admin/edit_trader/'.$trader->id) }}" target="_blank">
                            <button class="btn btn-success">Edit</button>
                        </a>
                        <a href="{{ url('/admin/delete_trader/'.$trader->id) }}">
                            <button class="btn btn-danger">Delete</button>
                        </a>
                        @endif
                        <a href="{{ url('/admin/preview_mou/'.$inv->id) }}" target="_blank"><button class="btn btn-primary">Preview MOU</button></a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>Bank Account Info</h4></div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" style="font-size:14px;">
                            <tbody>
                                <tr>
                                    {{-- <td>{{ print_r($bank) }}</td> --}}
                                    <td><strong>Bank Name</strong></td>
                                    <td>{{ strtoupper($bank->bank_name) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Holder's Name</strong></td>
                                    <td>{{ ucwords($bank->holder_name) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Number</strong></td>
                                    <td>{{ str_pad($bank->account_number, 10, 0, STR_PAD_LEFT) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>Personal Details</h4></div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody style="font-size:15px;">
                                <tr>
                                    <td><strong>Full Name</strong></td>
                                    <td>{{ ucwords($trader->full_name) }}</td>
                                </tr>
                                @if ($trader->trader_type != 3)
                                <tr>
                                    <td><strong>Marital Status</strong></td>
                                    <td>{{ ucwords($trader->marital_status) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender</strong></td>
                                    <td>{{ ucwords($trader->gender) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    @if ($trader->trader_type == 3)
                                    <td><strong>Date of Incorporation</strong></td>
                                    @else
                                    <td><strong>Date of Birth</strong></td>
                                    @endif

                                    <td>{{ $trader->dob }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address</strong></td>
                                    <td>{{ $trader->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nationality</strong></td>
                                    <td>{{ ucwords($trader->nationality) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>State</strong></td>
                                    <td>{{ ucwords($trader->state) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>City</strong></td>
                                    <td>{{ ucwords($trader->lga) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td>{{ strtolower($trader->email) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Phone</strong></td>
                                    <td>0{{ $trader->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Other Phone</strong></td>
                                    <td>{{ $trader->other_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{NOK / Parent / Rep} Name</strong></td>
                                    <td>{{ ucwords($trader->contact_name) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{NOK / Parent / Rep} Phone</strong></td>
                                    <td>0{{ $trader->contact_phone }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>Investment Details</h4></div>
                </div>

                <div class="card-body">
                    <h3 class="text-success">Active Investment</h3>
                    @if ($inv->status == 2)
                    <div class="table-responsive">
                        <table class="table">
                            <thead style="font-size:15px;">
                                <th>Amount</th>
                                <th>Amount in Words</th>
                                <th>Monthly ROI</th>
                                <th>Monthly %</th>
                                <th>Duration</th>
                                <th>Purpose</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                            </thead>
                            <tbody style="font-size:14px;">
                                <tr>
                                    <td>{{ $inv->amount }}</td>
                                    <td>{{ $inv->amount_in_words }}</td>
                                    <td>{{ $inv->monthly_roi }}</td>
                                    <td>{{ $inv->monthly_pcent }}</td>
                                    <td>{{ $inv->duration }}</td>
                                    <td>{{ $inv->purpose }}</td>
                                    <td>{{ $inv->start_date }}</td>
                                    <td>{{ $inv->end_date }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    @else
                        <p class="text-warning" style="text-align:center; font-style:italic;">No active investment</p>
                    @endif
                    <h3 class="text-danger">Investment History</h3>
                    <div class="table-responsive" style="max-height:500px; overflow-y:scroll;">
                        <table class="table">
                            <thead style="font-size:15px;">
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Amount in Words</th>
                                <th>Monthly ROI</th>
                                <th>Monthly %</th>
                                <th>Duration</th>
                                <th>Purpose</th>
                                <th>Created Date</th>
                            </thead>
                            <tbody style="font-size:14px;">
                                @foreach($invLog as $log)
                                <tr>
                                    <td>{{ $log->investment_type }}</td>
                                    <td>{{ $log->amount }}</td>
                                    <td>{{ $log->amount_in_words }}</td>
                                    <td>{{ $log->monthly_roi }}</td>
                                    <td>{{ $log->monthly_pcent }}</td>
                                    <td>{{ $log->duration }}</td>
                                    <td>{{ $log->purpose }}</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- print_r($invLog) --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
