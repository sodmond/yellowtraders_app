<table class="table">
    <thead>
        <tr>
            <th>Log ID</th>
            <th>Trader ID</th>
            <th>Type</th>
            <th>Amount (&#8358;)</th>
            <th>Date</th>
            <th>Action</th>
            <th>Admin</th>
            <th>Last Updated</th>
        </tr>
    </thead>
    <tbody style="font-size:14px; font-weight:100;">
        @foreach($r_pay as $pay)
        <tr>
            <td>{{ $pay->investment_log_id }}</td>
            <td>{{ strtoupper($pay->trader_id) }}</td>
            <td>{{ $pay->investment_type }}</td>
            <td>{{ number_format($pay->amount) }}</td>
            <td>{{ $pay->created_at }}</td>
            <td>
                <a href="{{ url('/admin/payments/'.$pay->id) }}"><button class="btn btn-info" style="padding:7px;">View</button></a>
            </td>
            <td>{{ $pay->admin }}</td>
            <td>{{ $pay->updated_at }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<div id="mPag">{{ $r_pay->links() }} <span id="listloader"><img src="{{ asset('images/loader2.gif') }}" style="width:25px;">Loading...</span></div>
