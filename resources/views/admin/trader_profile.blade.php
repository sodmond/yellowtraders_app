@extends('layouts.dark-theme')

<title>Trader Profile</title>

@section('page-header')
    <h3>Trader Profile</h3>
@endsection

@section('content')
<?php
$trader_type = DB::table('trader_types')->where('id', $trader->trader_type)->value('name');
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card card-profile">
                <div class="card-avatar">
                    <img class="img" src="{{ asset('storage/'.$trader->image)}}">
                </div>
                <div class="card-body">
                    <h4 class="card-title">{{ strtoupper($trader->full_name) }}</h4>
                    <h5 class="card-category">Trader ID: <strong>{{ strtoupper($trader->trader_id) }}</strong></h5>
                    <p class="card-description">Account type is a <strong>{{ ucwords($trader_type) }} Trader</strong></p>
                    @if(auth()->user()->role != 'cs-agent')
                    <div>
                        <a href="{{ url('/admin/preview_mou/'.$inv->id) }}" target="_blank"><button class="btn btn-primary">Preview MOU</button></a>
                        @if(auth()->user()->role == 'superuser')
                            <a href="{{ url('/admin/edit_trader/'.$trader->id) }}" target="_blank">
                                <button class="btn btn-warning">Edit</button>
                            </a>
                            <a href="{{ url('/admin/delete_trader/'.$trader->id) }}">
                                <button class="btn btn-danger">Delete</button>
                            </a>
                            @if($inv->status == 0 && $inv->capital == 0)
                                <button class="btn btn-success" id="reactivateAcct" data-toggle="modal" data-target="#archTivate">Re-Activate Account</button>
                            @else
                                <button class="btn btn-secondary" id="archiveAcct" data-toggle="modal" data-target="#archTivate">Archive Account</button>
                            @endif
                        @endif
                        <!-- Archive and Reactivate Modal -->
                        <div class="modal fade" id="archTivate">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">Modal Heading</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        Modal body..
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <span id="loader"><img src="{{ asset('images/loader.gif') }}" width="25"> Loading...</span>
                                        <form id="acctStatForm">
                                            <input type="hidden" name="_token" id="acctStatTok" value="{{ csrf_token() }}">
                                            <input type="hidden" name="trader_id" id="trader_id" value="{{ $trader->trader_id }}">
                                            <input type="hidden" name="acctStat" id="acctStat" value="">
                                            <button type="submit" class="btn btn-success" id="acctStatBtn">Proceed</button>
                                        </form>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>Bank Account Info</h4></div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table" style="font-size:14px;">
                            <tbody>
                                <tr>
                                    {{-- <td>{{ print_r($bank) }}</td> --}}
                                    <td><strong>Bank Name</strong></td>
                                    <td>{{ strtoupper($bank->bank_name) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Holder's Name</strong></td>
                                    <td>{{ ucwords($bank->holder_name) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Account Number</strong></td>
                                    <td>{{ str_pad($bank->account_number, 10, 0, STR_PAD_LEFT) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>Personal Details</h4></div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tbody style="font-size:15px;">
                                <tr>
                                    <td><strong>Full Name</strong></td>
                                    <td>{{ ucwords($trader->full_name) }}</td>
                                </tr>
                                @if ($trader->trader_type != 3)
                                <tr>
                                    <td><strong>Marital Status</strong></td>
                                    <td>{{ ucwords($trader->marital_status) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Gender</strong></td>
                                    <td>{{ ucwords($trader->gender) }}</td>
                                </tr>
                                @endif
                                <tr>
                                    @if ($trader->trader_type == 3)
                                    <td><strong>Date of Incorporation</strong></td>
                                    @else
                                    <td><strong>Date of Birth</strong></td>
                                    @endif

                                    <td>{{ $trader->dob }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Address</strong></td>
                                    <td>{{ $trader->address }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nationality</strong></td>
                                    <td>{{ ucwords($trader->nationality) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>State</strong></td>
                                    <td>{{ ucwords($trader->state) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>City</strong></td>
                                    <td>{{ ucwords($trader->lga) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email</strong></td>
                                    <td><a href="mailto:{{ strtolower($trader->email) }}" style="color:#E2A921; text-decoration:underline;">{{ strtolower($trader->email) }}</a></td>
                                </tr>
                                <tr>
                                    <td><strong>Phone</strong></td>
                                    <td>0{{ $trader->phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Other Phone</strong></td>
                                    <td>{{ $trader->other_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{NOK / Parent / Rep} Name</strong></td>
                                    <td>{{ ucwords($trader->contact_name) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>{NOK / Parent / Rep} Phone</strong></td>
                                    <td>0{{ $trader->contact_phone }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Referral</strong></td>
                                    <td>{{ $trader->referral }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md">
            <div class="card">
                <div class="card-header card-header-warning" style="background:#E2A921;">
                    <div class="card-title" style="font-weight:500;"><h4>Investment Details</h4></div>
                </div>

                <div class="card-body">
                    <h3 class="text-success">Active Investment</h3>
                    @if ($inv->status == 2)
                    <div class="table-responsive">
                        <table class="table">
                            <thead style="font-size:15px;">
                                <th>Amount</th>
                                <th>Amount in Words</th>
                                <th>Monthly ROI</th>
                                <th>Monthly %</th>
                                <th>Duration</th>
                                <th>Purpose</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>...</th>
                            </thead>
                            <tbody style="font-size:14px;">
                                <tr>
                                    <td>{{ number_format($inv->amount) }}</td>
                                    <td>{{ $inv->amount_in_words }}</td>
                                    <td>{{ number_format($inv->monthly_roi) }}</td>
                                    <td>{{ $inv->monthly_pcent }}</td>
                                    <td>{{ $inv->duration }}</td>
                                    <td>{{ $inv->purpose }}</td>
                                    <td>{{ $inv->start_date }}</td>
                                    <td>{{ $inv->end_date }}</td>
                                    <td>@if(auth()->user()->role == 'superuser')
                                        <button class="btn btn-info" style="padding:7px;" id="updtActInv" data-toggle="modal" data-target="#actInvModal"> Edit </button>
                                        @endif
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- Active Investment Modal -->
                        <div class="modal fade" id="actInvModal">
                            <div class="modal-dialog">
                                <div class="modal-content" style="background:#202940; color:#fff;">

                                    <!-- Modal Header -->
                                    <div class="modal-header">
                                        <h4 class="modal-title">Update Investment</h4>
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <!-- Modal body -->
                                    <div class="modal-body">
                                        <form method="POST" action="updateActInv" id="actInvForm" style="padding:5px;">
                                            <input type="hidden" name="_token" id="acctStatTok" value="{{ csrf_token() }}">
                                            <div class="row">
                                                <div class="col-sm-4"><strong>Trader ID:</strong></div>
                                                <div class="col-sm-8"><input type="text" class="form-control" name="trader_id" id="trader_id" value="{{ $trader->trader_id }}" style="color:#000;" readonly></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4"><strong>Amount:</strong></div>
                                                <div class="col-sm-8"><input type="number" class="form-control" name="amount" id="amount" value="{{ $inv->amount }}"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4"><strong>Amount in Words:</strong></div>
                                                <div class="col-sm-8"><input type="text" class="form-control" name="amount_words" id="amount_words" value="{{ $inv->amount_in_words }}"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4"><strong>Monthly %:</strong></div>
                                                <div class="col-sm-8"><input type="number" class="form-control" name="month_pcent" id="month_pcent" value="{{ $inv->monthly_pcent }}"></div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-4"><strong>Monthly ROI:</strong></div>
                                                <div class="col-sm-8"><input type="number" class="form-control" name="month_roi" id="month_roi" value="{{ $inv->monthly_roi }}"></div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- Modal footer -->
                                    <div class="modal-footer">
                                        <input type="hidden" id="calRoi" value="{{ url('apply/calRoi/') }}">
                                        <button type="button" class="btn btn-success" id="updateInv">Update</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                        <p class="text-warning" style="text-align:center; font-style:italic;">No active investment</p>
                    @endif
                    <h3 class="text-danger">Investment History</h3>
                    <div class="table-responsive" style="max-height:500px; overflow-y:scroll;">
                        <table class="table">
                            <thead style="font-size:15px;">
                                <th></th>
                                <th>Type</th>
                                <th>Amount (&#8358;)</th>
                                <th>Amount in Words</th>
                                <th>Monthly ROI (&#8358;)</th>
                                <th>Monthly %</th>
                                <th>Duration</th>
                                <th>Purpose</th>
                                <th>Created Date</th>
                                <th>Transaction ID</th>
                            </thead>
                            <tbody style="font-size:14px;">
                                @foreach($invLog as $log)
                                <tr>
                                    <td>
                                        <?php
                                            if ($log->status == 0) {
                                                echo '<span class="text-danger"><i class="fa fa-times-circle-o"></i></span>';
                                            }
                                            if ($log->status == 1) {
                                                echo '<span class="text-warning"><i class="fa fa-dot-circle-o"></i></span>';
                                            }
                                            if ($log->status == 2) {
                                                echo '<span class="text-success"><i class="fa fa-check-circle-o"></i></span>';
                                            }
                                        ?>
                                    </td>
                                    <td>{{ $log->investment_type }}</td>
                                    <td>{{ number_format($log->amount) }}</td>
                                    <td>{{ $log->amount_in_words }}</td>
                                    <td>{{ number_format($log->monthly_roi) }}</td>
                                    <td>{{ $log->monthly_pcent }}</td>
                                    <td>{{ $log->duration }}</td>
                                    <td>{{ $log->purpose }}</td>
                                    <td>{{ $log->created_at }}</td>
                                    <td>
                                        <input type="text" id="{{ 'log_id'.$log->id }}" class="form-control" value="{{ 'trans#'.str_pad($log->id, 9, 0, STR_PAD_LEFT) }}" style="width:100px; color:#666;" readonly onfocus="clone(this.value)">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        {{-- print_r($invLog) --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.5.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    function clone(element) {
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(element).select();
        document.execCommand("copy");
        $(this).attr('title', "Copied");
        alert("Copied " + element);
        $temp.remove();
    }
    $('#reactivateAcct').click(function(){
        $('#acctStat').val('react');
        $('.modal-title').text("Reactivate Trader Account");
        $('.modal-body').html("Do you want to reactivate trader <strong>"+$('#trader_id').val()+"</strong> account?");
    });
    $('#archiveAcct').click(function(){
        $('#acctStat').val('arch');
        $('.modal-title').text("Archive Trader Account");
        $('.modal-body').html("Do you want to archive trader <strong>"+$('#trader_id').val()+"</strong> account?");
    });
    $('#loader').css('display', 'none');
    $('#acctStatForm').submit(function(event) {
        event.preventDefault();
        $('#loader').css('display', 'block');
        $('#acctStatBtn').attr('disabled');
        let archtivate = $('#acctStat').val();
        let token = $('#acctStaTok').val();
        let trader_id = $('#trader_id').val();
        $.ajax({
            url: "archtivate/"+archtivate+"/"+trader_id,
            type: "GET",
            dataType: 'JSON',
            success: function(data){
                if ($.isEmptyObject(data.error)){
                    //alert('Working '+trader_id);
                    alert(data.msg);
                    location.reload(true);
                } else{
                    alert("Error");
                }
                $("#loader").css('display', 'none');
            }
        });
    });
    $('#updateInv').click(function () {
        $('#actInvForm').submit();
    });
</script>
@endsection
