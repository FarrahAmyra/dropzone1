@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">View Product</div>

                <div class="panel-body">
                <h1>{{ $product->product_name }}</h1>
                {!! Form::open(['route' => ['products.update', $product->id], 'method'=>'PUT','files' => true]) !!}

                    <div class="form-group">
                        @if (!empty($product->product_image))
                            <img src="{{ asset('storage/uploads/'.$product->product_image) }}" class="img-responsive">
                        @endif
                    </div>
                    
                    <div class="form-group {{ $errors->has('category_id') ? 'has-error' : false }}">
                        {!! Form::label('category_id', 'Category', ['class' => 'control-label']); !!}
                        {!! Form::text('category_id', $product->subcategory->category->category_name, ['class'=>'form-control']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('subcategory_id') ? 'has-error' : false }}">
                        {!! Form::label('subcategory_id', 'Subcategory', ['class' => 'control-label']); !!}
                        {!! Form::text('subcategory_id', $product->subcategory->name, ['class'=>'form-control']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('state_id') ? 'has-error' : false }}">
                        {!! Form::label('state_id', 'State', ['class' => 'control-label']); !!}
                        {!! Form::text('state_id', $product->area->state->state_name, ['class' => 'form-control']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('area_id') ? 'has-error' : false }}">
                        {!! Form::label('area_id', 'Area', ['class' => 'control-label']); !!}
                        {!! Form::text('area_id',$product->area->area_name, ['class' => 'form-control']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('brand_id') ? 'has-error' : false }}">
                        {!! Form::label('brand_id', 'Brand', ['class' => 'control-label']); !!}
                        {!! Form::text('brand_id', $product->brand->brand_name, ['class'=>'form-control']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('product_name') ? 'has-error' : false }}">
                        {!! Form::label('product_name', 'Product Name', ['class' => 'control-label']); !!}
                        {!! Form::text('product_name', $product->product_name, ['class'=>'form-control']); !!}
                    </div>
                    
                    <div class="form-group {{ $errors->has('product_desc') ? 'has-error' : false }}">
                        {!! Form::label('product_desc', 'Description', ['class' => 'control-label']); !!}
                        {!! Form::textarea('product_desc', $product->product_desc, ['class'=>'form-control']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('price') ? 'has-error' : false }}">
                        {!! Form::label('price', 'Price', ['class' => 'control-label']); !!}
                        {!! Form::text('price', $product->price, ['class'=>'form-control']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('condition') ? 'has-error' : false }}">
                        {!! Form::label('condition', 'Condition', ['class' => 'control-label']); !!}
                        {!! Form::text('condition', $product->condition, ['class'=>'form-control']); !!}
                    </div>

                    

                {!! Form::close() !!}

                <a href="{{ route('products.index') }}"><i class="btn btn-success" role="button">Back</i></a>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection