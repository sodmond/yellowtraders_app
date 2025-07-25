@extends('layouts.dark-theme')

<title>List of Inactive Investors</title>

@section('page-header')
    <h3>List of Inactive Investors</h3>
@endsection

@section('content')
<style type="text/css">
    .page-item.active > .page-link{
        background: #E2A921;
    }
    .form-control{
        border-bottom: 2px solid #E2A921;
    }
</style>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md">&nbsp;</div>
                <div class="col-md">
                    <form class="navbar-form" method="POST" action="{{ url('/admin/search_trader') }}">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="input-group no-border">
                          <input type="text" name="search" class="form-control" required placeholder="Search for trader with trader id or email or phone or trader's name...">
                          <button type="submit" class="btn btn-default btn-round btn-just-icon">
                            <i class="material-icons">search</i>
                            <div class="ripple-container"></div>
                          </button>
                        </div>
                      </form>
                </div>
            </div>
            <div class="card">
                <div class="card-header card-header-primary" style="background:#E2A921;">
                    <div class="card-title" style="color:#000;"><h4>All Inactive Investors</h4></div>
                </div>

                <div class="card-body">
                    {{--<div>
                        <a href="{{ url('/admin/traders_export/1') }}"><button class="btn btn-secondary">Export List</button></a>
                    </div>--}}
                    @isset($_GET['msg'])
                        @if ($_GET['msg'] == "traderdelsuc")
                            <div class="alert alert-success">Trader has been deleted successfully</div>
                        @endif
                    @endisset
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="text-primary">
                                <tr>
                                    <th>Trader ID</th>
                                    <th>Full Name</th>
                                    <th>Email Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody style="font-size:15px; font-weight:100;">
                                @foreach ($tradersList as $trader)
                                <tr>
                                    <td>{{ strtoupper($trader->trader_id) }}</td>
                                    <td>{{ ucwords($trader->full_name) }}</td>
                                    <td>{{ strtolower($trader->email) }}</td>
                                    <td><a href="trader_profile/{{$trader->id}}" style="color:#E2A921;">View</a></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row justify-content-center">{{ $tradersList->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
