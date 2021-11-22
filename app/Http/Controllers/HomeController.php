<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\User;

use App\Models\Product;

use App\Models\Card;

use App\Models\Order;

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

               $user=auth()->user();

               $count=card::where('phone',$user->phone)->count();

            return view ('user.home', compact('data','count'));  
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

    public function showcard ()
    {
        $user=auth()->user();
        $card=card::where('phone',$user->phone)->get();

            $count=card::where('phone',$user->phone)->count();

        return view ('user.showcard',compact('count','card'));
    }
    public function deletecard($id)
    {
        $data=card::find($id);
        
        $data->delete();
        
        return redirect()->back()->with('message','Product Deleted Successfully');
    }

    public function confirmorder(Request $request)
    {
            $user=auth()->user();
            $name=$user->name;
            $phone=$user->phone;
            $adress=$user->adress;

            foreach($request->productname as $key=>$productname)

            {
                $order=new order;
                
                $order->product_name=$request->productname[$key];

                $order->price=$request->price[$key];

                $order->quantity=$request->quantity[$key];

                $order->name=$name;

                $order->phone=$phone;

                $order->adress=$adress;

                $order->save();


            }

            return redirect()->back();
    }
}