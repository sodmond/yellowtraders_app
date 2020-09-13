@extends('layouts.emails-temp')

@section('content')
<div>
    <h3>Transaction Details</h3>
    <p>Your @isset($inv_type) {{ $inv_type }} @endisset investment has been accepted. Please pay <strong>#{{ $amount }}</strong> to our bank account and use the details below to submit your proof of payment.</p>
    <table>
        <tbody>
            <tr>
                <td style="text-align:right; width:50%;"><strong>Trader ID: </strong></td>
                <td style="text-align:left;">{{ $trader_id }}</td>
            </tr>
            <tr>
                <td style="text-align:right; width:50%;"><strong>Transaction ID:</strong></td>
                <td style="text-align:left;">{{ $trans_id }}</td>
            </tr>
        </tbody>
    </table>
    <p>Go to the <a href="https://app.yellowtraders.org/apply/payment">Payment Page</a> to upload proof of payment. See our designated bank account details you are to make payment to below:</p>
    <table style="border-top: 1px solid #999;">
        <tbody style="font-size:14px;">
            <tr>
                <td style="text-align:right; width:50%;"><strong>Bank Name: </strong></td>
                <td style="text-align:left;">Guaranty Trust Bank</td>
            </tr>
            <tr>
                <td style="text-align:right; width:50%;"><strong>Account Name:</strong></td>
                <td style="text-align:left;">YellowPoint Media Enterprises</td>
            </tr>
            <tr>
                <td style="text-align:right; width:50%;"><strong>Account Number:</strong></td>
                <td style="text-align:left;">0018519250</td>
            </tr>
        </tbody>
    </table>
</div>
<?php
#print_r(get_loaded_extensions());
#echo phpinfo();
?>
@endsection
