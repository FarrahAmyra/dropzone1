@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-success">
                <div class="panel-heading">Edit Product</div>

                <div class="panel-body">

                <!-- Paparkan validation error -->
                @if (count($errors) > 0)
                <div class="alert alert-danger" role="alert">
                    <ul>
                        @foreach ($errors->all() as $message)
                            <li>{{ $message }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <!-- <a href="{{ route('admin.products.index') }}" class="btn btn-success pull-right">Cancel</a> -->

                <!-- {!! Form::open(['route/url' => 'route.name']) !!} -->
                {!! Form::open(['route' => ['admin.products.update', $product->id], 'method'=>'PUT','files' => true]) !!}
                    
                    <div class="form-group {{ $errors->has('category_id') ? 'has-error' : false }}">
                        {!! Form::label('category_id', 'Category', ['class' => 'control-label']); !!}
                        {!! Form::select('category_id', $categories, $product->subcategory->category_id, ['placeholder' => 'Pick a Category','class'=>'form-control', 'id' => 'category_id']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('subcategory_id') ? 'has-error' : false }}">
                        {!! Form::label('subcategory_id', 'Subcategory', ['class' => 'control-label']); !!}
                        {!! Form::select('subcategory_id', $subcategories, $product->subcategory_id, ['placeholder' => 'Pick a Subcategory','class'=>'form-control' , 'id' => 'subcategory_id']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('state_id') ? 'has-error' : false }}">
                        {!! Form::label('state_id', 'State', ['class' => 'control-label']); !!}
                        {!! Form::select('state_id', $states, $product->area->state_id, ['placeholder' => 'Pick a state..', 'class' => 'form-control', 'id'=> 'state_id']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('area_id') ? 'has-error' : false }}">
                        {!! Form::label('area_id', 'Area', ['class' => 'control-label']); !!}
                        {!! Form::select('area_id', $areas, $product->area_id, ['placeholder' => 'Pick an area ..', 'class' => 'form-control', 'id'=> 'area_id']); !!}
                    </div>

                    <div class="form-group {{ $errors->has('brand_id') ? 'has-error' : false }}">
                        {!! Form::label('brand_id', 'Brand', ['class' => 'control-label']); !!}
                        {!! Form::select('brand_id', $brands, $product->brand_id, ['placeholder' => 'Pick a brand','class'=>'form-control']); !!}
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
                        {!! Form::radio('condition', 'new', false); !!}New
                        {!! Form::radio('condition', 'used', false); !!}Used
                    </div>

                    <div class="form-group {{ $errors->has('product_image') ? 'has-error' : false }}">
                        {!! Form::label('product_image', 'Image', ['class' => 'control-label']); !!}
                        {!! Form::file('product_image', '', ['class'=>'form-control']); !!}
                    </div>

                    <div class="form-group">
                        @if (!empty($product->product_image))
                            <img src="{{ asset('storage/uploads/'.$product->product_image) }}" class="img-responsive">
                        @endif
                    </div>

                    <div class="form-group pull-right btn-group">
                        <button type="submit" name="submit" class="btn btn-success">Submit</button>
                        <button class="btn btn-danger" type="reset" name="reset">Reset</button>
                    </div>

                {!! Form::close() !!}

                <a href="{{ route('admin.products.index') }}"><i class="btn btn-success" role="button">Back</i></a>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('script')
    <script type="text/javascript">
        $( document ).ready(function() {
            // console.log( "Sekarang berada di form create product!" );

            var selected_state_id = '{{ old('state_id') }}';
            console.log(selected_state_id);

            //kalau ada selected state id kita akan panggil function getStateArea untuk dapatkan area
            if (selected_state_id.length>0){
                // console.log("Kita akan panggil balik ajax unutk dapatkan area");
                getStateAreas(selected_state_id);
            }

            function getStateAreas(state_id){

                var ajax_url = 'admin/products/areas/' + state_id;
                $.get( ajax_url, function( data ) {
                  console.log(data);

                    $('#area_id').empty().append('<option value="">Select Area</option>');

                    //loop data untuk hasilkan senarai option baru bagu setiap dropdown
                    $.each(data, function(area_id, area_name){
                        // console.log(area_id);
                        // console.log(area_name);
                        $('#area_id').append('<option value=' +area_id+ '>' +area_name+ '</option>');
                    });

                    var selected_area_id = '{{ old('area_id') }}';

                    if(selected_area_id.length>0){
                        $('#area_id').val(selected_area_id);
                    }
                });

            }




            var selected_category_id = '{{ old('category_id') }}';
            console.log(selected_category_id);

            //kalau ada selected state id kita akan panggil function getStateArea untuk dapatkan area
            if (selected_category_id.length>0){
                //console.log("Kita akan panggil balik ajax unutk dapatkan subcategory_id");
                getCategorySub(selected_category_id);   
            }

            function getCategorySub(category_id){

                var ajax_url = 'admin/products/subcategories/' + category_id;
                $.get( ajax_url, function( data ) {
                  console.log(data);

                    $('#subcategory_id').empty().append('<option value="">Select Subcategory</option>');
                    $.each(data, function(subcategory_id, name){
                        // console.log(subcategory_id);
                        // console.log(name);
                        $('#subcategory_id').append('<option value=' +subcategory_id+ '>' +name+ '</option>');
                    });

                    var selected_subcategory_id = '{{ old('subcategory_id') }}';

                    if(selected_subcategory_id.length>0){
                        $('#area_id').val(selected_subcategory_id);
                    }
                });
            }
        });
    </script>
@endsection
