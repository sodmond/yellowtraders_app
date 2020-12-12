@extends('layouts.emails-temp')

@section('content')
<div style="margin: 50px 10px;">
    <p>Hello,</p>
    <p>Your {{ $inv_type }} investment of the amount of
    @if ($inv_type == "topup")
        {{ ($newInv["amount"] - $investment['amount']) }}
    @else
        {{ $investment['amount'] }}
    @endif
    has been confirmed. See details below:</p>
    <table>
        <tbody>
            <tr>
                <td style="text-align:right;"><strong>Trader ID: </strong></td>
                <td style="text-align:left;">{{ $investment["trader_id"] }}</td>
            </tr>
            @if ($inv_type == "topup")
            <tr>
                <td style="text-align:right;"><strong>Total Invested Amount:</strong></td>
                <td style="text-align:left;">{{ $newInv["amount"] }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Monthly %:</strong></td>
                <td style="text-align:left;">{{ $newInv["monthly_pcent"] }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Monthly ROI:</strong></td>
                <td style="text-align:left;">{{ $newInv["monthly_roi"] }}</td>
            </tr>
            @else
            <tr>
                <td style="text-align:right;"><strong>Invested Amount:</strong></td>
                <td style="text-align:left;">{{ $investment["amount"] }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Monthly %:</strong></td>
                <td style="text-align:left;">{{ $investment["monthly_pcent"] }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>Monthly ROI:</strong></td>
                <td style="text-align:left;">{{ $investment["monthly_roi"] }}</td>
            </tr>
            @endif
            <tr>
                <td style="text-align:right;"><strong>Start Date:</strong></td>
                <td style="text-align:left;">{{ $investment["start_date"] }}</td>
            </tr>
            <tr>
                <td style="text-align:right;"><strong>End Date:</strong></td>
                <td style="text-align:left;">{{ $investment["end_date"] }}</td>
            </tr>
        </tbody>
    </table>
    <div style="text-align: justify;">
        <p>Than you for investing with us.</p>
        <p>Best regards, <br>Yellow Traders.</p>
    </div>
</div>
<?php
#print_r(get_loaded_extensions());
#echo phpinfo();
?>
@endsection
