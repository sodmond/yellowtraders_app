@extends('layouts.dark-theme')

<title>Confirmed Payout List</title>

@section('page-header')
    <h3>Confirmed Payout List</h3>
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
        <div class="col-md">&nbsp;</div>
        <div class="col-md">
            <form class="navbar-form" id="searchPaymentForm">
                <input type="hidden" name="_token" id="searchToken" value="{{ csrf_token() }}">
                <div class="input-group no-border">
                    <input type="text" name="trader_id" class="form-control" placeholder="Enter trader id">
                    &nbsp; &nbsp; &nbsp; &nbsp;
                    <input type="date" name="payDate" class="form-control" placeholder="Select date">
                    <button type="submit" class="btn btn-default btn-round btn-just-icon" id="searchPayment">
                        <i class="material-icons">search</i>
                        <div class="ripple-container"></div>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div id="loader" style="text-align:center; margin:20px 0px; display:none;">
        <img src="{{ asset('images/loader2.gif') }}" alt="loading..." style="width:30px;">
        <br>Loading result...
    </div>

    <div class="row" id="searchResult" style="display: none;">
        <div class="col-md">
            <div class="card">
                <div class="card-header-warning" style="background:#E2A921;">
                    <div class="card-title" id="searchTitle" style="font-weight:500;"></div>
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
                                    <th>Monthly %</th>
                                    <th>Duration</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                    <th>Admin</th>
                                    <th>Paid Date</th>
                                </tr>
                            </thead>
                            <tbody id="resultTab" style="font-size:14px; font-weight:100;"></tbody>
                        </table>
                    </div>
                    <p id="resultList"></p>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>List of All Confirmed Payouts</h4></div>
                </div>
                <div class="card-body">
                    <div>
                        <a href="{{ url('/admin/payout_list') }}"><button class="btn btn-info">Unconfirmed Payouts</button></a>
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
                                    <th>Admin</th>
                                    <th>Paid Date</th>
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
                                                <form method="POST" action="{{ url('/admin/payout_list') }}">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="hidden" name="payout_id" value="{{ $payout->id }}">
                                                    <button type="submit" class="btn btn-info" style="padding:7px;">Confirm</button>
                                                </form>
                                            @endif
                                        {{-- $payout->id --}}
                                        @else
                                        <button class="btn btn-success" style="padding:7px;" onclick="javascript:void(0)">Paid</button>
                                        @endif
                                    </td>
                                    <td>{{ $payout->admin }}</td>
                                    <td>{{ $payout->updated_at }}</td>
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
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
    $("#searchResult").css('display', 'none');
    $('#searchPaymentForm').submit(function(event){
        event.preventDefault();
        $("#loader").css('display', 'block');
        s_trader_id = $("input[name=trader_id]").val();
        s_payDate = $("input[name=payDate]").val();
        token = $("#searchToken").val();

        $.ajax({
            url: "search_payouts",
            type: "POST",
            data: {_token:token, trader_id:s_trader_id, payDate:s_payDate},
            dataType: 'JSON',
            success: function(data){
                if ($.isEmptyObject(data.error) && data.paidTraders != ""){
                    //alert("success");
                    let record = JSON.stringify(data.paidTraders);
                    let result = JSON.parse(record);
                    //console.log(result);
                    $("#searchResult").css('display', 'block');
                    $("#searchTitle").html('<h4>Search results</h4>');
                    let row = "";
                    result.forEach(function(index) {
                        let conv = Object.values(index);
                        console.log(conv);
                        row += "<tr>";
                        row += "<td style='text-transform:uppercase;'>" + conv[0] + "</td>";
                        row += "<td>" + conv[1] + "</td>";
                        row += "<td>" + conv[2] + "</td>";
                        row += "<td style='text-transform:uppercase;'>" + conv[3] + "</td>";
                        row += "<td>" + conv[4] + "</td>";
                        row += "<td>" + conv[5] + "</td>";
                        row += "<td>" + conv[6] + "</td>";
                        row += "<td>" + conv[7] + "</td>";
                        row += "<td>" + conv[8] + "</td>";
                        row += "<td>" + conv[9] + "</td>";
                        //let linkUrl = "payments/" + conv[10];
                        let rowlink = "<button class='btn btn-success' style='padding:7px;'>Paid</button></a>";
                        row += "<td>" + rowlink + "</td>";
                        row += "<td>" + conv[10] + "</td>";
                        row += "<td>" + conv[11] + "</td>";
                        row += "</tr>";
                    });
                    $("#resultTab").html(row);
                    //$("#resultList").text(conResult.length);
                } else{
                    $("#searchResult").css('display', 'block');
                    $("#searchTitle").html('<h4>No results found</h4>');
                    $("#resultTab").text("");
                }
                $("#loader").css('display', 'none');
            }
        });
    });
</script>
@endsection
