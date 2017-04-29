<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Product;

use App\Brand;
use Auth;
use App\Area;
use App\Subcategory;
use App\User;
use App\State;
use App\Category;
use App\Http\Requests\CreateProductRequest;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //kalau nk guna relationship kalau dah ada data
        $products = Product::with('brand', 'user', 'area', 'subcategory');

        //additional searching
        if(!empty($request->search_anything)){
            $search_anything = $request->search_anything;

            $products = $products->where(function($query) use ($search_anything){
                $query->orWhere('product_name', 'Like', '%'.$search_anything. '%')
                ->orWhere('product_desc', 'Like', '%' .$search_anything. '%');
            });
        }

        //pagination
        $products = $products->paginate(3);

        //load additional data for searching
        $brands = Brand::pluck('brand_name', 'id');
        $categories = Category::pluck('category_name', 'id');
        $states = State::pluck('state_name', 'id');
        return view('products.index', compact('products', 'brands', 'categories', 'states')); //compaq == with('products') hk abe li ajar tuh
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //variable baru = nama model::pluck('id', 'brand_name');
        $states = State::pluck('state_name', 'id');
        
        $brands = Brand::pluck('brand_name', 'id');
        $categories = Category::pluck('category_name', 'id');
        $subcategories = Subcategory::pluck('name', 'id');

        //tolong paparkan create.blade.php
        return view('products.create', compact('brands' , 'subcategories', 'states', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateProductRequest $request)
    {
        $product=new Product;
        $product->product_name= $request->product_name;
        $product->product_desc= $request->product_desc;
        // $product->product_image = $request->product_image;
        $product->price = $request->price;
        $product->condition = $request->condition;
        $product->area_id = $request->area_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->brand_id = $request->brand_id;

        $product->user_id = auth()->id();

        //check ad file yg diupload or not
        if ($request->hasFile('product_image')) 
        {
            //$path = $request->product_image->store('images');
            $product->product_image = $request->product_image->hashName();    
        }

        $product->save();

        //selepas berjaya simpan set success message
        flash('New Product is successfully inserted into database.')->success();

        return redirect()->route('products.index');

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //dapatkan maklumat product
        $product = Product::find($id);

        $states = State::pluck('state_name', 'id');
        $brands = Brand::pluck('brand_name', 'id');
        $categories = Category::pluck('category_name', 'id');
        $subcategories = Subcategory::pluck('name', 'id');

        //get area based on previously selected state
        $areas = $this->getStateAreas($product->area->state_id);
        $subcategories = $this->getCategorySubcategories($product->subcategory->category_id);

        //tolong paparkan edit.blade.php
        return view('products.edit', compact('brands', 'subcategories', 'states', 'categories', 'product', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // $product->nama attribute = $request->product_image;
        $product->product_name= $request->product_name;
        $product->product_desc= $request->product_desc;
        $product->price = $request->price;
        $product->condition = $request->condition;
        $product->area_id = $request->area_id;
        $product->subcategory_id = $request->subcategory_id;
        $product->brand_id = $request->brand_id;

        //check ad file yg diupload or not
        if ($request->hasFile('product_image')) 
        {
            //$path = $request->product_image->store('images');
            $product->product_image = $request->product_image->hashName();     
        }

        $product->save();

        flash('Your product is successfully updated.');

        return redirect()->route('products.edit', $product->id);
        // return redirect()->route('products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getStateAreas($state_id){
        
        $areas = Area::whereStateId($state_id)->pluck('area_name', 'id');
        return $areas;
    }

    public function getCategorySubcategories($category_id)
    {
        $subcategories= Subcategory::whereCategoryId($category_id)->pluck('name', 'id');
        return $subcategories;
    }
}
