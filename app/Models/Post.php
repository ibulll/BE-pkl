<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Post extends Model
{
    use HasFactory;

    protected $fillable =[
        'image','title', 'slug' ,  'category_id', 'user_id', 'content'
    ];

    public function category()
    {
        return $this->belongsTo(category::class);
    }

    public function user()
    {
        return $this->belongsTo(user::class);
    }

    public function views()
    {
        return $this->hasMany(PostView::class);
    }

    protected function image(): Attribute
    {
        return Attribute::make(
          get:  fn ($image) => asset('/strorage/posts/' . $image),
        );
    }
}

