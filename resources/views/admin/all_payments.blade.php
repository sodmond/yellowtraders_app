@extends('layouts.dark-theme')

<title>All Received Payments</title>

@section('page-header')
    <h3>All Received Payments</h3>
@endsection

@section('content')
<style type="text/css">
    .btn{
        padding: 10px;
    }
</style>
<div class="container">
    <div class="row">
        <div class="col-md">&nbsp;</div>
        <div class="col-md">
            <form class="navbar-form" id="searchPaymentForm">
                <input type="hidden" name="_token" id="searchToken" value="{{ csrf_token() }}">
                <div class="input-group no-border">
                  <input type="text" name="searchPayValue" class="form-control" required placeholder="Search for payment with trader id only...">
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
                                    <th>Log ID</th>
                                    <th>Trader ID</th>
                                    <th>Type</th>
                                    <th>Amount</th>
                                    <th>Date</th>
                                    <th>Action</th>
                                    <th>Admin</th>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>List of All Received Payments</h4></div>
                </div>

                <div class="card-body">
                    <div>
                        <a href="{{ url('/admin/payments') }}"><button class="btn">Unconfirmed Payments</button></a>
                    </div>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Log ID</th>
                                    <th>Trader ID</th>
                                    <th>Type</th>
                                    <th>Amount (&#8358;)</th>
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
                                    <td>{{ number_format($pay->amount) }}</td>
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
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
    $("#searchResult").css('display', 'none');
    $('#searchPaymentForm').submit(function(event){
        event.preventDefault();
        $("#loader").css('display', 'block');
        search = $("input[name=searchPayValue]").val();
        token = $("#searchToken").val();

        $.ajax({
            url: "searchpayments",
            type: "POST",
            data: {_token:token, searchPayValue:search},
            dataType: 'JSON',
            success: function(data){
                if ($.isEmptyObject(data.error) && data.r_pay != ""){
                    //alert("success");
                    let record = JSON.stringify(data.r_pay);
                    let result = JSON.parse(record);
                    //console.log(result);
                    $("#searchResult").css('display', 'block');
                    $("#searchTitle").html('<h4>Search results for trader <strong>"' + search + '"</strong></h4>');
                    let row = "";
                    result.forEach(function(index) {
                        let conv = Object.values(index);
                        console.log(conv);
                        row += "<tr>";
                        row += "<td>" + conv[2] + "</td>";
                        row += "<td style='text-transform:uppercase;'>" + conv[5] + "</td>";
                        row += "<td>" + conv[3] + "</td>";
                        row += "<td>" + conv[4] + "</td>";
                        row += "<td>" + conv[1] + "</td>";
                        let linkUrl = "payments/" + conv[0];
                        let rowlink = "<a href='"+linkUrl+"'><button class='btn btn-info' style='padding:7px;'>View</button></a>";
                        row += "<td>" + rowlink + "</td>";
                        row += "<td>" + conv[6] + "</td>";
                        row += "</tr>";
                    });
                    $("#resultTab").html(row);
                    //$("#resultList").text(conResult.length);
                } else{
                    $("#searchResult").css('display', 'block');
                    $("#searchTitle").html('<h4>No results for trader <strong>"' + search + '"</strong></h4>');
                    $("#resultTab").text("");
                }
                $("#loader").css('display', 'none');
            }
        });
    });
</script>
@endsection
