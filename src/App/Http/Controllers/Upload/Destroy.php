<?php


namespace LaravelMerax\FileServer\App\Http\Controllers\Upload;


use LaravelMerax\FileServer\App\Models\Upload;
use Illuminate\Routing\Controller;

class Destroy extends Controller
{

    public function __invoke(Upload $upload)
    {
        $upload->delete();
    }
}
