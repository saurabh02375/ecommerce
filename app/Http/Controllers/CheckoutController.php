<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Addtocart;
use App\Models\Checkout;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Psy\VersionUpdater\Checker;

class CheckoutController extends Controller
{

    public function deletecheckoutproduct($id)
    {
        $delete     = Checkout::where('id', $id)->delete();
        return redirect()->back();
    }
    public function tocheckout(Request $request)
    {



        $products    = DB::table('products')->join('addtocarts', 'products.id', '=', 'addtocarts.product_id')
            ->select('name', 'price', 'addtocarts.*', 'image')->get();

        $checkouts = Checkout::all();
        $subtotal = $checkouts->sum('finalprice');
        $gst = ($subtotal * 18) / 100;
        $finalamount = $subtotal + $gst;
        return view('user.frontend.tocheckout', compact('checkouts', 'subtotal', 'finalamount', 'gst'));
    }

    public function proceed(Request $request)
    {



        if (!empty($request->quantity)) {
            foreach ($request->quantity as $product_id => $quantity) {
                $product = Product::where('id', $product_id)->first();
                // $user_id = Checkout::where('user_id', Auth::id())->first();
                $user_id        =   Auth::user()->id;
                $final_price = $product->price * $quantity;
                $checksave = new Checkout();
                $checksave->finalprice  = $final_price;
                $checksave->product_id  = $product_id;
                $checksave->quantity    = $quantity;
                $checksave->product_name = $product->name;
                $checksave->price = $product->price;
                $checksave->user_id    =    $user_id;
                // dd($request);
                $checksave->save();
            }
            return redirect()->route('tocheckout');
        }
        $products = $request->input('product_id');
        $product = Addtocart::where('id', $products)->first();
    }

    public function placeorder(Request $request)
    {
        $request->validate([
            'fname' => 'required',
            'lname' => 'required',
            'email' => 'required',

        ]);
        $address    = new Address();
        $address->fname    = $request->input('fname');
        $address->lname    = $request->input('lname');
        $address->country    = $request->input('country');
        $address->address    = $request->input('address');
        $address->postcode    = $request->input('postcode');
        $address->city    = $request->input('city');
        $address->email    = $request->input('email');
        $address->phone    = $request->input('phone');
        $address->save();


        $delete     = Addtocart::where('user_id', Auth::user()->id)->delete();

        $checkouts  = Checkout::all();

        foreach ($checkouts as $checkout) {
            $order = new Order();
            $order->user_id   = Auth::user()->id;
            $order->product_id  = $checkout->product_id;
            $order->baseprice  = $checkout->price;
            $order->quantity  = $checkout->quantity;
            $order->save();
        }


        $delete     = Checkout::where('user_id', Auth::user()->id)->delete();
        return redirect()->route('home');
    }
}
