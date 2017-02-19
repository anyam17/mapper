<?php

namespace App\Http\Controllers;

use App\Car;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    /*public function __construct()
    {
        $this->middleware('auth');
    }
*/
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function retJsonData(Request $request) 
    {
        $data = Car::all();

        return response()->json($data);
    }

    /*
    |--------------------------------------------------------------------------
    | Function that stores and displays the cars 
    |-------------------------------------------------------------------------- */
    public function storeDisplayCar(Request $request) {

        /* Validating the input form parameters*/
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            /*'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',*/
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->all()]);
        }

        /* Getting the image from the ajax request*/
        /*if ($request->hasFile('image')) {
            $img = $request->file('image');
            $basePath = public_path().'/images/';
            $image_filename = md5('image'.date('y-m-d h:i:s')) . '.' . $img->getClientOriginalExtension();
            $img->move($basePath, $image_filename);
        }
        else
            dd('No image was found');*/

        /* Creating and storing a new Car object*/
        $cars = new Car;
        $cars->name = $request->input('name');
        $cars->latitude = $request->input('latitude');
        $cars->longitude = $request->input('longitude');
        /*$cars->image = $image_filename;*/

        $cars->save();    
 
        $data = Car::all();

            return response()->json($data);
    }
}
