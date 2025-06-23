<table>
    <thead>
    <tr>
        <th>Transfer Amount</th>
        <th>Transfer Note </th>
        <th>Transfer Reference </th>
        <th>Recipient Code </th>
        <th>Bank Code or Slug </th>
        <th>Account Number </th>
        <th>Account Name </th>
        <th>Email Address </th>
    </tr>
    </thead>

    <tbody>
    @foreach ($show_data as $key => $value)
        @php
            $due = 0;
            foreach ($value->parcels as $parcel) {
                if ($parcel->status == 4 || $parcel->status == 6) {
                    // $due = $due + ($parcel->codCharge + $parcel->deliveryCharge - $parcel->cod);
                    $due = $due + $parcel->merchantDue;
                }
            }
        @endphp
        @if ($due > 0)
            <tr>
                <td>{{ $due }}</td>
                <td>SETTLEMENT</td>
                <td></td>
                <td></td>                
                <td>{{ $value->beneficiary_bank_code }}</td>
                <td>{{ $value->bankAcNo }}</td>
                <td>{{ $value->companyName }}</td>
                <td>{{ $value->emailAddress }}</td> 
            </tr>
        @endif
    @endforeach
    </tbody>
</table>