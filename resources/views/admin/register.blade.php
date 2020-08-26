@extends('layouts.dark-theme')

<title>Registered Admins</title>

@section('page-header')
    <h3>Registered Admins</h3>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>My Profile</h4></div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md" style="text-align:center;">
                            <div class="table-responsive">
                                <table class="table" style="font-size: 14px;">
                                    <tr>
                                        <td><strong>Username</strong></td>
                                        <td>{{ auth()->user()->username }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Full Name</strong></td>
                                        <td>{{ auth()->user()->name }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Email</strong></td>
                                        <td>{{ auth()->user()->email }}</td>
                                    </tr>
                                    <tr>
                                        <td><strong>Role</strong></td>
                                        <td>{{ auth()->user()->role }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if (auth()->user()->role == 'superuser')
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>All Registered Admin</h4></div>
                </div>

                <div class="card-body">
                    <div class="text-warning"><strong>Note:</strong> You can't delete an admin with a superuser role and any other deleted admin cannot be recovered</div>
                    @isset($suc_msg)
                        <div class="alert alert-success">{{ $suc_msg }}</div>
                    @endisset
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Username</th>
                                    <th>Full Name</th>
                                    <th>Email Address</th>
                                    <th>Role</th>
                                    <th>Created Date</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;">
                                @foreach($users as $user)
                                <tr>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->role }}</td>
                                    <td>{{ $user->created_at }}</td>
                                    <td>
                                        <a href="{{ url('/admin/delete_admin/'.$user->id) }}">
                                            <button class="btn btn-danger" style="padding:7px;">Delete</button>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div style="text-align: center;">
                        <a href="{{ url('/register') }}" target="_blank">
                            <button class="btn btn-info">Register New Admin</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
