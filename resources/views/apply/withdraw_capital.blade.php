@extends('layouts.app')

<title>Capital Withdrawal Form</title>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header"><h3>Capital Withdrawal Form</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="withdraw_capital" enctype="multipart/form-data">
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
                        @isset($suc_msg) <div class="alert alert-success"><strong>Success!</strong> {{ $suc_msg }}</div> @endisset
                        @isset ($_GET['msg']) <div class="alert alert-success"><strong>Success!</strong> You have just {{ $_GET['msg'] }} your investment</div> @endisset
                        @isset($err_msg) <div class="alert alert-danger"><strong>No records found!</strong> {{ $err_msg }}</div> @endisset
                        @isset ($pend_msg) <div class="alert alert-warning"><strong>Oops!</strong> {{ $pend_msg }}</div> @endisset
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" id="token">
                        <input type="hidden" name="capitalAuth" value="capitalAuth">
                        <div id="getCapitalDiv">
                            <div class="form-group row">
                                <div class="col-md">
                                    <label class="required" for="tidpne"><strong>Enter your Trader ID or Phone Number or Email</strong></label>
                                    <input type="text" class="form-control" id="tidpne" name="tidpne" value="{{ old('tidpne') }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md" style="text-align: center;">
                                    <input type="button" class="btn btn-warning" value="Continue" id="getCapital">
                                    <span id="loader"><img src="{{ asset('images/loader.gif') }}" width="25"> Loading...</span>
                                </div>
                            </div>
                        </div>
                        <div id="getCapitalForm">
                            <input type="hidden" name="cwForm" value="cwForm">
                            <div class="row">
                                <div class="col">
                                    <p><strong>Note:</strong> Check if your information is correct. If yes, attach your bank statement (in PDF format) for the contract period of your investment and click on the submit button below the form.</p>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md">
                                    <label class="required" for="trader_id"><strong>Your Trader ID:</strong></label>
                                    <input type="text" class="form-control" name="trader_id" id="trader_id" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md">
                                    <label class="required" for="full_name"><strong>Full Name:</strong></label>
                                    <input type="text" class="form-control" name="full_name" id="full_name" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md">
                                    <label class="required" for="email"><strong>Email:</strong></label>
                                    <input type="email" class="form-control" name="email" id="email" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md">
                                    <label class="required" for="amount"><strong>Amount:</strong></label>
                                    <input type="text" class="form-control" name="amount" id="amount" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md">
                                    <label class="required" for="amount_words"><strong>Amount in Words:</strong></label>
                                    <input type="text" class="form-control" name="amount_words" id="amount_words" required readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md">
                                    <label class="required" for="bank_stmt"><strong>Attach Bank Statement:</strong></label>
                                    <input type="file" class="form-control form-control-file border" id="bank_stmt" name="bank_stmt" required>
                                    <span style="font:italic 13px calibri; color:blueviolet;">Only PDF file is allowed and must not be above 5MB</span>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md" style="text-align: center;">
                                    <button type="submit" class="btn btn-warning">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script>
    $(document).ready(function(){
        $('#loader').css('display', 'none');
        $('#getCapitalForm').css('display', 'none');
        $('#getCapital').click(function(event){
            event.preventDefault();
            $('#loader').css('display', 'block');
            let token = $('#token').val();
            let tidpne = $('#tidpne').val();
            $.ajax({
                type: 'POST',
                url: 'withdraw_capital',
                data:{_token:token, tidpne:tidpne},
                dataType: 'JSON',
                success:function(data) {
                    let traderInfo = data.trader;
                    if ($.isEmptyObject(data.error) && data.trader != ""){
                        if (traderInfo.status == 0 && traderInfo.capital == 1){
                            $('#getCapitalForm').css('display', 'block');
                            $('#getCapitalDiv').css('display', 'none');
                            $('#trader_id').val(traderInfo.trader_id);
                            $('#full_name').val(traderInfo.full_name);
                            $('#email').val(traderInfo.email);
                            $('#amount').val(traderInfo.amount);
                            $('#amount_words').val(traderInfo.amount_in_words);
                        } else if (traderInfo.status == 0 && traderInfo.capital == 0){
                            alert(traderInfo.trader_id);
                            alert("Oops!! You have withdrawn your capital already");
                            $('#loader').css('display', 'none');
                        } else{
                            alert(traderInfo.status);
                            alert("Oops!! You either have a pending or active investment at the moment");
                            $('#loader').css('display', 'none');
                        }
                    } else{
                        alert("Hello");
                        $('#loader').css('display', 'none');
                    }
                    //console.log(traderInfo);
                }
            });
        });
    });
</script>
@endsection
