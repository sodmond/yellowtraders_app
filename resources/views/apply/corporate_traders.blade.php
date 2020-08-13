@extends('layouts.app')

<title>Corporate Traders Application Form</title>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

@section('content')
<?php
$duration = DB::table('trader_types')->select('durations')->where('id', 3)->get();
$dur_arr = explode(",", $duration[0]->durations);
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header"><h3>Corporate Traders Application Form</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="corporate_traders" enctype="multipart/form-data">
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
                        @if (isset($suc_msg))
                            <div class="alert alert-success"><strong>Success!</strong> {{ $suc_msg }}</div>
                        @endif
                        @if (isset($err_msg))
                            <div class="alert alert-warning"><strong>Error!</strong> {{ $err_msg }}</div>
                        @endif
                        <div class="row">
                            <div class="col">
                                <p><strong>Note:</strong> This form is only for registered companies/business.</p>
                                <h4>Company Information</h4>
                                <p>Fill in your company details below just as it appears in your valid business certificate.</p>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="cname"><strong>Company Name:</strong></label>
                                <input type="text" class="form-control" id="cname" name="cname" value="{{ old('cname') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="address"><strong>Office Address:</strong></label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="email"><strong>Email Address:</strong></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="phone"><strong>Phone:</strong></label>
                                <input type="number" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                            </div>
                            <div class="col">
                                <label for="alt_phone"><strong>Other Phone:</strong></label>
                                <input type="number" class="form-control" id="alt_phone" name="alt_phone" value="{{ old('alt_phone') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="dob"><strong>Date of Incorporation:</strong></label>
                                <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="country"><strong>Country:</strong></label>
                                <input type="text" class="form-control" id="country" name="country" value="{{ old('country') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="state"><strong>State:</strong></label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="city"><strong>City:</strong></label>
                                <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="rep_name"><strong>Representative [Full Name]:</strong></label>
                                <input type="text" class="form-control" id="rep_name" name="rep_name" value="{{ old('rep_name') }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="rep_phone"><strong>Representative [Phone]:</strong></label>
                                <input type="number" class="form-control" id="rep_phone" name="rep_phone" value="{{ old('rep_phone') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required"><strong>Business Certificate Image:</strong></label>
                                <input type="file" class="form-control form-control-file border" id="image" name="image" value="{{ old('image') }}" required>
                            </div>
                            <div class="col-md">
                                <label for="ref"><strong>Referral: (leave blank if none)</strong></label>
                                <input type="text" class="form-control" id="ref" name="ref" value="{{ old('ref') }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <hr>
                                <h4>Bank Account Details</h4>
                                <p>Fill in your bank details below. The account name must match the name on your profile</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="bank_name"><strong>Bank Name:</strong></label>
                                <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ old('bank_name') }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="account_number"><strong>Account Number:</strong></label>
                                <input type="number" class="form-control" id="account_number" name="account_number" value="{{ old('account_number') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="account_name"><strong>Account Name:</strong></label>
                                <input type="text" class="form-control" id="acct_name" name="account_name" value="{{ old('account_name') }}" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <hr>
                                <h4>Investment Details</h4>
                                <p>Select your investment plan below:</p>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="amount"><strong>Amount to be Invested:</strong></label>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="amount_words"><strong>Amount in Words:</strong></label>
                                <input type="text" class="form-control" id="amount_words" name="amount_words" value="{{ old('amount_words') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="duration"><strong>Duration:</strong></label>
                                <select class="form-control" id="duration" name="duration" value="{{ old('duration') }}" required>
                                    <option value="">- - - - - - - - - - - -</option>
                                    @foreach ($dur_arr as $item)
                                        <option value="{{ $item }}">{{ $item }} Months</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md">
                                <label for="purpose"><strong>Purpose:</strong></label>
                                <input type="text" class="form-control" id="purpose" name="purpose" value="{{ old('purpose') }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="month_pcent"><strong>Monthly %:</strong></label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" id="month_pcent" name="month_pcent" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">% &nbsp;&nbsp;&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <label for="month_roi"><strong>Monthly ROI:</strong></label>
                                <input type="number" class="form-control" id="month_roi" name="month_roi" readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col" style="text-align:center;">
                                <input type="submit" class="btn btn-warning" value="Submit">
                            </div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>
@endsection
