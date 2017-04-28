@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">Products</div>

                <div class="panel-body">

                <a href="{{ route('products.create') }}" class="btn btn-success pull-right">Create Product</a>
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Product Title</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Condition</th>
                                <th>Location</th>
                                <th>Subcategories</th>
                                <th>Brand</th>
                                <th>Seller</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)

                            <tr>
                                <td>{{ $product->product_name }}</td>
                                <td>{{ $product->product_desc }}</td>
                                <td>{{ $product->price }}</td>
                                <td>{{ $product->condition }}</td>
                                <td>
                                    {{ $product->area->area_name }}, {{ $product->area->state->state_name }}
                                </td>
                                <td>{{ $product->subcategory->name }}</td>
                                <td>{{ $product->brand->brand_name }}</td>
                                <td>{{ $product->user->name }}</td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
