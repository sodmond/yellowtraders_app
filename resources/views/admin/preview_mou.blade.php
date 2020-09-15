<?php
$amountWords = new \NumberFormatter("en", \NumberFormatter::SPELLOUT);
#echo print_r(get_loaded_extensions());
$start_date = date("jS F Y", strtotime($getTraderInfo->start_date));
$end_date = date("jS F Y", strtotime($getTraderInfo->end_date));
?>
<!DOCTYPE html>
<html>
<head>
	<title>MOU - Yellow Traders</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style.css">
    <style type="text/css">
        body{
			margin: 0 auto;
			width:600px;
			font: 14px arial;
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
        }
        #client_info p span{
            font-weight: 600;
        }
        #footer table{
            width:100%;
        }
        #footer table tr td{
            width: 50%;
            text-align: center;
            padding: 30px 20px;
        }
        #footer table span{
            border-top: 1px solid #000;
            padding: 5px 50px;
            font-weight: 600;
	        font-size: 13px;
    	    text-transform: uppercase;
	        text-decoration: none;
        }
    </style>
</head>
<body>
	<div style="padding:10px; margin-bottom:20px;">
        <button class="btn btn-default" onclick="window.print();" style="float:right;"><i class="fa fa-print"></i> Print</button>
    </div>
	<div id="main" style="width:595px; max-height:842px; background:#FFF;">
		<div id="header" style="width:100%; height:20%;">
			<table style="width:100%;">
				<tr>
					<td style="width:60%; text-align:left;">
                        <p style="line-height:1.5em; font-weight:500;">
                            17, Iyalla Street, Beside Shoprite,
                            <br>Alausa ikeja, Lagos.
                            <br>yellowtrade018@gmail.com
                            <br>yellowcare@yellowtraders.org
                            <br>+2349044777007, +2349044777077,
                            <br>+2349044777017, +2349044777117
                        </p>
					</td>
                    <td style="width:40%; text-align:right;">
                        <img src="{{ url('images/logo.png') }}" style="width:120px;">
					</td>
				</tr>
			</table>
		</div>
		<div id="content" style="width:100%; height:66%; padding:5px;">
            <div id="client_info" style="margin: 20px 0px 20px 0px; text-align:left;">
                <p><span>DATE:</span> {{ $start_date }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span>TRADER ID:</span> {{ strtoupper($getTraderInfo->trader_id) }}
                </p>
		        <p><span>CLIENT'S NAME:</span> {{ strtoupper($getTraderInfo->full_name) }}</p>
                <p><span>CLIENT'S ADDRESS:</span> {{ strtoupper($getTraderInfo->address) }}</p>
            </div>
            <p>Sir / Ma</p>
            <h4 style="text-align: center;">AGREEMENT LETTER</h4>
            <p>You have invested the sum of <strong>#<?php echo number_format($getTraderInfo->amount) ?>
                 ({{ ucwords($amountWords->format($getTraderInfo->amount)) }} Naira Only)</strong> from this
                date {{ $start_date }} to {{ $end_date }}, a {{$amountWords->format($getTraderInfo->duration)}}
                months period with {{$getTraderInfo->monthly_pcent}}% monthly ROI.</p>
            <p>Please note that you can only withdraw your capital after {{$amountWords->format($getTraderInfo->duration)}}
                months contract, withdrawal before the expiration date is not allowed.</p>
            <p>This letter serves as a formal agreement between <strong>Yellow Traders</strong> and its investor
                in any case of trade which doesn't favour us, there will be a capital refund to client.</p>
            <p>Thank you.</p>
		</div>
		<div id="footer" style="width:100%; padding:20px;">
			<table>
                <tr>
                    <td><span>NAME OF CEO</span></td>
                    <td><span>SIGN / DATE</span></td>
                </tr>
                <tr>
                    <td><span>INVESTOR'S NAME</span></td>
                    <td><span>SIGN / DATE</span></td>
                </tr>
                <tr>
                    <td><span>LAWYER'S NAME</span></td>
                    <td><span>SIGN / DATE</span></td>
                </tr>
            </table>
		</div>
	</div>
	<script type="text/javascript" src="script.js"></script>
</body>
</html>
