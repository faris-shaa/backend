<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Event;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Storage;
class CitiesController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('city_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $city = City::OrderBy('id','DESC')->get();
        return view('admin.cities.index', compact('city'));
    }

    public function create()
    {
        abort_if(Gate::denies('city_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.cities.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'bail|required',
            'image' => 'bail|required|image|mimes:jpeg,png,jpg,gif|max:3048',
        ]);
        $data = $request->all();

          $imageData = $request->input('cropped_image');

        // Extract base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $type)) {
            $imageData = substr($imageData, strpos($imageData, ',') + 1);
            $type = strtolower($type[1]); // jpg, png, gif

            if (!in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                return response()->json(['message' => 'Invalid image type.'], 400);
            }

            $imageData = base64_decode($imageData);

            if ($imageData === false) {
                return response()->json(['message' => 'Base64 decode failed.'], 400);
            }

            // Generate a unique filename
            $filename = 'image_' . time() . '.' . $type;
            
            // Store the image
            Storage::disk('root_public')->put('images/upload/' . $filename, $imageData);

            //return response()->json(['path' => 'storage/images/upload/test/' . $filename], 200);
        }
        $data['image'] = $filename  ;
        
        /*if ($request->hasFile('image')) {
            $data['image'] = (new AppHelper)->saveImage($request);
        }*/
        City::create( $data);
        return redirect()->route('cities.index')->withStatus(__('city has added successfully.'));
    }

    public function edit(City $city)
    {
        abort_if(Gate::denies('city_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.cities.edit', compact( 'city'));
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'name' => 'bail|required',
        ]);
        $data = $request->all();
        if ($request->hasFile('image')) {
            $request->validate([    
                'image' => 'bail|required|image|mimes:jpeg,png,jpg,gif|max:3048',
            ]);
            //(new AppHelper)->deleteFile($city->image);
            $data['image'] = (new AppHelper)->saveImage($request);
        }  
        
        City::find($id)->update( $data);
        return redirect()->route('cities.index')->withStatus(__('city has updated successfully.'));
    }

    public function destroy($id)
    {
        try {
            City::find($id)->update(['status' => 0]);
            $event = Event::where("city_id", $id)->update(['is_deleted' => 1, 'event_status' => 'Deleted']);
            
            return true;
        } catch (Throwable $th) {
            return response('Data is Connected with other Data', 400);
        }
    }

}
