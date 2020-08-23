@extends('layouts.emails-temp')

@section('content')
<div>
    <div>You have succesfully registered with us as an Investor.</div>
    @isset($profile)
    <h3>Personal Details</h3>
    <table>
        <tbody>
            @foreach ($profile as $key => $item)
                <tr>
                    <td style="text-align:right;"><strong>{{ ucwords($key) }}:</strong></td>
                    <td style="text-align:left;">{{ $item }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endisset

    @isset($bank)
    <h3>Bank Details</h3>
    <table>
        <tbody>
            @foreach ($bank as $key => $item)
                <tr>
                    <td style="text-align:right;"><strong>{{ ucwords($key) }}:</strong></td>
                    <td style="text-align:left;">{{ $item }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endisset

    @isset($inv)
    <h3>Investment Details</h3>
    <table>
        <tbody>
            @foreach ($inv as $key => $item)
                <tr>
                    <td style="text-align:right;"><strong>{{ ucwords($key) }}:</strong></td>
                    <td style="text-align:left;">{{ $item }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @endisset
</div>
@endsection

