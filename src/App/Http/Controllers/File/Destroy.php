<?php


namespace LaravelMerax\FileServer\App\Http\Controllers\File;


use LaravelMerax\FileServer\App\Models\FileServer\File;
use Illuminate\Routing\Controller;

class Destroy extends Controller
{

    public function __invoke(File $file)
    {
        $file->attachable->delete();
    }
}
