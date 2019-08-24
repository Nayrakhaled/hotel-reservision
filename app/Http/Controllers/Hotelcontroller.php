<?php

namespace App\Http\Controllers;
use DB;
use Illuminate\Http\Request;



class Hotelcontroller extends Controller
{
    public function registeration(){
        return view('register');
    }


    public function register(Request $request){
        $name = $request->input('name');
        $telephone = $request->input('telephone');
        $district = $request->input('district');
        $city = $request->input('city');
        $country = $request->input('country');
        //what is the clicks?********************************************
        $clicks = $request->input('clicks');
        $data=array("name"=>$name,"telephone"=>$telephone,"district"=>$district,"city"=>$city,"country"=>$country,"clicks"=>$clicks);
        DB::table('hotel')->insert($data);
        echo "Record inserted successfully.<br/>";

    }
}