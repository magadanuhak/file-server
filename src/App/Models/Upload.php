<?php


namespace LaravelMerax\FileServer\App\Models;


use LaravelMerax\FileServer\App\Contracts\Attachable;
use LaravelMerax\FileServer\App\Traits\HasFile;
use LaravelMerax\FileServer\App\Services\FileServer\UploadManager;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model implements Attachable
{
    use HasFile;

    protected $optimizeImages = true;

    public static function store(array $files)
    {
        return (new UploadManager($files))->handle();
    }
}
