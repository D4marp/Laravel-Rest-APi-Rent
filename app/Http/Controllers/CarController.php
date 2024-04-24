<?php

namespace App\Http\Controllers;

use App\Models\Car;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function index()
    {
        $cars = Car::all();

        return response()->json([
            'success' => true,
            'message' => 'List of all cars',
            'data' => $cars
        ], 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'brand'=> 'required|string',
            'model'=> 'required|string',
            'year'=> 'required|string',
            'color'=> 'required|string',
            'price'=> 'required|integer',
            'image'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation for image
        ]);

        if ($validator->fails()) {
            return response()->json([
                'succes' => false,
                'massage' => 'Validation Error',
                'errors' => $validator->errors()
            ],422);
        }

        $car = new Car();
        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->year = $request->year;
        $car->price = $request->price;
        $car->color = $request->color;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path ('/images/cars');
            $image->move($destinationPath, $name);
            $car->image = $name;
        }
        $car->save();

        return response()->json([
            'success' => true,
            'message' => 'Car created successfully',
            'data' => $car
        ], 201);
       
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
       $car = Car::find($id);

       if (!$car) {
        return response()->json([
            'success' => false,
            'message' => 'Car not found',
        ], 404);
       }
       //Menambahkan Url Gambar Mobil
       $car->image =url('/images/cars/'.$car->image);

       return response()->json([
        'success' => true,
        'message' => 'Car found',
        'data' => $car
       ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Car not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'brand' => 'required|string',
            'model' => 'required|string',
            'year' => 'required|integer',
            'color' => 'required|string',
            'price' => 'required|integer',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation for image
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $car->brand = $request->brand;
        $car->model = $request->model;
        $car->year = $request->year;
        $car->color = $request->color;
        $car->price = $request->price;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = public_path('/images/cars');
            $image->move($destinationPath, $name);
            $car->image = $name;
        }

        $car->save();

        return response()->json([
            'success' => true,
            'message' => 'Car updated successfully',
            'data' => $car
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $car = Car::find($id);

        if (!$car) {
            return response()->json([
                'success' => false,
                'message' => 'Car not found'
            ], 404);
        }

        $car->delete();

        return response()->json([
            'success' => true,
            'message' => 'Car deleted successfully'
        ], 200);
    }
}
