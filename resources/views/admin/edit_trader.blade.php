@extends('layouts.app')

<title>Edit Trader's Information</title>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

@section('content')
<?php
?>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card">
                <div class="card-header"><h3>Edit Trader's Information</h3></div>

                <div class="card-body">
                    @if (session('traderUpdateSuc'))
                        <div class="alert alert-success" role="alert">
                            {{ session('traderUpdateSuc') }}
                        </div>
                    @endif
                    <form method="post" action="{{ url('/admin/edit_trader') }}" enctype="multipart/form-data">
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
                                <h4>Personal Information</h4>
                                <p>Fill in your personal details below just as it appears in your Bank Account details.</p>
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="trader_id" value="{{ $trader->trader_id }}">
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="fname" class="required"><strong>Full Name:</strong></label>
                                <input type="text" class="form-control" id="fname" name="fname" value="{{ $trader->full_name }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="email" class="required"><strong>Email Address:</strong></label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ $trader->email }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="gender" class="required"><strong>Gender:</strong></label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="{{ $trader->gender }}"> - - - {{ $trader->gender }} - - - </option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="not applicable">Not Applicable</option>
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="required" for="marital"><strong>Marital Status:</strong></label>
                                <select class="form-control" id="marital" name="marital">
                                    <option value="{{ $trader->marital_status }}"> - - - {{ $trader->marital_status }} - - - </option>
                                    <option value="single">Single</option>
                                    <option value="married">Married</option>
                                    <option value="divorced">Divorced</option>
                                    <option value="widow">Widow / Widower</option>
                                    <option value="not applicable">Not Applicable</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="address" class="required"><strong>Residential Address:</strong></label>
                                <input type="text" class="form-control" id="address" name="address" value="{{ $trader->address }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="phone" class="required"><strong>Phone:</strong></label>
                                <input type="number" class="form-control" id="phone" name="phone" value="{{ $trader->phone }}" required>
                            </div>
                            <div class="col">
                                <label for="alt_phone"><strong>Other Phone:</strong></label>
                                <input type="number" class="form-control" id="phone" name="alt_phone" value="{{ $trader->other_phone }}">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="dob" class="required"><strong>Date of Birth:</strong></label>
                                <input type="date" class="form-control" id="dob" name="dob" value="{{ $trader->dob }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="country"><strong>Nationality:</strong></label>
                                <select class="form-control" id="country" name="country">
                                    <option value="{{ $trader->nationality }}"> - - - {{ $trader->nationality }} - - - </option>
                                    <?php echo file_get_contents(asset('country_list_dropdown.txt')); ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="state"><strong>State of Origin:</strong></label>
                                <input type="text" class="form-control" id="state" name="state" value="{{ $trader->state }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="lga"><strong>LGA:</strong></label>
                                <input type="text" class="form-control" id="lga" name="lga" value="{{ $trader->lga }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="nok_name"><strong>NOK / Parent / Rep [Full Name]:</strong></label>
                                <input type="text" class="form-control" id="nok_name" name="nok_name" value="{{ $trader->contact_name }}" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="nok_phone"><strong>NOK / Parent / Rep [Phone]:</strong></label>
                                <input type="number" class="form-control" id="nok_phone" name="nok_phone" value="{{ $trader->contact_phone }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="image"><strong>Passport:</strong> (Leave empty if you are not changing the image)</label>
                                <input type="file" class="form-control form-control-file border" id="image" name="image" value="{{ old('image') }}">
                            </div>
                            <div class="col-md">
                                <label for="ref"><strong>Referral:</strong> (leave blank if none)</label>
                                <input type="text" class="form-control" id="ref" name="ref" value="{{ $trader->referral }}">
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
                                <select class="form-control" id="bank_name" name="bank_name">
                                    <option value="{{ $bank->bank_name }}"> - - - {{ $bank->bank_name }} - - - </option>
                                    <?php echo file_get_contents(asset('bank_list_dropdown.txt')); ?>
                                </select>
                            </div>
                            <div class="col-md">
                                <label class="required" for="account_number"><strong>Account Number:</strong></label>
                                <input type="number" class="form-control" id="account_number" name="account_number" value="{{ $bank->account_number }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="account_name"><strong>Account Name:</strong></label>
                                <input type="text" class="form-control" id="acct_name" name="account_name" value="{{ $bank->holder_name }}" required readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col" style="text-align:center;">
                                <input type="submit" class="btn btn-warning" value="Update">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@endsection
