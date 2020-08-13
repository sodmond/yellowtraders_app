@extends('layouts.app')

<title>Junior Traders Application Form</title>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

@section('content')
<?php
$duration = DB::table('trader_types')->select('durations')->where('id', 2)->get();
$dur_arr = explode(",", $duration[0]->durations);
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header"><h3>Junior Traders Application Form</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="junior_traders" enctype="multipart/form-data">
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
                                <p><strong>Note:</strong> This form is only for Children between age group 0-17years.</p>
                                <h4>Personal Information</h4>
                                <p>Fill in your peronal details below just as it appears in your valid documents like Birth Certificate, National ID, International Passport etc.</p>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="fname"><strong>Full Name:</strong></label>
                                <input type="text" class="form-control" id="fname" name="fname" placeholder="Firstname Lastname Othername" value="{{ old('fname') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="email"><strong>Email Address:</strong></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="somone@example.com" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="phone"><strong>Phone:</strong></label>
                                <input type="tel" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required"><strong>Gender:</strong></label>
                                <div class="form-row">
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="male" name="gender" value="male" required>
                                        <label class="custom-control-label" for="male">Male</label>
                                    </div>
                                    <span>&nbsp;&nbsp;&nbsp;&nbsp;</span>
                                    <div class="custom-control custom-radio">
                                        <input type="radio" class="custom-control-input" id="female" name="gender" value="female" required>
                                        <label class="custom-control-label" for="female">Female</label>
                                    </div>
                                    <input type="hidden" name="marital" value="single">
                                </div>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="address"><strong>Residential Address:</strong></label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="dob"><strong>Date of Birth:</strong></label>
                                <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob') }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="country"><strong>Nationality:</strong></label>
                                <input type="text" class="form-control" id="country" name="country" value="{{ old('country') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="state"><strong>State of Origin:</strong></label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ old('state') }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="lga"><strong>LGA:</strong></label>
                                <input type="text" class="form-control" id="lga" name="lga" value="{{ old('lga') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="p_name"><strong>Parent's Full Name:</strong></label>
                                <input type="text" class="form-control" id="p_name" name="p_name" value="{{ old('p_name') }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="p_phone"><strong>Parent's Phone:</strong></label>
                                <input type="tel" class="form-control" id="p_phone" name="p_phone" value="{{ old('p_phone') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="image"><strong>Passport:</strong></label>
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
