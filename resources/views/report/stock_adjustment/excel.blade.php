<table>
	<thead>
		<tr>
			<th colspan="6">Diagnostic Limited</th>
		</tr>
		<tr>
			<th colspan="6">
				Plot# 529/1, Bypass, National University, Gazipur, Bangladesh
			</th>
		</tr>
		<tr>
			<th colspan="6">
                Expense Report from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
            </th>
		</tr>		
		<tr>
            <th>Sl#</th>
            <th>Date</th>
            <th>Category</th>
            <th>Invoice</th>
            <th>Amount</th>
            <th>Created By</th>
        </tr>
	</thead>
	<tbody>
		@foreach ($expenses as $expense)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $expense->date }}</td>
                <td>{{ $expense->category->name }}</td>
                <td>{{ $expense->invoice }}</td>
                <td>{{ $expense->amount }}</td>
                <td>{{ $expense->createdBy->name }}</td>
            </tr>
        @endforeach
        <tr>
            <th colspan="4">Total</th>
            <th>{{ number_format($total, 2) }}</th>
            <th></th>
        </tr>
	</tbody>
	<tfoot>
		<tr>
            <th colspan="6">report generated on: {{ $date }}</th>
        </tr>
	</tfoot>
</table>
