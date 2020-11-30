@extends('layouts.master')
@section('title', 'User-Update')

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
					<div>Update User
						<div class="page-title-subheading">Fieds marked with asterisk must be filled.
						</div>
					</div>
				</div>
				<div class="page-title-actions">
					<button type="button" data-toggle="tooltip" title="Example Tooltip" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
						<i class="fa fa-pencil-square-o"></i>
					</button>
					<div class="d-inline-block dropdown">
						
					</div>
				</div>    
			</div>
		</div>
		<div class="main-card mb-3 card">
            <div class="card-body">
                <form class="" method="POST" action="{{ route('user.update',$user->id) }}">
                	@csrf
                	@method('PUT')    
                	@include('pages.user.form')         
                </form>
         	</div>
		</div>
	</div>
</div>
@endsection