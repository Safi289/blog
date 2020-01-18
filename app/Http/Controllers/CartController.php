<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;
use Session;
use App\Models\product;

class CartController extends Controller
{

	public function __construct() {
       
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */

    // public function index(){
    //     if (Auth::guest()) {
    //         return view('checkout_Without_login');
    //     }
    //     return view('checkout2');
    // }

    public function addToCart(Request $request)
	{
		if (! session()->has('tracking_number'))
		{
			session()->put('tracking_number', session::getId());
		}
		$tracking_number = session::get('tracking_number');

		$data['product_info'] = DB::table('products')->where('product_row_id', $request->product_row_id)->first();

		$order_exists = DB::table('temp_orders')->where('product_row_id', $data['product_info']->product_row_id)->where('tracking_number', $tracking_number)->first();

		if($order_exists)
		{
			$product_qty = $order_exists->product_qty + $request->qty;
			DB::table('temp_orders')->where('temp_order_row_id', $order_exists->temp_order_row_id)->update([
				'product_qty' => $product_qty,
				'product_total_price' => ($order_exists->product_price * $product_qty)]);
		}
		else
		{
			DB::table('temp_orders')->insert([
			'product_row_id' =>      $data['product_info']->product_row_id,
			'tracking_number' =>     $tracking_number, 
			'product_price' =>       $data['product_info']->product_price,
			'product_qty' =>         $request->qty,
			'product_total_price' => $data['product_info']->product_price*$request->qty,
			'created_at' =>          date('Y-m-d H:i:s'),
			]);
		}
		return redirect('/mycart');
	}

	public function mycart()
	{
		if (! session()->has('tracking_number'))
		{
			session()->put('tracking_number', session::getId());
		}
		$tracking_number = session()->get('tracking_number');
		$data['temp_orders'] = DB::table('temp_orders As To')
							   ->join('products As p', 'To.product_row_id', '=', 'p.product_row_id')
                               ->where('To.tracking_number', $tracking_number)
                               ->select('p.*', 'To.*')
                               ->get();

        $data['total_price'] = DB::table('temp_orders')
                               ->where('tracking_number', $tracking_number)
                               ->sum('product_total_price');

        return view('cart', ['data'=>$data]);
	}

}



