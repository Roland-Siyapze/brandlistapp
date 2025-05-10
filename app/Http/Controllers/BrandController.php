<?php

namespace App\Http\Controllers;
use App\Model\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $country = $request->header('CF-IPCountry');
        if ($country) {
            $brands = Brand::where('country', $country)->orderBy('rating','desc')->get();
        } else {
            $default = 'US';
            $brands = Brand::where('country', $default)->orderBy('rating','desc')->get();
        }
        return response()->json($brands);
    }

    public function show($id)
    {
        return response()->json(Brand::findOrFail($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'brand_name'  => 'required|string',
            'brand_image' => 'required|url',
            'rating'      => 'required|integer',
            'country'     => 'nullable|string|size:2',
        ]);
        $brand = Brand::create($data);
        return response()->json($brand, 201);
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);
        $data = $request->only(['brand_name','brand_image','rating','country']);
        $brand->update($data);
        return response()->json($brand);
    }

    public function destroy($id)
    {
        Brand::destroy($id);
        return response()->json(null, 204);
    }
}
