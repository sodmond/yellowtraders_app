@extends('layouts.emails-temp')

@section('content')
<div style="margin: 50px 10px; text-align:justify; padding:30px;">
    <p>Hello,</p>
    <p>You have just requested for a password reset on your account. Use the OTP below to continue the process of
        resetting your password. The code expires in 6 hours.</p>
    <div style="background-color:#20293F; margin:30px; padding:20px 0px 20px 0px; font-weight:bold; font-size:18px; text-align:center;">
        <span style="color:#E2A921;">{{$code}}</span>
    </div>
    <p>You should not take any action if you did not initiate any request to reset your password.</p>
    <p>Regards,<br>Yellow Traders</p>
</div>
@endsection
