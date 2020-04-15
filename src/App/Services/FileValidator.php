<?php


namespace LaravelMerax\FileServer\App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use LaravelMerax\FileServer\App\Exceptions\File AS FileException;

class FileValidator
{
    protected $file;

    private array $extensions;
    private array $mimeTypes;

    public function __construct($file, array $extensions, array $mimeTypes)
    {
        $this->file = $file;
        $this->extensions = $extensions;
        $this->mimeTypes = $mimeTypes;
    }

    public function handle(): void
    {
        $this->validateExtension()
            ->validateMimeType();
    }

    private function validateExtension(): self
    {
        $valid = (new Collection($this->extensions));

        $extension = $this->file instanceof UploadedFile
            ? $this->file->getClientOriginalExtension()
            : $this->file->getExtension();

        if ($valid->isNotEmpty() && ! $valid->contains($extension)) {
            throw FileException::invalidExtension(
                $extension, implode(', ', $this->extensions)
            );
        }

        return $this;
    }

    private function validateMimeType(): FileValidator
    {
        $valid = (new Collection($this->mimeTypes));

        $mimeType = $this->file instanceof UploadedFile
            ? $this->file->getClientMimeType()
            : $this->file->getMimeType();

        if ($valid->isNotEmpty() && ! $valid->contains($mimeType)) {
            throw FileException::invalidMimeType(
                $mimeType, implode(', ', $this->mimeTypes),
                );
        }

        return $this;
    }
}
