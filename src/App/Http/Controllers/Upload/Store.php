<?php


namespace LaravelMerax\FileServer\App\Http\Controllers\Upload;


use App\Http\Controllers\Controller;
use App\Models\FileServer\Upload;
use Illuminate\Http\Request;

class Store extends Controller
{
    public function __invoke(Request $request, Upload $upload)
    {
        $request->merge(['author_id' => \user()->id]);

        return $upload->store($request->allFiles());
    }
}
