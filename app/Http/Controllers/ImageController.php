<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Requests\ImageRequest;

class ImageController extends Controller
{
    public function index(){
        $images = Image::published()->latest()->paginate(15);


        return view('images.index', compact('images'));
    }

    public function show(Image $image)
    {
        return view('images.show', compact('image'));
    }

    public function create(){
        return view('images.create');
    }

    public function store(ImageRequest $request){
        Image::create($request->getData());
        return to_route('images.index')->with('message', "Image has been uploaded successfully");
    }
}