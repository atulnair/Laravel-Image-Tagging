<?php

namespace App\Http\Controllers;

use App\Http\Resources\ImageResource;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;



class ImagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return ImageResource::collection(auth('api')->user()->images);
    }

    public function show_all(){ 

        return ImageResource::collection(Image::all()->where('type','public'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($files = $request->file('file')) {
            
            $imageName = Storage::disk('public')->put('',$request->file);

            $document = new Image();
            $document->title = $imageName;
            $document->user_id = auth('api')->user()->id;
            $document->type = 'private';

            $document->height = \Intervention\Image\Facades\Image::make($request->file('file'))->height();
            $document->width = \Intervention\Image\Facades\Image::make($request->file('file'))->width();
            $document->save();
                
            return response()->json([
                "success" => true,
                "message" => "File successfully uploaded",
                "data" => $document
            ]);

        }

    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function show(Image $image)
    {
        if($image->type == 'public'){
            return new ImageResource($image);
        }
        if($image->user_id == auth('api')->user()->id){
            return new ImageResource($image);
        }
        return response()->json([
            "error"=> [
                "message" => "User not authorized to access this image",
            ]], 403); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function edit(Image $image)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Image $image)
    {
        if($image->user_id == auth('api')->user()->id){

            if( $request->filled('type') ){

                if( $request-> type == 'private' || $request-> type == 'public'){
                    $image->type = $request->type;
                    $image->save();
                }
                else{
                    return response()->json([
                        "error"=> [
                            "message" => "Invalid request type",
                        ]], 200); 
                }
            };

            if( $request->filled('category') ){

                $image->category = $request->category;
                $image->save();
            };
            return new ImageResource($image);
        }

        return response()->json([
            "error"=> [
                "message" => "User not authorized to access this image",
            ]], 403); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Image  $image
     * @return \Illuminate\Http\Response
     */
    public function destroy(Image $image)
    {
        //
    }
}
