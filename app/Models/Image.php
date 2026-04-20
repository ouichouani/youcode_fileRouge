<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Image extends Model
{
    protected $fillable = ["imageable_id", "imageable_type", "path"];

    public function imageable()
    {
        return $this->morphTo();
    }

    static function store($model, string $folder, $image)
    {

        // if the model is a user
        if (!$model instanceof User) throw new \InvalidArgumentException('store() expects a User model.');

        if($model->image) {
            Storage::disk('public')->delete($model->image->path);
            $model->image()->delete();
        }

        $path = $image->store($folder, 'public');

        return $model->image()->create([
            'path' => $path
        ]);
    }

    static function storeMultiple($model, string $folder, $images)
    {
        if (!$model instanceof Post) {
            throw new \InvalidArgumentException('storeMultiple() expects a Post model.');
        }

        $images = is_array($images) ? $images : [$images];
        $storedImages = [];

        foreach ($images as $image) {
            if(!isset($image) or !$image instanceof \Illuminate\Http\UploadedFile) continue ;
            $path = $image->store($folder, 'public');

            $storedImages[] = $model->images()->create([
                'path' => $path
            ]);
        }

        return $storedImages;
    }
}
