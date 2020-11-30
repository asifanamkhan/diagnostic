@extends('layouts.master')
@section('title', 'Service Report')

@section('content')
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="app-page-title">
                <div class="page-title-wrapper">
                    <div class="page-title-heading">
                        <div class="page-title-icon">
                            <i class="pe-7s-gleam text-success"></i>
                        </div>
                        <div>
                            Income Graph
                            <div class="page-title-subheading">
                                Report of Income.
                            </div>
                        </div>
                    </div>
                    <div class="page-title-actions">
                        <button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                            <i class="fa fa-star"></i>
                        </button>
                        <div class="d-inline-block dropdown"></div>
                    </div>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <form action="{{route('service.graph')}}" method="post">
                        @csrf
                        <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="start_date" class="">Start Date</label>
                                                <input name="start_date" id="start_date" placeholder="Start Date" type="text" class="datepicker form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date') }}">
                                                @error('start_date')
                                                <span class="is-invalid">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                <div class="valid-feedback">Looks Good!</div>
                                                <div class="invalid-feedback">This Field is Required</div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="end_date" class="">End Date</label>
                                                <input name="end_date" id="end_date" placeholder="End Date" type="text" class="datepicker form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date') }}">
                                                @error('end_date')
                                                <span class="is-invalid">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                                @enderror
                                                <div class="valid-feedback">Looks Good!</div>
                                                <div class="invalid-feedback">This Field is Required</div>
                                            </div>
                                        </div>
                                    </div>
                                    <button class="mt-2 btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </form>
                    <h4>
                        Income chart
                        @if ($start_date && $end_date)
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @elseif ($start_date && !$end_date)
                            from {{ Carbon\Carbon::parse($start_date)->format('d M, Y') }} to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @else
                            from the beginning to {{ Carbon\Carbon::parse($end_date)->format('d M, Y') }}
                        @endif
                    </h4>
                    <div class="row">
                        <div class="col-md-9">
                            <canvas id="myChart" ></canvas>
                        </div>
                        <div class="col-md-3">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th>Total amount</th>
                                        <td>{{$total_amount}} (100%)</td>
                                    </tr>
                                    <tr>
                                        <th>Paid amount</th>
                                        <td>{{$final_amount}} @if($final_amount >0 )({{number_format(($final_amount/$total_amount)*100,2)}}%) @else % @endif</td>
                                    </tr>
                                    <tr>
                                        <th>Discount</th>
                                        <td>{{$discount}} @if($discount >0 )({{number_format(($discount/$total_amount)*100,2)}}%)@else % @endif</td>
                                    </tr>
                                    <tr>
                                        <th>Due amount</th>
                                        <td>{{$due_amount}} @if($due_amount >0 )({{number_format(($due_amount/$total_amount)*100,2)}}%) @else % @endif</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script>
        var discount = "{{$discount}}";
        var final_amount = "{{$final_amount}}";
        var due_amount = "{{$due_amount}}";
        var ctx = document.getElementById('myChart');
        var myChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Paid amount', 'Discount', 'Due amount', ],
                datasets: [{
                    label: '# of Votes',
                    data: [final_amount, discount, due_amount,],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',

                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',

                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                }
            }
        });
    </script>
@endsection