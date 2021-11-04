<?php

namespace App\Models;

use Grimzy\LaravelMysqlSpatial\Eloquent\SpatialTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory, SpatialTrait;

    protected $spatialFields = [
        'p1',
        'p2'
    ];

    public function image(){
        $this->belongsTo(Image::class);
    }
}
