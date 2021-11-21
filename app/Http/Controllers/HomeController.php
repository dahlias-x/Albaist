<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Models\Product;

use App\Models\Card;

class HomeController extends Controller
{


    public function redirect()
    {
        $usertype=Auth::user()->usertype;

        if ($usertype=='1')
        {
            return view ('admin.home');
        }
        else
        {
               $data = product::paginate(3);

            return view ('user.home', compact('data'));  
        }
    }



    public function index ()
    {
        if (Auth::id())
        {
            return redirect('redirect');
        }
        else
        {
            $data = product::paginate(3);

            return view ('user.home', compact('data'));
        }
        
    }

    public function search (Request $request)
    {
        $search=$request->search;

        if($search=='')
        {
            $data = product::paginate(3);

            return view ('user.home', compact('data')); 
        }

        $data=product::where('title','Like','%'.$search.'%')->get();


        return view('user.home',compact('data'));
    }

    public function addcard(Request $request, $id)
    {

    if(Auth::id())
    {

        $user=auth()->user();

        $product=product::find($id);

        $card= new card;
        
        $card->name=$user->name;

        $card->phone=$user->phone;

        $card->adress=$user->adress;

        $card->product_title=$product->title;

        $card->price=$product->price;

        $card->quantity= $request->quantity;

        $card->save();

        return redirect()->back()->with('message','Product Added Successfully');
    }

    else
    {
        return redirect('login');
    }

}

}