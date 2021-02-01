<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    Hello
    <?php
        $header = [
            'Customername',
            'Phone',
            'Amount Invested',
            'Monthly ROI',
            'Monthly %',
            'Bankname',
            'Bank Sort Code',
            'Account Number',
            'Duration'
        ];
        $filename = 'TradersToPay-'.date("Y-m-d").'.csv';
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename='.$filename);
        $output = fopen('php://output', 'w');
        fputcsv($output, $header);
        foreach ($tradersToPay as $payout) {
            $row = [
                $payout->full_name, $payout->phone, $payout->amount, $payout->monthly->roi, $payout->monthly_pcent,
                $payout->bankname, $payout->bank_sort_code, $payout->account_number, $payout->duration
            ];
            fputcsv($output, $row);
        }
        echo "Hello";
?>

</body>
</html>
