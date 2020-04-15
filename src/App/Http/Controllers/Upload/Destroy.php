<?php


namespace LaravelMerax\FileServer\App\Http\Controllers\Upload;


use App\Models\FileServer\Upload;
use Illuminate\Routing\Controller;

class Destroy extends Controller
{

    public function __invoke(Upload $upload)
    {
        $upload->delete();
    }
}
