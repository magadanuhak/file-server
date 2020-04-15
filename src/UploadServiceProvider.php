<?php


namespace LaravelMerax\FileServer;


use LaravelMerax\FileServer\App\Models\Upload;

class UploadServiceProvider extends FileServiceProvider
{
    public $register = [
        'uploads' => [
            'model' => Upload::class,
            'order' => 100,
        ],
    ];
}