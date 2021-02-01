@extends('layouts.emails-temp')

@section('content')
<div style="margin: 50px 10px; text-align:justify;">
    <p>Hello,</p>
    <p>You have requested for the refund of your capital and this will take a minimum of 14 days
        to be processed and refunded into your account, upon approval of complete documentation
        and successful verification of your application.
    </p>
    <p>See your details below:</p>
    <table>
        <tbody>
            <tr>
                <td style="text-align:right;"><strong>Trader ID: </strong></td>
                <td style="text-align:left;">{{ $trader_id }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Full Name: </strong></td>
                <td style="text-align:left;">{{ $fullname }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Amount: </strong></td>
                <td style="text-align:left;">{{ $amount }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Amount in Words: </strong></td>
                <td style="text-align:left;">{{ $amount_words }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Bank Name: </strong></td>
                <td style="text-align:left;">{{ $bankname }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Account Number: </strong></td>
                <td style="text-align:left;">{{ $acctnum }}</td>
            </tr>
        </tbody>
    </table>
    <p>Note that your capital will be refunded to the same bank account you have been receiving your ROI.</p>
    <p>Regards,<br>Yellow Traders</p>
</div>
@endsection
