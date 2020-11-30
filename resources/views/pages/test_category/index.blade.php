@extends('layouts.master')
@section('title', 'Test Categories')

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
                        Test Categories
                        <div class="page-title-subheading">
                            Categories of Tests.
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
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Sl#</th>
                            <th width="14%">Name</th>
                            <th width="8%">Code</th>
                            <th width="15%">Created By</th>
                            <th width="15%">Updated By</th>
                            <th width="40%">Description</th>
                            <th width="8%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($testCategories as $testCategory)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $testCategory->name }}</td>
                                <td>{{ $testCategory->code }}</td>
                                <td>{{ $testCategory->createdBy->name }}</td>
                                <td width="13%">
                                    @if ($testCategory->updated_by)
                                        {{ $testCategory->updatedBy->name }}
                                    @else
                                        {{'--'}}
                                    @endif
                                </td>
                                <td>{{ $testCategory->description }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('test-category.edit', $testCategory->id) }}" title="Edit">
                                            <button class=" mr-2 btn-icon btn-icon-only btn btn-success">
                                                <i class="pe-7s-tools btn-icon-wrapper"></i>
                                            </button>
                                        </a>
                                        <form action="{{ route('test-category.destroy', $testCategory->id) }}" method="POST" class="delete_form">
                                            @csrf
                                            @method('DELETE')
                                            <button class=" mr-2 btn-icon btn-icon-only btn btn-danger">
                                                <i class="pe-7s-trash btn-icon-wrapper"></i>
                                            </button>
                                        </form>
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
