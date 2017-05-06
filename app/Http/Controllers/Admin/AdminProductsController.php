<?php
// namespace kna tukar ikut nama folder
namespace App\Http\Controllers\Admin; 
// kena use App\Http\
use App\Http\Controllers\Controller;

// tambah middleware sendiri untk cek role user
use App\Http\Middleware\CheckUserRole;
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
use Alert;

class AdminProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){

        // check dah login ke belum
        $this->middleware('auth');

        $this->middleware('check_user_role:admin');

    }

    public function index(Request $request)
    {
        //kalau nk guna relationship kalau dah ada data
        $products = Product::with('brand', 'user', 'area', 'subcategory');
        // dd($products);
        //additional searching
        if(!empty($request->search_anything)){
            $search_anything = $request->search_anything;

            $products = $products->where(function($query) use ($search_anything){
                $query->orWhere('product_name', 'Like', '%'.$search_anything. '%')
                ->orWhere('product_desc', 'Like', '%' .$search_anything. '%');
            });
        }

        //search by state
        if(!empty($request->search_state)){
            $search_state= $request->search_state;

            $products = Product::whereHas('area', function ($query) use ($search_state){
                $query->where('state_id', $search_state);
            });
        }

        //search by area
        if(!empty($request->search_area)){
            $search_area= $request->search_area;

            $products = Product::where(function ($query) use ($search_area){
                $query->where('area_id', $search_area);
            });
        }


        //search by category
        if(!empty($request->search_category)){
            $search_category= $request->search_category;

            $products = Product::whereHas('subcategory', function ($query) use ($search_category){
                $query->where('category_id', $search_category);
            });
        }

        //search by brand
        if(!empty($request->search_brand)){
            $search_brand= $request->search_brand;

            // $products = Product::whereHas('brand', function ($query) use ($search_brand){
            //     $query->where('brand_id', $search_brand);
            $products = Product::where(function ($query) use ($search_brand){
                $query->where('brand_id', $search_brand);
            });
        }
        
        //sort by latest products
        $products = $products->orderBy('id', 'desc');

        //pagination
        $products = $products->paginate(3);

        //load additional data for searching
        $brands = Brand::pluck('brand_name', 'id');
        $categories = Category::pluck('category_name', 'id');
        $states = State::pluck('state_name', 'id');

        // dd($products);
        return view('admin.products.index', compact('products', 'brands', 'categories', 'states')); //compaq == with('products') hk abe li ajar tuh
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
        return view('admin.products.create', compact('brands' , 'subcategories', 'states', 'categories'));
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
            $path = $request->product_image->store('public/uploads');
            $product->product_image = $request->product_image->hashName();    
        }

        $product->save();

        //selepas berjaya simpan set success message
        // flash('New Product is successfully inserted into database.')->success();

        //sweetalert message
        Alert::success('Your Product is successfully stored.');

        return redirect()->route('admin.products.index');

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
        return view('admin.products.edit', compact('brands', 'subcategories', 'states', 'categories', 'product', 'areas'));
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

        // flash('Your product is successfully updated.');

        // sweetalert message
        Alert::success('Your Product is successfully updated.');

        return redirect()->route('admin.products.edit', $product->id);
        // return redirect()->route('admin.products.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product=Product::findOrFail($id);
        $product->delete();

        //flash('Product is successfully deleted.');
        // sweetalert message
        Alert::success('Your Product is successfully deleted.');

        return redirect()->route('admin.products.index');
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
