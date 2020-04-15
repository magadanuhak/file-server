<?php


namespace LaravelMerax\FileServer\App\Http\Controllers\File;


use LaravelMerax\FileServer\App\Models\File;
use Illuminate\Routing\Controller;

class Link extends Controller
{
    public function __invoke(File $file)
    {
        return ['link' => $file->attachable->temporaryLink()];
    }
}
