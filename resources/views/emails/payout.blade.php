@extends('layouts.emails-temp')

@section('content')
<div style="margin: 50px 10px; text-align:justify;">
    <p>Hello,</p>
    <p>Your monthly ROI have just been paid to the bank account which you provided for investment.</p>
    <p>The amount of <strong>#{{ $monthly_roi }}</strong> has been paid as your monthly ROI
        for your <strong>#{{ $amount }}</strong> investment.</p>
    <p>We hope you enjoy your investment with us. Please contact us if you have any payment issue.</p>
    <p>Regards,<br>Yellow Traders</p>
</div>
<?php
#print_r(get_loaded_extensions());
#echo phpinfo();
?>
@endsection
