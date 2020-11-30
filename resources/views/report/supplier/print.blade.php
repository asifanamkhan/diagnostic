<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Font Awesome -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
	<!-- Theme style -->
	<link href="{{ asset('css/main.css') }}" rel="stylesheet">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
	<style type="text/css">
		table tfoot {display:table-row-group;}
	</style>
</head>
<body onload="window.print();">
	<div class="wrapper">
		<section class="invoice">
			<div class="row">
				<div class="col-12" style="text-align: center;">
					<h2 class="page-header"> 
						Diagnostic Limited
					</h2>
					<address>Plot# 529/1, Bypass, National University,
						Gazipur, Bangladesh
					</address>
					<small class="float-right">Date: {{ $date }}</small>
				</div>
			</div>
			<br>
			<div class="row invoice-info">
				<div class="col-sm-12 invoice-col">
					<h4>Expense Report from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
					</h4>
				</div>
			</div>
			<br>
			<div class="row">
				<div class="col-12 table-responsive">
					<table style="width: 100%;" class="table table-hover table-striped table-bordered">
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
			</div>
		</section>
	</div>
</body>
</html>
