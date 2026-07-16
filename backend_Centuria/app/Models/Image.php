<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Image extends Model
{
    protected $fillable = ["imageable_id", "imageable_type", "path"];

    public function imageable()
    {
        return $this->morphTo();
    }

    static function store($model, string $folder, $image)
    {

        // IF THE MODEL IS A USER AVATARE
        // IF THE MODEL IS A GROUP AVATARE
        if (!$model instanceof User && !$model instanceof Group) throw new \InvalidArgumentException('store() expects a User or Group model.');

        if ($model->image) {
            Storage::disk('public')->delete($model->image->path);
            $model->image()->delete();
        }

        
        $path = null;

        if ($image instanceof \Illuminate\Http\UploadedFile) {
            $path = $image->store($folder, 'public');
        } else {
            // IF THE MODEL IS A USER
            // TO SUPPORT GOOGLE AVATARS, WE NEED TO DOWNLOAD THE IMAGE AND STORE IT LOCALLY
            $path = $folder . '/' . Str::uuid() . '.jpg';
            Storage::disk('public')->put($path, $image);
        }

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
            if (!isset($image) or !$image instanceof \Illuminate\Http\UploadedFile) continue;
            $path = $image->store($folder, 'public');

            $storedImages[] = $model->images()->create([
                'path' => $path
            ]);
        }

        return $storedImages;
    }

    static function deleteMultiple($model)
    {
        $ids = $model->images()->pluck('id');
        foreach ($ids as $id) {
            Storage::disk('public')->delete(Image::find($id)->path);
            Image::find($id)->delete();
        }
    }

    static function deleteOne($model)
    {
        if ($model->image->path) Storage::disk('public')->delete($model->image->path);
    }

    static function deleteById($id)
    {
        $image = Image::find($id);
        if ($image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }
    }

    //use accessor to get the full URL of the image

    // This tells Laravel: "include url when converting to JSON"
    protected $appends = ['url'];

    // This is the magic method that creates the value
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->path);
    }
}
