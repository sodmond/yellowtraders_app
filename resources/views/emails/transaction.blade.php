@extends('layouts.emails-temp')

@section('content')
<div>
    <h3>Transaction Details</h3>
    <p>Your @isset($inv_type) {{ $inv_type }} @endisset investment has been accepted. Please pay <strong>#{{ $amount }}</strong> to our bank account and use the details below to submit your proof of payment.</p>
    <table>
        <tbody>
            <tr>
                <td style="text-align:right;"><strong>Trader ID: </strong></td>
                <td style="text-align:left;">{{ $trader_id }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Transaction ID:</strong></td>
                <td style="text-align:left;">{{ $trans_id }}</td>
            </tr>
        </tbody>
    </table>
    <p>Go to the <a href="https://app.yellowtraders.org/apply/payment">Payment Page</a> to upload proof of payment</p>
</div>
<?php
#print_r(get_loaded_extensions());
#echo phpinfo();
?>
@endsection
