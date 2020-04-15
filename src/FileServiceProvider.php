<?php


namespace LaravelMerax\FileServer;


use Illuminate\Support\ServiceProvider;
use LaravelMerax\FileServer\App\Facades\FileBrowser;

class FileServiceProvider extends ServiceProvider
{
    public $register = [];

    public function boot()
    {
        FileBrowser::register($this->register);
    }
}