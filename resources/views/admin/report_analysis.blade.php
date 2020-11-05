@extends('layouts.dark-theme')

<title>Report Analysis</title>

@section('page-header')
    <h3>Report Analysis</h3>
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
        <div class="col-md">
            <div class="row">
                <div class="col-md">
                    <div class="card card-chart">
                        <div class="card-header card-header-secondary">
                            <canvas id="allPayChart" width="400" height="270"></canvas>
                        </div>
                        <div class="card-body">
                            <h3 class="card-title">All Time</h3>
                            <p class="card-category">
                                <div style="font-size:15px;"><span class="text-danger"><i class="fa fa-long-arrow-up"></i>  &#8358;{{ number_format($sumPayout) }} </span> Total Paid Out.</div>
                                <div style="font-size:15px;"><span class="text-success"><i class="fa fa-long-arrow-down"></i>  &#8358;{{ number_format($sumPayin) }} </span> Total Paid In.</div>
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
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
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
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
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
            </div>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
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
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
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
        </div>
        <div class="col-md">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4><i class="fa fa-star-o"></i> Active Top Earners</h4></div>
                    <?php
                    $topEarners = App\Investments::where('status', 2)
                                ->leftJoin('traders', 'investments.trader_id', '=', 'traders.trader_id')
                                ->select('investments.trader_id', 'investments.monthly_roi', 'traders.id', 'traders.full_name', 'traders.image')
                                ->orderBy('investments.monthly_roi', 'desc')
                                ->take(10)
                                ->get();
                    ?>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody style="font-size: 14px;">
                                @foreach($topEarners as $earner)
                                <tr>
                                    <td><img class="img" src="{{ asset('storage/'.$earner->image)}}" style="width:50px; border-radius:50px;"></td>
                                    <td>
                                        <h5><a href="trader_profile/{{$earner->id}}">{{ ucwords($earner->full_name) }}</a></h5>
                                        <div>{{ strtoupper($earner->trader_id) }}</div>
                                        <div class="text-success">&#8358;{{ number_format($earner->monthly_roi) }}</div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center" style="margin-bottom: -30px;">
        <div class="col-md">
            <div class="card">
                <div class="card-body" style="text-align: center;">
                    <span style="color:#fafafa;">Filter Weekly Report: </span>
                    <input type="date" id="weekReportDate">
                    <button class="btn btn-warning" style="padding:6px 10px;" onclick="location.reload();">Reset</button>
                    <div id="loader" style="display:none;">
                        <img src="{{ asset('images/loader2.gif') }}" alt="loading..." style="width:15px;"> Loading...
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xl col-lg-12">
            <div class="card card-chart">
                <div class="card-header card-header-secondary">
                    <div id="payoutChtCntnr"><canvas id="payoutChart" width="400" height="270"></canvas></div>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Weekly Pay Outs</h4>
                    <p class="card-category">
                    <span class="text-danger" id="twPayout"><i class="fa fa-long-arrow-up"></i> &#8358;{{ number_format($weekTotalPayout) }} </span> Total.
                    </p>
                </div>
                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">access_time</i> updated at <span id="payNDate">{{ date("Y-m-d H:i:s") }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl col-lg-12">
            <div class="card card-chart">
                <div class="card-header card-header-secondary">
                    <div id="payinChtCntnr"><canvas id="payinChart" width="400" height="270"></canvas></div>
                </div>
                <div class="card-body">
                    <h4 class="card-title">Weekly Pay In</h4>
                    <p class="card-category">
                    <span class="text-success" id="twPayin"><i class="fa fa-long-arrow-down"></i>  &#8358;{{ number_format($weekTotalPayin) }} </span> Total.
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

    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921">
                    <div class="card-title" style="font-weight:500;"><h4><i class="fa fa-star-o"></i> Monthly Report</h4></div>
                </div>
                <?php
                function mnthPay($input, $amount)
                {
                    $result = [0, 0, 0];
                    foreach ($input as $val) {
                        switch ($val->trader_type) {
                            case 1:
                                $result[0] += $val->$amount;
                                break;
                            case 2:
                                $result[1] += $val->$amount;
                                break;
                            default:
                                $result[2] += $val->$amount;
                                break;
                        }
                    }
                    return $result;
                }
                ?>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md" style="text-align:center; border-bottom:1px solid #7D8C98; margin-bottom:20px; padding-bottom:20px;">
                            <span style="color:#fafafa;">Filter Report: </span>
                            <form id="monthFilter">
                                <select id="filterMonth" style="border:2px solid #E2A921;" required>
                                    <option value="">- Select Month: -</option>
                                    <option value="01">JAN</option>
                                    <option value="02">FEB</option>
                                    <option value="03">MAR</option>
                                    <option value="04">APR</option>
                                    <option value="05">MAY</option>
                                    <option value="06">JUN</option>
                                    <option value="07">JUL</option>
                                    <option value="08">AUG</option>
                                    <option value="09">SEP</option>
                                    <option value="10">OCT</option>
                                    <option value="11">NOV</option>
                                    <option value="12">DEC</option>
                                </select>
                                <select id="filterYear" style="border:2px solid #E2A921;" required>
                                    <option value="">- Select Year -</option>
                                    <?php $curYear = date('Y'); while ($curYear >= 2018) {
                                        echo '<option value="'.$curYear.'">'.$curYear.'</option>';
                                        $curYear--;
                                    }
                                    ?>
                                </select>
                                <button type="submit" class="btn btn-info" style="padding:6px 10px;">Filter</button>
                                <button class="btn btn-warning" style="padding:6px 10px;" onclick="location.reload();">Reset</button>
                            </form>
                            <div id="mLoader" style="display:none;">
                                <img src="{{ asset('images/loader2.gif') }}" alt="loading..." style="width:15px;"> Loading...
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md">
                            <h4 class="card-title">Pay Out</h4>
                            <div id="mnthPayout"><canvas id="mnthPayoutChart" width="400" height="270"></canvas></div>
                            <p class="card-category">
                                <span class="text-danger" id="tMPayout"><i class="fa fa-long-arrow-up"></i>  &#8358;{{ number_format(array_sum(mnthPay($curMPout, 'roi'))) }} </span> Total.
                            </p>
                        </div>
                        <div class="col-md">
                            <h4 class="card-title">Pay In</h4>
                            <div id="mnthPayin"><canvas id="mnthPayinChart" width="400" height="270"></canvas></div>
                            <p class="card-category">
                                <span class="text-success" id="tMPayin"><i class="fa fa-long-arrow-down"></i>  &#8358;{{ number_format(array_sum(mnthPay($curMPin, 'amount'))) }} </span> Total.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="stats">
                        <i class="material-icons">access_time</i> updated at {{ date("Y-m-d H:i:s") }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.min.js" integrity="sha512-s+xg36jbIujB2S2VKfpGmlC3T5V2TF3lY48DX7u2r9XzGzgPsa6wTpOQA7J9iffvdeBN0q9tKzRxVxw1JviZPg==" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.bundle.min.js" integrity="sha512-vBmx0N/uQOXznm/Nbkp7h0P1RfLSj0HQrFSzV8m7rOGyj30fYAOKHYvCNez+yM8IrfnW0TCodDEjRqf6fodf/Q==" crossorigin="anonymous"></script>
<script>
    // All Pays
    var allPay = document.getElementById('allPayChart');
    var allPayChart = new Chart(allPay, {
        type: 'pie',
        data: {
            labels: ['Paid Outs', 'Paid in'],
            datasets: [{
                label: 'NGN',
                data: <?php echo json_encode([$sumPayout, $sumPayin], JSON_NUMERIC_CHECK); ?>,
                backgroundColor: [
                    'rgba(255, 0, 0, 0.2)',
                    'rgba(50, 205, 50, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1,
                hoverBorderColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(75, 192, 192, 0.7)'
                ]
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
    var payoutList = <?php echo json_encode($curPayoutChart, JSON_NUMERIC_CHECK); ?>;
    var payinList = <?php echo json_encode($curPayinChart, JSON_NUMERIC_CHECK); ?>;
    weeklyReport(payoutList, payinList, "payoutChart", "payinChart");
    $('#weekReportDate').change(function(){
        $('#loader').css('display', 'block');
        var newDate = $('#weekReportDate').val();
        //alert("New date is " + newDate);
        $.ajax({
            type: 'get',
            url: 'report_analysis/'+newDate,
            dataType: 'json',
            success:function(data) {
                if ($.isEmptyObject(data.error)){
                    $('#payoutChtCntnr').html('<canvas id="newPayoutChart" width="400" height="270"></canvas>');
                    $('#payinChtCntnr').html('<canvas id="newPayinChart" width="400" height="270"></canvas>');
                    payoutList = getWeeknTotalPayout(data.payoutChart);
                    payinList = getWeeknTotalPayin(data.payinChart);
                    weeklyReport(payoutList[0], payinList[0], "newPayoutChart", "newPayinChart");
                    //alert(payinList[0]);
                    //alert(payinList[1][0]);
                    nfObject = new Intl.NumberFormat('en-US')
                    $('#twPayout').html('<i class="fa fa-long-arrow-up"></i> &#8358;' + nfObject.format(payoutList[1][0]));
                    $('#twPayin').html('<i class="fa fa-long-arrow-down"></i> &#8358;' + nfObject.format(payinList[1][0]));
                    $('#loader').css('display', 'none');
                }else{
                    alert("No record found for the week of selected date!");
                }
            }
        });
    });

    function weeklyReport(payoutList, payinList, pOutChart, pInChart){
        // Pay Out
        var payout = document.getElementById(pOutChart);
        var payoutChart = new Chart(payout, {
            type: 'bar',
            data: {
                labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                datasets: [{
                    label: 'NGN',
                    data: payoutList,
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
        var payin = document.getElementById(pInChart);
        var payinChart = new Chart(payin, {
            type: 'bar',
            data: {
                labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                datasets: [{
                    label: 'NGN',
                    data: payinList,
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
    }
    function getWeeknTotalPayout(obj){
        var result = [
            [0, 0, 0, 0, 0, 0, 0],
            [0]
        ]
        Object.values(obj).forEach(val => {
            result[1][0] += val.roi
            switch(val.weekday){
                case 1: result[0][0] += val.roi;
                break;
                case 2: result[0][1] += val.roi;
                break;
                case 3: result[0][2] += val.roi;
                break;
                case 4: result[0][3] += val.roi;
                break;
                case 5: result[0][4] += val.roi;
                break;
                case 5: result[0][5] += val.roi;
                break;
                default: result[0][6] += val.roi;
                break;
            }
        });
        return result;
    }
    function getWeeknTotalPayin(obj){
        var result = [
            [0, 0, 0, 0, 0, 0, 0],
            [0]
        ]
        Object.values(obj).forEach(val => {
            result[1][0] += val.amount
            switch(val.weekday){
                case 1: result[0][0] += val.amount;
                break;
                case 2: result[0][1] += val.amount;
                break;
                case 3: result[0][2] += val.amount;
                break;
                case 4: result[0][3] += val.amount;
                break;
                case 5: result[0][4] += val.amount;
                break;
                case 5: result[0][5] += val.amount;
                break;
                default: result[0][6] += val.amount;
                break;
            }
        });
        return result;
    }

    /***      Monthly Analysis    ***/
    let curMPout = <?php echo json_encode(mnthPay($curMPout, 'roi'), JSON_NUMERIC_CHECK); ?>;
    let curMPin = <?php echo json_encode(mnthPay($curMPin, 'amount'), JSON_NUMERIC_CHECK); ?>;
    monthPay(curMPout, curMPin, 'mnthPayoutChart','mnthPayinChart');
    $('#monthFilter').submit(function(event){
        event.preventDefault();
        $('#mLoader').css('display', 'block');
        var filterMonth = $('#filterMonth').val();
        var filterYear = $('#filterYear').val();
        //alert("New date is " + newDate);
        $.ajax({
            type: 'get',
            url: 'report_month/'+filterMonth+'/'+filterYear,
            dataType: 'json',
            success:function(data) {
                if ($.isEmptyObject(data.error)){
                    $('#mnthPayout').html('<canvas id="newPoutMonth" width="400" height="270"></canvas>');
                    $('#mnthPayin').html('<canvas id="newPinMonth" width="400" height="270"></canvas>');
                    mPayout = getMonthnTotalPayout(data.curMPout);
                    mPayin = getMonthnTotalPayin(data.curMPin);
                    monthPay(mPayout[0], mPayin[0], "newPoutMonth", "newPinMonth");
                    //alert(payinList[0]);
                    //alert(payinList[1][0]);
                    nfObject = new Intl.NumberFormat('en-US')
                    $('#tMPayout').html('<i class="fa fa-long-arrow-up"></i> &#8358;' + nfObject.format(mPayout[1][0]));
                    $('#tMPayin').html('<i class="fa fa-long-arrow-down"></i> &#8358;' + nfObject.format(mPayin[1][0]));
                    $('#mLoader').css('display', 'none');
                }else{
                    alert("No record found for the week of selected date!");
                }
            }
        });
    });
    function monthPay(mPayout, mPayin, mPoutChart, mPinChart){
        // Payout
        var payout = document.getElementById(mPoutChart);
        var payoutChart = new Chart(payout, {
            type: 'doughnut',
            data: {
                labels: ['Yellow Traders', 'Junior Traders', 'Corporate'],
                datasets: [{
                    label: 'NGN',
                    data: mPayout,
                    backgroundColor: [
                        'rgba(255, 255, 0, 0.2)',
                        'rgba(50, 205, 50, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(226, 169, 33, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1,
                    hoverBorderColor: [
                        'rgba(226, 169, 33, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(54, 162, 235, 0.7)'
                    ]
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
        // Payin
        var payin = document.getElementById(mPinChart);
        var payinChart = new Chart(payin, {
            type: 'doughnut',
            data: {
                labels: ['Yellow Traders', 'Junior Traders', 'Corporate'],
                datasets: [{
                    label: 'NGN',
                    data: mPayin,
                    backgroundColor: [
                        'rgba(255, 255, 0, 0.2)',
                        'rgba(50, 205, 50, 0.2)',
                        'rgba(54, 162, 235, 0.2)'
                    ],
                    borderColor: [
                        'rgba(226, 169, 33, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)'
                    ],
                    borderWidth: 1,
                    hoverBorderColor: [
                        'rgba(226, 169, 33, 0.7)',
                        'rgba(75, 192, 192, 0.7)',
                        'rgba(54, 162, 235, 0.7)'
                    ]
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
    }
    function getMonthnTotalPayout(obj){
        var result = [
            [0, 0, 0],
            [0]
        ]
        Object.values(obj).forEach(val => {
            result[1][0] += val.roi
            switch(val.trader_type){
                case 1: result[0][0] += val.roi;
                break;
                case 2: result[0][1] += val.roi;
                break;
                default: result[0][2] += val.roi;
                break;
            }
        });
        return result;
    }
    function getMonthnTotalPayin(obj){
        var result = [
            [0, 0, 0],
            [0]
        ]
        Object.values(obj).forEach(val => {
            result[1][0] += val.amount
            switch(val.trader_type){
                case 1: result[0][0] += val.amount;
                break;
                case 2: result[0][1] += val.amount;
                break;
                default: result[0][2] += val.amount;
                break;
            }
        });
        return result;
    }
</script>

@endsection
