@extends('layouts.dark-theme')

<title>Delete Trader</title>

@section('page-header')
    <h3>Registered Admins</h3>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>Delete Trader</h4></div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md" style="text-align:center;">
                            <h5>You are about to delete this trader, Do you want to proceed?</h5>
                            <div style="text-align:center; margin-bottom:10px;">
                                <img class="img" src="{{ asset('storage/'.$trader->image)}}" style="width:80%">
                            </div>
                            <div class="table-responsive">
                                <table class="table" style="font-size: 14px;">
                                    <tr>
                                        <td><strong>Trader ID</strong></td>
                                        <td>{{ strtoupper($trader->trader_id) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Full Name</strong></td>
                                        <td>{{ ucwords($trader->full_name) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email</strong></td>
                                        <td>{{ strtolower($trader->email) }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Phone</strong></td>
                                        <td>0{{ $trader->phone }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <a href="{{ url('/admin/trader_profile/'.$trader->id) }}">
                                        <button class="btn btn-danger">Cancel</button>
                                    </a>
                                </div>
                                <div class="col-md">
                                    <form action="{{ url('/admin/delete_trader') }}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id" value="{{ $trader->id }}">
                                        <input type="hidden" name="trader_id" value="{{ $trader->trader_id }}">
                                        <button type="submit" class="btn btn-success">Proceed</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
