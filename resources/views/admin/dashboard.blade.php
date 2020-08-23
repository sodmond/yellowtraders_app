@extends('layouts.dark-theme')

<title>Dashboard</title>

@section('page-header')
    <h3>Dashboard</h3>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-warning card-header-icon">
              <div class="card-icon">
                <i class="material-icons">supervisor_account</i>
              </div>
              <p class="card-category">Total</p>
              <h3 class="card-title"><?php echo $all_investors; ?></h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons text-warning">calendar_today</i>
                <h5 class="primary-text">All Investors</h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-info card-header-icon">
              <div class="card-icon">
                <i class="material-icons">notifications</i>
              </div>
              <p class="card-category">Today / Total</p>
              <h3 class="card-title"><?php echo $payments_today.'/'.$payments_total; ?></h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons text-info">money</i>
                <h5 class="primary-text">Received Payments</h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-success card-header-icon">
              <div class="card-icon">
                <i class="material-icons">verified</i>
              </div>
              <p class="card-category">Active / Total</p>
              <h3 class="card-title"><?php echo $investment_active.'/'.$investment_total ?></h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons text-success">local_offer</i>
                <h5>Investments</h5>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6">
          <div class="card card-stats">
            <div class="card-header card-header-primary card-header-icon">
              <div class="card-icon">
                <i class="material-icons">today</i>
              </div>
              <p class="card-category">Active / Total</p>
              <h3 class="card-title"><?php echo $payouts_unconfirmed.'/'.$payouts_total ?></h3>
            </div>
            <div class="card-footer">
              <div class="stats">
                <i class="material-icons text-primary">update</i>
                <h5>Payouts</h5>
              </div>
            </div>
          </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>Payout List</h4></div>
                </div>
                <div class="card-body">
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
                                    <th>Monthy %</th>
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
                                        <form method="POST" action="{{ url('/admin/dashboard') }}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="payout_id" value="{{ $payout->id }}">
                                            <button type="submit" class="btn btn-info" style="padding:7px;">Confirm</button>
                                        </form>
                                        @else
                                        <button type="submit" class="btn btn-success" style="padding:7px;" onclick="javascript:void(0)">Paid</button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-center">
                        <a href="{{ url('/admin/payout_list') }}">
                            <button class="btn" style="background:#E2A921">View All</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
