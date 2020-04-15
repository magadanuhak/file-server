<?php


namespace LaravelMerax\FileServer\App\Facades;


use Illuminate\Support\Facades\Facade;

class FileBrowser extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'file-browser';
    }
}
