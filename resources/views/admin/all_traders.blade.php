@extends('layouts.dark-theme')

<title>All Traders</title>

@section('page-header')
    <h3>All Traders</h3>
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

    </div>

    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card">
                <div class="card-header card-header-primary" style="background:#E2A921;">
                    <div class="card-title row" style="color:#000;">
                        <div class="col-md-4" style="font-size:78px; color:#eee;"><i class="fa fa-user"></i></div>
                        <div class="col-md-8" style="font-size:22px; text-align:left; line-height:100px; padding:10px;">
                            <a href="{{ url('/admin/yellow_traders') }}">Yellow Traders</a>
                        </div>
                    </div>
                </div>

                <div class="card-body"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-header-primary" style="background:#E2A921;">
                    <div class="card-title row" style="color:#000;">
                        <div class="col-md-4" style="font-size:78px; color:#eee;"><i class="fa fa-child"></i></div>
                        <div class="col-md-8" style="font-size:22px; text-align:left; line-height:100px; padding:10px;">
                            <a href="{{ url('/admin/junior_traders') }}">Junior Traders</a>
                        </div>
                    </div>
                </div>

                <div class="card-body"></div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card">
                <div class="card-header card-header-primary" style="background:#E2A921;">
                    <div class="card-title row" style="color:#000;">
                        <div class="col-md-4" style="font-size:78px; color:#eee;"><i class="fa fa-building-o"></i></div>
                        <div class="col-md-8" style="font-size:22px; text-align:left; line-height:100px; padding:10px;">
                            <a href="{{ url('/admin/corporate_traders') }}">Corporate Traders</a>
                        </div>
                    </div>
                </div>

                <div class="card-body"></div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-4">

            <div class="card">
                <div class="card-header card-header-primary" style="background:#E2A921;">
                    <div class="card-title row" style="color:#000;">
                        <div class="col-md-4" style="font-size:78px; color:#eee;"><i class="fa fa-search"></i></div>
                        <div class="col-md-8" style="font-size:22px; text-align:left; line-height:100px; padding:10px;">
                            <a href="{{ url('/admin/search_trader') }}">Search Traders</a>
                        </div>
                    </div>
                </div>

                <div class="card-body"></div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header card-header-primary" style="background:#E2A921;">
                    <div class="card-title row" style="color:#000;">
                        <div class="col-md-4" style="font-size:78px; color:#eee;"><i class="fa fa-user-times"></i></div>
                        <div class="col-md-8" style="font-size:22px; text-align:left; line-height:100px; padding:10px;">
                            <a href="{{ url('/admin/inactive_investors') }}">Inactive Investors</a>
                        </div>
                    </div>
                </div>

                <div class="card-body"></div>
            </div>
        </div>

        <div class="col-md-4">

            <div class="card">
                <div class="card-header card-header-primary" style="background:#E2A921;">
                    <div class="card-title row" style="color:#000;">
                        <div class="col-md-4" style="font-size:78px; color:#eee;"><i class="fa fa-archive"></i></div>
                        <div class="col-md-8" style="font-size:22px; text-align:left; line-height:100px; padding:10px;">
                            <a href="{{ url('/admin/archived_investors') }}">Archived Investors</a>
                        </div>
                    </div>
                </div>

                <div class="card-body"></div>
            </div>
        </div>
    </div>
</div>
@endsection
