@extends('layouts.emails-temp')

@section('content')
<div style="margin: 50px 10px; text-align:justify;">
    <p>Dear Investor,</p>

    <p>Kindly be informed that your designated bank account has been credited with your monthly ROI.</p>

    <p>The amount of <strong>#{{ $monthly_roi }}</strong> has been paid as your monthly ROI on your investment of <strong>#{{ $amount }}</strong></p>

    <p>Following this payment, you have 10 days to fill a Top Up Form. (Only if your investment’s expiry date is not due)</p>

    <p>If you have any question or inquiries, please contact us and we will be happy to assist you.</p>

    <p>Thank you for investing with us.</p>

    <p>Best Regards,<br>Yellow Traders</p>
</div>
<?php
#print_r(get_loaded_extensions());
#echo phpinfo();
?>
@endsection
