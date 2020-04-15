<?php


namespace LaravelMerax\FileServer\App\Http\Controllers\File;



use LaravelMerax\FileServer\App\Http\Resources\Collection;
use LaravelMerax\FileServer\App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class Index extends Controller
{
    public function __invoke(Request $request)
    {
        return new Collection(
            File::visible()
                ->forUser($request->user())
                ->get()
        );
    }
}
