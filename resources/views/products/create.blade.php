@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">New Product</div>

                <div class="panel-body">

                <!-- Paparkan validation error -->
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
                <!-- <a href="{{ route('products.index') }}" class="btn btn-success pull-right">Cancel</a> -->

                <!-- {!! Form::open(['route/url' => 'route.name']) !!} -->
                {!! Form::open(['route' => 'products.store']) !!}
                    
                    <div class="form-group">
                        {!! Form::label('product_name', 'Product Name', ['class' => 'control-label']); !!}
                        {!! Form::text('product_name', '', ['class'=>'form-control']); !!}
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('product_desc', 'Description', ['class' => 'control-label']); !!}
                        {!! Form::text('product_desc', '', ['class'=>'form-control']); !!}
                    </div>

                    <!-- <div class="form-group">
                        {!! Form::label('product_image', 'Image', ['class' => 'control-label']); !!}
                        {!! Form::file('product_image', '', ['class'=>'form-control']); !!}
                    
                    </div> -->

                    <div class="form-group">
                        {!! Form::label('price', 'Price', ['class' => 'control-label']); !!}
                        {!! Form::text('price', '', ['class'=>'form-control']); !!}
                        
                    </div>

                    <div class="form-group">
                        {!! Form::label('condition', 'Condition', ['class' => 'control-label']); !!}
                        {!! Form::radio('condition', 'new', true); !!}New
                        {!! Form::radio('condition', 'used', false); !!}Used
                    </div>

                    <div class="form-group">
                        {!! Form::label('state_id', 'State', ['class' => 'control-label']); !!}
                        {!! Form::select('state_id', $states, null, ['placeholder' => 'Pick a state..', 'class' => 'form-control', 'id'=> 'state_id']); !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('area_id', 'Area', ['class' => 'control-label']); !!}
                        {!! Form::select('area_id', [], null, ['placeholder' => 'Pick an area ..', 'class' => 'form-control', 'id'=> 'area_id']); !!}
                    </div>
                    
                    <div class="form-group">
                        {!! Form::label('brand_id', 'Brand', ['class' => 'control-label']); !!}
                        {!! Form::select('brand_id', $brands, null, ['placeholder' => 'Pick a brand','class'=>'form-control']); !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('category_id', 'Category', ['class' => 'control-label']); !!}
                        {!! Form::select('category_id', $categories, null, ['placeholder' => 'Pick a Category','class'=>'form-control', 'id' => 'category_id']); !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('subcategory_id', 'Subcategory', ['class' => 'control-label']); !!}
                        {!! Form::select('subcategory_id', [], null, ['placeholder' => 'Pick a Subcategory','class'=>'form-control' , 'id' => 'subcategory_id']); !!}
                    </div>

                    <!-- <div class="form-group">
                        {!! Form::label('user_id', 'ID', ['class' => 'control-label']); !!}
                        {!! Form::text('user_id', '', ['class'=>'form-control']); !!}
                    </div> -->

                    <div class="form-group pull-right">
                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        <button class="btn btn-danger"><a href="{{ route('products.index') }}" >Cancel</a></button>
                    </div>

                {!! Form::close() !!}
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
    <script type="text/javascript">
        $( document ).ready(function() {
            console.log( "Sekarang berada di form create product!" );

            $( "#state_id" ).change(function() {
                var state_id = $(this).val();
                console.log(state_id);

                var ajax_url = '/products/areas/' + state_id;
                $.get( ajax_url, function( data ) {
                  console.log(data);

                    $('#area_id').empty().append('<option value="">Select Area</option>');
                    $.each(data, function(area_id, area_name){
                        $('#area_id').append('<option value=' +area_id+ '>' +area_name+ '</option>');
                    });
                });
            });

            $( "#category_id" ).change(function() {
                var category_id = $(this).val();
                console.log(category_id);

                var ajax_url = '/products/subcategories/' + category_id;
                $.get( ajax_url, function( data ) {
                  console.log(data);

                    $('#subcategory_id').empty().append('<option value="">Select Subcategory</option>');
                    $.each(data, function(subcategory_id, name){
                        // console.log(subcategory_id);
                        // console.log(name);
                        $('#subcategory_id').append('<option value=' +subcategory_id+ '>' +name+ '</option>');
                    });
                });
            });
        });
    </script>
@endsection
