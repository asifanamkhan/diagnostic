@extends('layouts.master')
@section('title', 'Users')

@section('content')
<div class="app-main__outer">
	<div class="app-main__inner">
		<div class="app-page-title">
			<div class="page-title-wrapper">
				<div class="page-title-heading">
					<div class="page-title-icon">
						<i class="pe-7s-graph text-success">
						</i>
					</div>
					<div>Users
						<div class="page-title-subheading">Description
						</div>
					</div>
				</div>
				<div class="page-title-actions">
					<button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
						<i class="fa fa-star"></i>
					</button>
					<div class="d-inline-block dropdown">
						
					</div>
				</div>    
			</div>
		</div>
		<div class="main-card mb-3 card">
            {{--<div class="card-header">--}}
                {{--<a href="{{ route('user.all.print') }}" title="Print" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-print">--}}
                        {{--<i class="pe-7s-print btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>--}}
                {{--<a href="{{ route('user.all.pdf') }}" title="PDF" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-pdf">--}}
                        {{--<i class="fa fa-file-pdf-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a> --}}
                {{--<a href="{{ route('user.all.excel') }}" title="Excel" target="_blank" class="editable-link">--}}
                    {{--<button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-excel">--}}
                        {{--<i class="fa fa-file-excel-o btn-icon-wrapper"></i>--}}
                    {{--</button>--}}
                {{--</a>              --}}
            {{--</div>--}}

            <div class="card-body">
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                	<thead>
                		<tr>
                            <th>Sl#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                	</thead>
                	<tbody>
                		@foreach ($users as $user)
                    		<tr>
                    			<td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                    			<td>{{ $user->email }}</td>
                    			<td>
                                    <div class="btn-group">
                                        <a href="{{ route('user.edit', $user->id) }}" title="Edit">
                                            <button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-success">
                                                <i class="pe-7s-tools btn-icon-wrapper"></i>
                                            </button>
                                        </a>
                                        @if ($user->id != 1)                                     
                                            <form action="{{ route('user.destroy', $user->id) }}" method="POST" class="delete_form">
                                                @csrf
                                                @method('DELETE')
                                                <button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-danger"><i class="pe-7s-trash btn-icon-wrapper"> </i></button>
                                            </form>
                                        @endif
                                    </div>        
                                </td>
                    		</tr>
                		@endforeach
                	</tbody>
                </table>
         	</div>
		</div>
	</div>
</div>
@endsection