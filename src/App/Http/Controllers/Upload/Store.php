<?php


namespace LaravelMerax\FileServer\App\Http\Controllers\Upload;


use App\Http\Controllers\Controller;
use LaravelMerax\FileServer\App\Models\Upload;
use Illuminate\Http\Request;

class Store extends Controller
{
    public function __invoke(Request $request, Upload $upload)
    {
        return $upload->store($request->allFiles());
    }
}
