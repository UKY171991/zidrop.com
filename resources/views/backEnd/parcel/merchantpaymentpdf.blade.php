
<!DOCTYPE html>
<html>
<head>
    <title>Merchant Payment PDF</title>
    <style>
        /* Page styles */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        /* Table styles */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
            border: 1px solid #ddd; /* Add border to all cells */
        }

        th {
            background-color: #f2f2f2;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <img src="{{ asset('logo.png') }}" style="width:200%; max-width:200px;">
    <h1 class="text-center">Returned To Merchant Payment</h1>
  {{-- print button --}}
      

    <table>
        <thead>
            <tr>
                <th>SL</th>
                <th>Date</th>
                <th>Invoice</th>
                <th>Total Return Due</th>
                <th>Merchant</th>
                <th>Balance</th>
            </tr>
        </thead>
        
        <tbody>
            @foreach ($show_data as $key => $value)

                <tr>
                    <td>{{$key+1}}</td>
                     <td>{{ date('d-m-Y', strtotime(today())) }}</td>
                    <td>{{count($value->parcels)}}</td>
                    <td>N {{ number_format($value->charge, 2, '.', ',') }}</td>
                    <td>{{$value->companyName }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
        


