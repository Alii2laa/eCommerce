<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use Translatable;
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    protected  $translatedAttributes = ['name'];

    protected $fillable = ['slug'];

    protected $hidden = ['translations'];
}
