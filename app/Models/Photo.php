<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    protected $fillable = [
        "external_id",
        "album_id",
        "title",
        "image_url",
        "image_url_thumb",
    ];

    public function fillApi($photo)
    {
        $this->fill([
            "external_id" => $photo->id,
            "album_id" => $photo->albumId,
            "title" => $photo->title,
            "image_url" => $photo->url,
            "image_url_thumb" => $photo->thumbnailUrl,
        ]);
    }
}
