<!DOCTYPE html>
<html>
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<style>
		table {
			border-collapse: collapse;
			border-spacing: 0;
			width: 100%;
			border: 1px solid #ddd;
		}

		th, td {
		    text-align: left;
		    padding: 8px;
		}

		tr:nth-child(even){background-color: #f2f2f2}
	</style>
</head>
<body>

<div class="col-12" style="text-align: center;">
	<h2 class="page-header"> 
		Diagnostic Limited
	</h2>
	<address>Plot# 529/1, Bypass, National University,
		Gazipur, Bangladesh
	</address>
	<small class="float-right">Date: {{ $date }}</small>
</div>
<br>
<h5>Expense Report from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}</h5>
<br>

<div>
  	<table>
    	<thead>
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
      	</tbody>
    	<tfoot>
    	    <tr>
    	        <th colspan="4">Total</th>
    	        <th>{{ number_format($total, 2) }}</th>
    	        <th></th>
    	    </tr>
    	</tfoot>
  	</table>
</div>
</body>
</html>
