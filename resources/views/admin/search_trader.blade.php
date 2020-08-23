@extends('layouts.dark-theme')

<title>Search Page</title>

@section('page-header')
    <h3>Search Page</h3>
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
                          <input type="text" name="search" class="form-control" required placeholder="Search for trader with trader id or email or phone...">
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
                    <div class="card-title" style="color:#000;"><h4>Search results</h4></div>
                </div>

                <div class="card-body">

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
                            @isset($tradersList)
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
                            @endisset
                        </table>

                        @if(!isset($tradersList[0]->trader_id))
                        <p class="text-warning" style="text-align:center; font-style:italic;">No record found</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
