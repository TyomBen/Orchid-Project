<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Image extends Model
{
    use AsSource;
    protected $fillable = [
        'path',
    ];
}
