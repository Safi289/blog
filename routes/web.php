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
use Illuminate\Http\Request;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', 'HomeController@index');

Route::resource('/product','ProductController');

Route::post('product/submitRating', 'ProductController@submitRating');

Route::view('/about', 'about');

Route::view('/about_us', 'about_us');

Route::view('/contact', 'contact');

Route::post('/submit-contact', function(Request $request){
	//dd($request);
	$data = array('name'=>$request->name, 'email'=>$request->email, 'message'=>$request->message);
	
	return view('/show-detail', ['data'=>$data]);
	//Route::view('/show-detail', ['data'=>$data]);
});

// Route::resource('showprofile', 'showProfile');
// Route::get('contact','contactController@submitRating');
// Route::post('product/submitRating','HomeController@submitRating');
// Auth::routes();
Route::get('/registration', function () {
    return view('registration');
});
// Route::get('/product/{id}', function($id){
//     return 'Product id is ' .$id;
// });

//cart start
Route::post('/add-to-cart', 'CartController@addToCart');
Route::get('/mycart', 'CartController@mycart');
Route::post('/update-cart', 'CartController@updateCart');
Route::any('/cartItemDelete/{temp_order_row_id}', 'CartController@cartItemDelete');
Route::any('/cartItemDeleteAll', 'CartController@cartItemDeleteAll');
//cart end

//checkout start
Route::get('/checkoutPage', 'CartController@checkoutItems');
Route::post('/confirm-order', 'CartController@confirmOrder');
Route::post('/checkout', 'CartController@checkout');
Route::post('/checkoutwithregistration', 'CartController@checkoutwithregistration');
//checkout end


//registration start
Route::get('/user-registration', 'Auth\CommonController@showRegistrationForm')->name('user.registration');
Route::post('/user-registration', 'Auth\CommonController@register')->name('user.registration.submit');

Route::get('/thankyou', 'CartController@thankyou');