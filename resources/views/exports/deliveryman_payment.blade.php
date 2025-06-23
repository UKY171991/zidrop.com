

<table>
    <thead>
    <tr>
        <th>Transfer Amount</th>
        <th>Transfer Note</th>
        <th>Transfer Reference</th>
        <th>Recipient Code</th>
        <th>Bank Code or Slug</th>
        <th>Account Number</th>
        <th>Account Name</th>
        <th>Email Address</th>
    </tr>
    </thead>

    <tbody>
    @foreach ($show_data as $key => $value)

            <tr>
                <td>{{ $value->charge }}</td>
                <td>COMMISSION</td>
                <td></td>
                <td></td>
                <td>{{ $value->beneficiary_bank_code }}</td>
                <td>{{ $value->bankAcNo }}</td>
                <td>{{ $value->name }}</td>
                <td>{{ $value->email }}</td>
            </tr>
    @endforeach
    </tbody>
</table>

