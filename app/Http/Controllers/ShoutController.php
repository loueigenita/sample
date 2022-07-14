<?php

namespace App\Http\Controllers;
use App\Models\Shout;
use Illuminate\Http\Request;

class ShoutController extends Controller
{
    public function recent() {
        // $shout = Shout::orderBy('created_at','desc')->limit(100)->get();
        // return response()->json($shout);

        $shouts = Shout::where('user_id', auth()-> user()->id)
    ->orderBy('created_at','desc')
    ->limit(25)
    ->get();

    return response()->json($shouts);
    }
    public function show(Shout $shouts){
        return response()->json($shouts);
    }
}
