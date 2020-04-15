<?php


namespace LaravelMerax\FileServer\App\Services;

use Illuminate\Http\File;
use LaravelMerax\FileServer\App\Contracts\Attachable;
use App\Models\User\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File as BaseFile;

class Files
{
    private Attachable $attachable;
    private BaseFile $file;
    private string $disk;
    private array $extensions;
    private array $mimeTypes;
    private bool $optimize;
    private array $resize;

    public function __construct(Attachable $attachable)
    {
        $this->attachable = $attachable;
        $this->disk = config('filesystems.default');
        $this->extensions = [];
        $this->mimeTypes = [];
        $this->optimize = false;
        $this->resize = [];
    }

    public function inline()
    {
        return Storage::disk($this->disk)
            ->response($this->attachedFile());
    }

    public function download()
    {
        return Storage::disk($this->disk)
            ->download(
                $this->attachedFile(),
                Str::ascii($this->attachable->file->original_name)
            );
    }

    public function delete(): void
    {
        if ($this->attachable->file) {
            DB::transaction(function () {
                Storage::disk($this->disk)->delete($this->attachedFile());
                $this->attachable->file->delete();
            });
        }
    }

    public function attach(File $file, string $originalName, ?User $user): void
    {
        $this->file($file)
            ->validateFile()
            ->processImage()
            ->persistAttachedFile($originalName, $user);
    }

    public function upload(UploadedFile $file): void
    {
        $this->file($file)
            ->validateUploadedFile()
            ->processImage()
            ->persistUploadedFile();
    }

    public function optimize($optimize)
    {
        $this->optimize = $optimize;

        return $this;
    }

    public function resize(array $resize)
    {
        $this->resize = $resize;

        return $this;
    }

    public function disk(string $disk): Files
    {
        $this->disk = $disk;

        return $this;
    }

    public function extensions(array $extensions): Files
    {
        $this->extensions = $extensions;

        return $this;
    }

    public function mimeTypes(array $mimeTypes): Files
    {
        $this->mimeTypes = $mimeTypes;

        return $this;
    }

    private function file(BaseFile $file): Files
    {
        $this->file = $file;

        return $this;
    }

    private function validateFile(): Files
    {
        (new FileValidator(
            $this->file,
            $this->extensions,
            $this->mimeTypes
        ))->handle();

        return $this;
    }

    private function validateUploadedFile(): Files
    {
        (new UploadedFileValidator(
            $this->file,
            $this->extensions,
            $this->mimeTypes
        ))->handle();

        return $this;
    }

    private function processImage(): Files
    {
        (new ImageProcessor(
            $this->file,
            $this->optimize,
            $this->resize
        ))->handle();

        return $this;
    }

    private function persistAttachedFile(string $originalName, ?User $user): void
    {
        $this->attachable->file()->create([
            'original_name' => $originalName,
            'saved_name'    => $this->file->getBaseName(),
            'size'          => $this->file->getSize(),
            'mime_type'     => $this->file->getMimeType(),
            'author_id'     => optional($user)->id,
        ]);
    }

    private function persistUploadedFile(): void
    {
        DB::transaction(function () {
            $this->attachable->file()->create([
                'original_name' => $this->file->getClientOriginalName(),
                'saved_name'    => $this->file->hashName(),
                'size'          => $this->file->getSize(),
                'mime_type'     => $this->file->getMimeType(),
            ]);

            $this->file->store(
                $this->attachable->folder(),
                ['disk' => $this->disk]
            );
        });
    }

    private function attachedFile(): string
    {
        return $this->attachable->folder()
            .DIRECTORY_SEPARATOR
            .$this->attachable->file->saved_name;
    }
}
