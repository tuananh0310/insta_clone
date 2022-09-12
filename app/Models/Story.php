<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;
use Intervention\Image\Facades\Image;

class Story extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'image', 'time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function storeStory()
    {
        $imagePath = request('image')->store('/story', 'public');

        $image = Image::make(public_path("storage/{$imagePath}"));
        $image->resize(500, 751);
        $image->save();
        auth()->user()->stories()->create([
            'image' => URL::to('/storage/' . $imagePath)
        ]);
    }
}
