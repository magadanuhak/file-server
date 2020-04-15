<?php


namespace LaravelMerax\FileServer\App\Traits;

use App\Models\File;
use App\Models\User\User;
use App\Services\FileServer\Files;
use Illuminate\Http\File as IlluminateFile;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait HasFile
{
    public function file(): Relation
    {
        return $this->morphOne(File::class, 'attachable');
    }

    public function inline(): StreamedResponse
    {
        return (new Files($this))->inline();
    }

    public function download(): StreamedResponse
    {
        return (new Files($this))->download();
    }

    public function temporaryLink(): string
    {
        return $this->file->temporaryLink();
    }

    public function attach(IlluminateFile $file, string $originalName, ?User $user): void
    {
        (new Files($this))->attach($file, $originalName, $user);
    }

    public function upload(UploadedFile $file): void
    {
        (new Files($this))
            ->mimeTypes($this->mimeTypes())
            ->extensions($this->extensions())
            ->optimize($this->optimizeImages())
            ->resize($this->resizeImages())
            ->upload($file);
    }

    public function ensureFolderExists()
    {
        $folder = $this->folder();

        if (! Storage::has($folder)) {
            Storage::makeDirectory($folder);
        }

        return $this;
    }

    public function folder(): string
    {
        if (App::environment('testing')) {
            return config('files.paths.testing');
        }

        return property_exists($this, 'folder')
            ? $this->folder
            : config('files.paths.files');
    }

    public function mimeTypes(): array
    {
        return property_exists($this, 'mimeTypes')
            ? $this->mimeTypes
            : [];
    }

    public function extensions(): array
    {
        return property_exists($this, 'extensions')
            ? $this->extensions
            : [];
    }

    public function resizeImages(): array
    {
        return property_exists($this, 'resizeImages')
            ? $this->resizeImages
            : [];
    }

    public function optimizeImages(): bool
    {
        return property_exists($this, 'optimizeImages')
            ? $this->optimizeImages
            : false;
    }

    protected static function bootHasFile(): void
    {
        self::deleting(fn ($model) => (new Files($model))->delete());
    }
}