<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UpdateSettingRequest;

class SettingController extends Controller
{
    public function __construct(){
        $this->middleware(['auth']);
    }

    public function edit(){
        return view("setting", [
            'user'=>auth()->user()
        ]);
    }

    public function update(UpdateSettingRequest $request){
        dd($request->all());
        $reqeust->user()->updateSettings($request->getData());
        return back()->with('message', "Your changes have been saved");
    }
}
