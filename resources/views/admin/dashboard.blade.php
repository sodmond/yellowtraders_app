@extends('layouts.dark-theme')

<title>Dashboard</title>

@section('page-header')
    <h3>Dashboard</h3>
@endsection

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.css" integrity="sha512-SUJFImtiT87gVCOXl3aGC00zfDl6ggYAw5+oheJvRJ8KBXZrr/TMISSdVJ5bBarbQDRC2pR5Kto3xTR0kpZInA==" crossorigin="anonymous" />
<?php
#print_r($payoutChart[0]->weekday);
$curPayout = array("sun" => 0, "mon" => 0, "tue" => 0, "wed" => 0, "thu" => 0, "fri" => 0, "sat" => 0);
$weekTotalPayout = 0;
foreach ($payoutChart as $item) {
    $weekTotalPayout += $item->roi;
    switch ($item->weekday) {
        case 1:
            $curPayout["sun"] += $item->roi;
            break;
        case 2:
            $curPayout["mon"] += $item->roi;
            break;
        case 3:
            $curPayout["tue"] += $item->roi;
            break;
        case 4:
            $curPayout["wed"] += $item->roi;
            break;
        case 5:
            $curPayout["thu"] += $item->roi;
            break;
        case 6:
            $curPayout["fri"] += $item->roi;
            break;
        default:
            $curPayout["sat"] += $item->roi;
            break;
    }
}
$curPayoutChart = array(
    array("y" => $curPayout["sun"]),
    array("y" => $curPayout["mon"]),
    array("y" => $curPayout["tue"]),
    array("y" => $curPayout["wed"]),
    array("y" => $curPayout["thu"]),
    array("y" => $curPayout["fri"]),
    array("y" => $curPayout["sat"]),
);
$curPayin = array("sun" => 0, "mon" => 0, "tue" => 0, "wed" => 0, "thu" => 0, "fri" => 0, "sat" => 0);
$weekTotalPayin = 0;
foreach ($payinChart as $item) {
    $weekTotalPayin += $item->amount;
    switch ($item->weekday) {
        case 1:
            $curPayin["sun"] += $item->amount;
            break;
        case 2:
            $curPayin["mon"] += $item->amount;
            break;
        case 3:
            $curPayin["tue"] += $item->amount;
            break;
        case 4:
            $curPayin["wed"] += $item->amount;
            break;
        case 5:
            $curPayin["thu"] += $item->amount;
            break;
        case 6:
            $curPayin["fri"] += $item->amount;
            break;
        default:
            $curPayin["sat"] += $item->amount;
            break;
    }
}
$curPayinChart = array(
    array("y" => $curPayin["sun"]),
    array("y" => $curPayin["mon"]),
    array("y" => $curPayin["tue"]),
    array("y" => $curPayin["wed"]),
    array("y" => $curPayin["thu"]),
    array("y" => $curPayin["fri"]),
    array("y" => $curPayin["sat"]),
);
?>
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
              <p class="card-category">Pending / Total</p>
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
              <p class="card-category">Pending / Total</p>
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
        <div class="col-xl col-lg-12">
            <div class="card card-chart">
                <div class="card-header card-header-secondary">
                    <canvas id="payoutChart" width="400" height="270"></canvas>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Weekly Pay Outs</h4>
                    <p class="card-category">
                    <span class="text-danger"><i class="fa fa-long-arrow-up"></i> N{{ number_format($weekTotalPayout) }} </span> Total for this week.
                    </p>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">access_time</i> updated at {{ date("Y-m-d H:i:s") }}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg-12">
            <div class="card card-chart">
                <div class="card-header card-header-secondary">
                    <canvas id="payinChart" width="400" height="270"></canvas>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Weekly Pay In</h4>
                    <p class="card-category">
                    <span class="text-success"><i class="fa fa-long-arrow-down"></i>  N{{ number_format($weekTotalPayin) }} </span> Total for this week.
                    </p>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">access_time</i> updated at {{ date("Y-m-d H:i:s") }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div style="padding: 10px 0px;">
            <a href="{{ url('/admin/report_analysis') }}"><button class="btn btn-info">View Detailed Reports</button></a>
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
                                            @if(auth()->user()->role != 'cs-agent')
                                                <form method="POST" action="{{ url('/admin/dashboard') }}">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="payout_id" value="{{ $payout->id }}">
                                                    <button type="submit" class="btn btn-info" style="padding:7px;">Confirm</button>
                                                </form>
                                            @endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha512-s+xg36jbIujB2S2VKfpGmlC3T5V2TF3lY48DX7u2r9XzGzgPsa6wTpOQA7J9iffvdeBN0q9tKzRxVxw1JviZPg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js" integrity="sha512-vBmx0N/uQOXznm/Nbkp7h0P1RfLSj0HQrFSzV8m7rOGyj30fYAOKHYvCNez+yM8IrfnW0TCodDEjRqf6fodf/Q==" crossorigin="anonymous"></script>
<script>
    // Pay Out
    var payout = document.getElementById('payoutChart');
    var myChart = new Chart(payout, {
        type: 'bar',
        data: {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                label: 'NGN',
                data: <?php echo json_encode($curPayoutChart, JSON_NUMERIC_CHECK); ?>,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(128, 39, 161, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(128, 39, 161, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
    // Pay In
    var payin = document.getElementById('payinChart');
    var myChart = new Chart(payin, {
        type: 'bar',
        data: {
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
                label: 'NGN',
                data: <?php echo json_encode($curPayinChart, JSON_NUMERIC_CHECK); ?>,
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(128, 39, 161, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(128, 39, 161, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
</script>

@endsection
