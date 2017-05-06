@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-md-offset-0">

        <div class="panel panel-success">
            <div class="panel-heading">Search Product</div>
            <div class="panel-body">
                
            <form action="{{ route ('products.index')}}" method="get">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group {{ $errors->has('state_id') ? 'has-error' : false }}">
                            {!! Form::label('search_state', 'State', ['class' => 'control-label']); !!}
                            {!! Form::select('search_state', $states, Request::get('search_state'), ['placeholder' => 'Pick a state..', 'class' => 'form-control', 'id'=> 'state_id']); !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group {{ $errors->has('category_id') ? 'has-error' : false }}">
                            {!! Form::label('search_category', 'Category', ['class' => 'control-label']); !!}
                            {!! Form::select('search_category', $categories, Request::get('search_category'), ['placeholder' => 'Pick a Category','class'=>'form-control', 'id' => 'category_id']); !!}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            {!! Form::label('search_brand', 'Brand', ['class' => 'control-label']); !!}
                            {!! Form::select('search_brand', $brands, Request::get('search_brand'), ['placeholder' => 'Pick a brand','class'=>'form-control']); !!}
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            {!! Form::label('search_anything', 'By Product Name/Desc', ['class' => 'control-label']); !!}
                            {!! Form::text('search_anything', Request::get('search_anything'), ['class'=>'form-control']); !!}
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('search_area') ? ' has-error' : '' }}">
                            {!! Form::label('search_area', 'Area') !!}
                            {!! Form::select('search_area', [], Request::get('area_id'), ['placeholder' => 'Pick an area ..', 'class' => 'form-control', 'id'=> 'area_id']); !!}
                            <small class="text-danger">{{ $errors->first('search_area') }}</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group{{ $errors->has('search_subcategory') ? ' has-error' : '' }}">
                            {!! Form::label('search_subcategory', 'Subcategory') !!}
                            {!! Form::select('search_subcategory', [], Request::get('subcategory_id'), ['placeholder' => 'Pick a Subcategory','class'=>'form-control' , 'id' => 'subcategory_id']); !!}
                            <small class="text-danger">{{ $errors->first('search_subcategory') }}</small>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group" style="padding-top: 27px;">
                            <button type="submit" class="btn btn-success">Search</button>
                        </div>
                    </div>
                </div>
            </form>

            </div>
        </div>

            <div class="panel panel-success">
                <div class="panel-heading">Products</div>

                <div class="panel-body">
                    
                    
                    <table class="table table-bordered table-striped table-hover">
                        <thead>
                            <tr>
                                <th style="width: 150px">Thumbnails</th>
                                <th>Product Title</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Condition</th>
                                <th>Location</th>
                                <th>Subcategories</th>
                                <th>Brand</th>
                                <th>Seller</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($products as $product)

                            <tr>
                                <td>
                                    @if(!empty($product->product_image))
                                        <img src="{{ asset('storage/uploads/'.$product->product_image) }}" class="img-thumbnail">
                                    @else
                                        <img src="{{ asset('storage/uploads/default_thumbnails.png') }}" class="img-thumbnail">
                                    @endif

                                </td>
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
                                <td>
                                    {!! Form::open(['method' => 'POST', 'route' => ['products.destroy', $product->id], 'class' => 'form-horizontal']) !!}

                                        {!! Form::hidden('_method', 'DELETE') !!}

                                        {{ csrf_field() }}
                                    
                                        <div class="btn-group pull-right">
                                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-success btn-mini">Edit</a>
                                            {!! Form::button("Delete", ['class' => 'btn btn-danger delete', 'role' => 'button']) !!}
                                        </div>
                                    
                                    {!! Form::close() !!}
                                </td>
                            </tr>

                        @endforeach
                        </tbody>
                    </table>
                    
                    {{-- link to create a new product --}}
                    <a href="{{ route('products.create') }}" class="btn btn-success pull-right">Create Product</a>

                    <!-- pagination link -->
                    {{ $products->appends(Request::except('page'))->links() }}

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section ('script')

    <script type="text/javascript">
        
        $( document ).ready(function()
        {

            //bila pengguna select state, panggil ajax function getarea by state
            $('#state_id').change(function(){
                var state_id=$(this).val();
                getStateAreas(state_id);
            });

            //bila pengguna klik pada pagination, reload balik areas based on selected state

            var selected_state_id = '{{ Request::get('search_state') }}';

            if (selected_state_id.length>0){
                // console.log("Kita akan panggil balik ajax unutk dapatkan area");
                getStateAreas(selected_state_id);
            }

            function getStateAreas(state_id){

                var ajax_url = '/products/areas/' + state_id;
                $.get( ajax_url, function( data ) {
                  //console.log(data);

                    $('#area_id').empty().append('<option value="">Select Area</option>');

                    //loop data untuk hasilkan senarai option baru bagu setiap dropdown
                    $.each(data, function(area_id, area_name){
                        // console.log(area_id);
                        // console.log(area_name);
                        $('#area_id').append('<option value=' +area_id+ '>' +area_name+ '</option>');
                    });

                    var selected_area_id = '{{ Request::get('search_area') }}';

                    if(selected_area_id.length>0){
                        $('#area_id').val(selected_area_id);
                    }
                });

            }

            //untuk category
            $('#category_id').change(function(){
                var category_id=$(this).val();
                getCategorySub(category_id);
            });

            //bila pengguna klik pada pagination, reload balik areas based on selected state

            var selected_category_id = '{{ Request::get('search_category') }}';

            if (selected_category_id.length>0){
                // console.log("Kita akan panggil balik ajax unutk dapatkan area");
                getCategorySub(selected_category_id);
            }

            function getCategorySub(category_id){

                var ajax_url = '/products/subcategories/' + category_id;
                $.get( ajax_url, function( data ) {
                  //console.log(data);

                    $('#subcategory_id').empty().append('<option value="">Select Subcategory</option>');

                    //loop data untuk hasilkan senarai option baru bagu setiap dropdown
                    $.each(data, function(subcategory_id, name){
                        // console.log(subcategory_id);
                        // console.log(name);
                        $('#subcategory_id').append('<option value=' +subcategory_id+ '>' +name+ '</option>');
                    });

                    var selected_subcategory_id = '{{ Request::get('search_subcategory') }}';

                    if(selected_subcategory_id.length>0){
                        $('#subcategory_id').val(selected_subcategory_id);
                    }
                });

            }

            //sweetalert
            $('.delete').click(function(){

                //dapatkan form terdekat dengan butang delete yang kita tekan
                var closest_form = $(this).closest('form');

                //sweetalert confirmation
                swal({
                    title: "Are you sure?",
                    text: "You will not be able to recover this imaginary file!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!",
                    closeOnConfirm: false
                },
                function(){
                    // swal("Deleted!", "Your imaginary file has been deleted.", "success");
                    // submit form yang terdekat
                    closest_form.submit();
                });

            });

        });

    </script>

@endsection
