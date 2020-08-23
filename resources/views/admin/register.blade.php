@extends('layouts.dark-theme')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>My Profile</h4></div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md" style="text-align: center;">
                            @if (auth()->user()->role == 'superuser')
                                <a href="{{ url('/register') }}" target="_blank">
                                    <button class="btn btn-info">Register New Admin</button>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>All Registered Admin</h4></div>
                </div>

                <div class="card-body">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
