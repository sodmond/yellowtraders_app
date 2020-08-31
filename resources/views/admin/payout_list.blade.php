@extends('layouts.dark-theme')

<title>Payout List</title>

@section('page-header')
    <h3>Payout List</h3>
@endsection

@section('content')
<style type="text/css">
    .page-item.active > .page-link{
        background: #E2A921;
    }
    .form-control{
        border-bottom: 2px solid #E2A921;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>List of All Traders To Be Paid</h4></div>
                </div>
                <div class="card-body">
                    <div>
                        <a href="{{ url('/admin/payout_export') }}"><button class="btn">Export to CSV</button></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Trader ID</th>
                                    <th>Full Name</th>
                                    <th>Account#</th>
                                    <th>Bank Name</th>
                                    <th>Amount Invested</th>
                                    <th>Monthly ROI</th>
                                    <th>Monthly %</th>
                                    <th>Duration</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;">
                                @foreach($tradersToPay as $payout)
                                <tr>
                                    <td>{{ strtoupper($payout->trader_id) }}</td>
                                    <td>{{ ucwords($payout->full_name) }}</td>
                                    <td>{{ $payout->account_number }}</td>
                                    <td>{{ $payout->bank_name }}</td>
                                    <td>{{ $payout->amount }}</td>
                                    <td>{{ $payout->roi }}</td>
                                    <td>{{ $payout->monthly_pcent }}</td>
                                    <td>{{ $payout->duration }}</td>
                                    <td>{{ $payout->start_date }}</td>
                                    <td>{{ $payout->end_date }}</td>
                                    <td>
                                        @if($payout->status == 0)
                                        <form method="POST" action="{{ url('/admin/payout_list') }}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="payout_id" value="{{ $payout->id }}">
                                            <button type="submit" class="btn btn-info" style="padding:7px;">Confirm</button>
                                        </form>
                                        {{-- $payout->id --}}
                                        @else
                                        <button type="submit" class="btn btn-success" style="padding:7px;" onclick="javascript:void(0)">Paid</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-center">{{ $tradersToPay->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
