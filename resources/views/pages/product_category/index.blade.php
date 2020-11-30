@extends('layouts.master')
@section('title', 'Product Categories')

@section('content')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-graph text-success"></i>
                    </div>
                    <div>
                        Product Categories
                        <div class="page-title-subheading">
                            A group of products that offer similar benefits can be referred to as product categories.
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
                            <th>Code</th>
                            <th>Name</th> 
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Description</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($product_categories as $product_category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td width="10%">{{ $product_category->code }}</td>
                                <td width="13%">{{ $product_category->name }}</td>
                                <td width="13%">{{ $product_category->createdBy->name }}</td>
                                <td width="13%">
                                    @if ($product_category->updated_by)
                                        {{ $product_category->updatedBy->name }}
                                    @else
                                        {{'--'}}
                                    @endif
                                </td>
                                <td>{{ $product_category->description }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('product-category.edit', $product_category->id) }}" title="Edit">
                                            <button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-success">
                                                <i class="pe-7s-tools btn-icon-wrapper"></i>
                                            </button>
                                        </a>
                                        <form action="{{ route('product-category.destroy', $product_category->id) }}" method="POST" class="delete_form">
                                            @csrf
                                            @method('DELETE')
                                            <button class="mb-2 mr-2 btn-icon btn-icon-only btn btn-danger">
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