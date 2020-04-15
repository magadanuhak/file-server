<?php


namespace LaravelMerax\FileServer\App\Http\Controllers\File;



use LaravelMerax\FileServer\App\Http\Resources\FilesCollection;
use LaravelMerax\FileServer\App\Models\FileServer\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class Index extends Controller
{
    public function __invoke(Request $request)
    {
        return new FilesCollection(
            File::visible()
                ->forUser($request->user())
                ->get()
        );
    }
}
