<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Slider extends Model
{
    use HasFactory;

    protected $filliable =[
        'image', 'link'
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
          get:  fn ($image) => asset('/strorage/sliders/' . $image),
        );
    }
}
