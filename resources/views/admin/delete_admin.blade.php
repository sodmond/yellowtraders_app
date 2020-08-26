@extends('layouts.dark-theme')

<title>Registered Admins</title>

@section('page-header')
    <h3>Registered Admins</h3>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>My Profile</h4></div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md" style="text-align:center;">
                            <h5>You are about to delete this admin, Do you want to proceed?</h5>
                            <div class="table-responsive">
                                <table class="table" style="font-size: 14px;">
                                    <tr>
                                        <td><strong>Username</strong></td>
                                        <td>{{ $admin->username }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Full Name</strong></td>
                                        <td>{{ $admin->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email Address</strong></td>
                                        <td>{{ $admin->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Role</strong></td>
                                        <td>{{ $admin->role }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-md">
                                    <a href="{{ url('/admin/register') }}">
                                        <button class="btn btn-warning">Cancel</button>
                                    </a>
                                </div>
                                <div class="col-md">
                                    <form action="{{ url('/admin/delete_admin') }}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="id" value="{{ $admin->id }}">
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
