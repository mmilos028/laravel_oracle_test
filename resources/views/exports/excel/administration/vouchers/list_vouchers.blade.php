<table>
    <thead>
		<tr>
			<th>Serial Number</th>
			<th>Amount</th>
			<th>Currency</th>
			<th>Refill Type</th>
			<th>Expiry Date</th>
			<th>Username</th>
			<th>Password</th>
			<th>Status</th>
			<th>Refill Allowed</th>
			<th>Prepaid Code</th>
    	</tr>
    </thead>
    <tbody>
    @foreach($data as $row)
    	<tr>
    		<td width="20">{{ $row['serial_number'] }}</td>
    		<td>{{ $row['amount'] }}</td>
    		<td>{{ $row['currency'] }}</td>
    		<td width="15">{{ $row['refill_type'] }}</td>
    		<td width="20">{{ $row['expiry_date'] }}</td>
    		<td>{{ $row['username'] }}</td>
    		<td>{{ $row['password'] }}</td>
    		<td>{{ $row['status'] }}</td>
    		<td width="10">{{ $row['refill_allowed'] }}</td>
    		<td width="20">{{ $row['prepaid_code'] }}</td>        
		</tr>
    @endforeach
    </tbody>
</table>