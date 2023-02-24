<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Product;
use App\Models\Cart;

class HomeController extends Controller
{

    public function index()
    {
        $product = Product::paginate(9);
        return view('home.userpage', compact('product'));
    }
    public function redirect()
    {
        $usertype = Auth::user()->usertype;

        if ($usertype == '1') {
            return view('admin.home');
        } else {
            $product = Product::paginate(9);
            return view('home.userpage', compact('product'));
        }
    }

    public function product_details($id)
    {
        if (Auth::id()) {
            $product = product::find($id);
            return view('home.product_details', compact('product'));
        }
        else{
            return redirect('login');
        }
    }
    public function add_cart(Request $request, $id)
    {
        if (Auth::id()) {

            // return redirect()->back();
            //from user
            $user = Auth::user();
            $product = product::find($id);
            $cart = new cart;
            $cart->name = $user->name;
            $cart->email = $user->email;
            $cart->phone = $user->phone;
            $cart->address = $user->address;
            $cart->user_id = $user->id;
            $cart->quantity = 1;
            //from product
            $cart->product_title = $product->title;
            if ($product->discount_price != null) {
                $cart->price = $product->discount_price;
            } else {
                $cart->price = $product->price;
            }
            $cart->image = $product->image;
            $cart->Product_id = $product->id;

            $cart->save();
            return redirect()->back();

            // dd($product);


        } else {

            return redirect('login');
        }
    }
}
