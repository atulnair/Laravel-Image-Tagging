<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Image;
use App\Models\Tag;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;



class TagsController extends Controller
{
    public function store_tag(Request $request,Image $image){
        if( $image->user_id == auth('api')->user()->id){
            $data = $request->json()->all();

            $validated = $request->validate([
                'label' => 'required',
                'dynamic_data' => 'required',
                'p1.x' => 'required|numeric',
                'p1.y' => 'required|numeric',
                'p2.x' => 'required|numeric',
                'p2.y' => 'required|numeric',
                
            ]);
    
            $tag = new Tag();
            $tag->label = $data['label'];
            $tag->data = $data['dynamic_data'];
            $tag->p1 = new Point((float) $data['p1']['x'],(float) $data['p1']['y']);
            $tag->p2 = new Point((float) $data['p2']['x'],(float) $data['p2']['y']);
            $tag->image_id = $image->id;
            $tag->save();
    
            return response()->json([
                "success" => true,
                "message" => "Successfully added tag",
                "data" => new ImageResource($image),
            ]);
        }
        else{
            return response()->json([
                "error"=> [
                    "message" => "User not authorized to access this image",
                ]], 403); 
        }
        

    }
}
