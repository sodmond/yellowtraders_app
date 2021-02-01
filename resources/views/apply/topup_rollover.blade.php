@extends('layouts.app')

<title>Topup / Rollover Application Form</title>
<link rel="stylesheet" type="text/css" href="{{ asset('css/custom.css') }}">

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-9">
            <div class="card" style="margin-bottom:20px;">
                <div class="card-header"><h3>Topup / Rollover Application Form</h3></div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="post" action="topup_rollover">
                        <div class="row">
                            <div class="col">
                                <p><strong>Note:</strong> This form is only for Rollover and Topup of your Investment.</p>
                            </div>
                        </div>
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
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="turo" value="checkRec">
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="inv_type"><strong>What do you want to do?</strong></label>
                                <select class="form-control" id="inv_type" name="inv_type" required>
                                    <option value="{{ old('inv_type') }}"> - - - {{ lcfirst(old('inv_type')) }} - - - </option>
                                    <option value="rollover">Rollover</option>
                                    <option value="topup">Topup</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="tidpne"><strong>Enter your Trader ID or Phone Number or Email</strong></label>
                                <input type="text" class="form-control" id="tidpne" name="tidpne" value="{{ old('tidpne') }}" required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md" style="text-align: center;">
                                <input type="submit" class="btn btn-warning" value="Continue">
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            @isset($trader_id)
            <?php
            $duration = DB::table('trader_types')->select('durations')->where('id', $trader_type)->get();
            $dur_arr = explode(",", $duration[0]->durations);
            ?>
            <div class="card" id="investor">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <p><img src="{{ asset('storage/'.$image)}}" class="img-thumbnail img-fluid"></p>
                            <p><strong>Trader ID:</strong> {{ strtoupper($trader_id) }}</p>
                        </div>
                        <div class="col-md-8">
                            <p><strong>Full Name:</strong> {{ ucwords($full_name) }}</p>
                            <p><strong>Marital Status:</strong> {{ ucwords($marital_status) }}</p>
                            <p><strong>Gender:</strong> {{ ucwords($gender) }}</p>
                            <p><strong>Address:</strong> {{ $address }}</p>
                            <p><strong>Phone:</strong> 0{{ $phone }}</p>
                            <p><strong>Other Phone:</strong> {{ $other_phone }}</p>
                            <p><strong>Date of Birth:</strong> {{ $dob }}</p>
                            <p><strong>Country:</strong> {{ ucwords($nationality) }}</p>
                            <p><strong>State:</strong> {{ ucwords($state) }}</p>
                            <p><strong>City:</strong> {{ ucwords($lga) }}</p>
                            <p><strong>Email:</strong> {{ $email }}</p>
                            <p><strong>Referral:</strong> {{ $referral }}</p>
                        </div>
                    </div>

                    <hr>

                    <form method="post" action="topup_rollover">
                        <div class="row">
                            <div class="col">
                                <h4>{{ ucfirst($inv_type) }} Form</h4>
                                <p>Fill in the form below to {{ $inv_type }} your investment:</p>
                                @if ($inv_type == "rollover")
                                    <p><strong>Note:</strong> If you're adding more funds to your previous capital, input the new total amount in the field.</p>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="turo" value="{{ $inv_type }}">
                        <input type="hidden" name="trader_id" value="{{ $trader_id }}">
                        <input type="hidden" name="inv_id" value="{{ $inv_id }}">
                        <input type="hidden" name="trader_type" value="{{ $trader_type }}">

                        <div class="form-group row">
                            <div class="col-md">
                                <label class="required" for="amount"><strong>Amount:</strong></label>
                                <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount') }}" onblur="" required>
                            </div>
                            <div class="col-md">
                                <label class="required" for="amount_in_words"><strong>Amount in Words:</strong></label>
                                <input type="text" class="form-control" id="amount_words" name="amount_words" value="{{ old('amount_words') }}" required readonly>
                            </div>
                        </div>
                        <div class="form-group row">
                            @if ($inv_type == "rollover")
                            <div class="col-md">
                                <label class="required" for="duration"><strong>Duration:</strong></label>
                                <select class="form-control" id="duration" name="duration" required>
                                    <option value="{{ old('duration') }}"> - - - {{ old('duration') }} - - - </option>
                                    @foreach ($dur_arr as $item)
                                        <option value="{{ $item }}">{{ $item }} Months</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            <div class="col-md">
                                <label for="purpose"><strong>Purpose:</strong></label>
                                <input type="text" class="form-control" id="purpose" name="purpose" value="{{ old('purpose') }}">
                            </div>
                        </div>
                        <div id="loader" style="text-align:center;"><img src="{{ asset('images/loader.gif') }}" width="25"> Loading Monthly % and ROI...</div>
                        <div class="form-group row">
                            <div class="col-md">
                                <label for="month_pcent"><strong>Monthly %:</strong></label>
                                <div class="input-group mb-3">
                                    <input type="number" class="form-control" id="month_pcent" readonly>
                                    <div class="input-group-append">
                                        <span class="input-group-text">% &nbsp;&nbsp;&nbsp;</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md">
                                <label for="month_roi"><strong>Monthly ROI:</strong></label>
                                <input type="number" class="form-control" id="month_roi" readonly>
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
            @endisset
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script src="{{ asset('js/custom.js') }}"></script>
@endsection
