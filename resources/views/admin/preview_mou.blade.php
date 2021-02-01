<?php
$amountWords = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
#echo print_r(get_loaded_extensions());
$start_date = date("jS F Y", strtotime($getTraderInfo->start_date));
$end_date = date("jS F Y", strtotime($getTraderInfo->end_date));
?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>MOU - Yellow Traders</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style type="text/css">
        @font-face {
            font-family: 'Open Sans', sans-serif;
            src: url({{storage_path('fonts/OpenSans-Regular.ttf')}}) format("truetype");
        }
        body{
			margin: 0 auto;
			width: 600px;
			font: 13px 'Open Sans', sans-serif;
			background: #fff;
			color: #000;
		}
        #main{
            background:#FFF;
        }
        #content p{
            text-align: justify; line-height: 1.5em;
        }
        h4{
	        font-family: Cambria;
            font-weight: 600;
            font-size: 17px;
        }
        #client_info p span{
            font-weight: 600; font-family: 'Open Sans', sans-serif;
        }
        span{
            font-family: 'Open Sans', sans-serif;
        }
        #sendMail{ cursor: pointer; }
    </style>
</head>
<body>{{--
	<div style="padding:10px; margin-bottom:10px;">
        <button class="btn btn-default" onclick="window.print();" style="float:right;"><i class="fa fa-print"></i> Print</button>
        <button class="btn btn-default"><i class="fa fa-envelope"></i> Send Mail</button>
    </div>--}}
    <div id="loader"><button class="btn btn-default" id="sendMail"><i class="fa fa-envelope"></i> Send Mail</button>
        {{--<img src="{{ asset('images/loader.gif') }}" width="25"> Sending MOU to trader...--}}</div>
    <div id="main" style="width:595px; max-height:842px; background:#FFF;">
        <div id="header" style="width:100%; height:20%;">
            <table style="width:100%; font-family:'Open Sans', sans-serif;">
				<tr>
					<td style="width:60%; text-align:left;">
                        <img src="{{ public_path('images/logo.png') }}" style="width:120px;">
                        {{-- storage_path('images/logo.png') --}}
					</td>
                    <td style="width:40%; text-align:left; font-family:'Open Sans', sans-serif;">
                        <p style="line-height:1.5em; font-weight:500; font-family:'Open Sans', sans-serif;">
                            17, Iyalla Street, Beside Shoprite,
                            <br>Alausa ikeja, Lagos.
                            <br>yellowtrade018@gmail.com
                            <br>yellowcare@yellowtraders.org
                            <br>+2349044777007, +2349044777077,
                            <br>+2349044777017, +2349044777117
                        </p>
					</td>
				</tr>
			</table>
		</div>
		<div id="content" style="width:100%; height:66%;">
            <div id="client_info" style="margin: 20px 0px 20px 0px; text-align:left; font-family:'Open Sans', sans-serif;">
                <p><span>DATE:</span> {{ $start_date }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>TRADER ID:</span> {{ strtoupper($getTraderInfo->trader_id) }}
                </p>
		        <p><span>CLIENT'S NAME:</span> {{ strtoupper($getTraderInfo->full_name) }}</p>
                <p><span>CLIENT'S ADDRESS:</span> {{ strtoupper($getTraderInfo->address) }}</p>
                <input type="hidden" name="trader_id" id="trader_id" value="{{ $getTraderInfo->trader_id }}">
                <input type="hidden" name="email" id="email" value="{{ $getTraderInfo->email }}">
            </div>
            <p>Sir / Ma</p>
            <h4 style="text-align: center;">MEMORANDUM OF UNDERSTANDING</h4>
            <p>You have invested the sum of <strong>#<?php echo number_format($getTraderInfo->amount) ?>
                 ({{ ucwords($amountWords->format($getTraderInfo->amount)) }} Naira Only)</strong> from this
                date {{ $start_date }} to {{ $end_date }}, a {{$amountWords->format($getTraderInfo->duration)}}
                months period with {{$getTraderInfo->monthly_pcent}}% monthly ROI.</p>

            <p>Withdrawal of Capital can only be at the expiration of your contract, withdrawal before the expiration is not allowed.
                Please note that you will have to formally request a withdrawal of your capital after the expiration of the contract.</p>

            <p>Yellow Traders reserves the right to terminate your investment contract before the expiration date if you fail to
                comply with the laid down processes of the company.</p>

            <p>This letter serves as a formal agreement between <strong>Yellow Traders</strong> and its investor
                in any case of trade which doesn't favour us, there will be a capital refund to client.</p>
            <p>Thank you.</p>
            <p>&nbsp;</p>
            <p style="text-align:center;"><img src="{{ public_path('images/Yellow-Point-Stamp.png') }}" width="100"></p>
		</div>
		{{--<div id="footer" style="width:100%; text-align:center;">
			<div><img src="{{ public_path('images/Yellow-Point-Stamp.png') }}" width="100"></div>
		</div>--}}
    </div>
    <script src="https://code.jquery.com/jquery-3.5.0.js"></script>
	<script>
        $(document).ready(function () {
            //console.log($('#email').val() + $('#trader_id').val());
            $('#sendMail').click(function(event){
                event.preventDefault();
                //alert("Sending MOU... Please wait");
                $('#loader').html('<span>Sending MOU... please wait</span>');
                //$('#loader').css('display', 'block');
                let trader_id = $('#trader_id').val();
                let email = $('#email').val();
                $.ajax({
                    url: "send/"+email+"/"+trader_id,
                    type: "GET",
                    dataType: 'JSON',
                    success: function(data){
                        if ($.isEmptyObject(data.error) && data.msg != ""){
                            //alert('Working '+trader_id);
                            alert(data.msg);
                            $('#loader').html('<span style="color:#0f0;">MOU sent succesfully</span>');
                        } else{
                            alert("Error sending MOU to trader");
                        }
                        //$('#loader').css('display', 'none');
                    }
                });
            });
        });
    </script>
</body>
</html>
