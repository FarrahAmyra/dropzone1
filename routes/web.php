<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'ProductsController@index');

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::resource('states', 'StatesController');
Route::resource('areas', 'AreasController');
Route::resource('categories', 'CategoriesController');
Route::resource('subcategories', 'SubcategoriesController');
Route::resource('listingtypes', 'ListingtypesController');

// route for brands
Route::resource('brands', 'BrandsController');

// route for products
Route::get('my_products' , 'ProductsController@my_products')->name('my_products');

Route::get('products/areas/{state_id}', 'ProductsController@getStateAreas');
Route::get('products/subcategories/{category_id}', 'ProductsController@getCategorySubcategories');
Route::resource('products', 'ProductsController');


// Route for admin manage products
Route::group(['prefix' => 'admin', 'as' => 'admin.'], function () {
   
   // Route for managing product
	Route::get('products/areas/{state_id}', 'Admin\AdminProductsController@getStateAreas');
	Route::get('products/subcategories/{category_id}', 'Admin\AdminProductsController@getCategorySubcategories');
	Route::resource('products', 'Admin\AdminProductsController');

});



