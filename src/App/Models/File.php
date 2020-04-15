<?php


namespace LaravelMerax\FileServer\App\Models;


use LaravelMerax\FileServer\App\Services\FileBrowser;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    protected $fillable = ['original_name', 'saved_name', 'size', 'mime_type', 'created_by'];

    public function attachable(): MorphTo
    {
        return $this->morphTo();
    }

    public function temporaryLink()
    {
        return url()->temporarySignedRoute(
            'v1.files.share',
            now()->addSeconds(config('files.linkExpiration')),
            ['file' => $this->id]
        );
    }

    public function type(): string
    {
        return FileBrowser::folder($this->attachable_type);
    }

    public function path()
    {
        return Storage::path(
            $this->attachable->folder()
            .DIRECTORY_SEPARATOR
            .$this->saved_name
        );
    }

    public function scopeForUser($query, $user): void
    {
        $query->whereAuthorId($user->id);
    }

    public function scopeVisible($query)
    {
        $query->hasMorph(
            'attachable', FileBrowser::models()->toArray()
        );
    }
}
