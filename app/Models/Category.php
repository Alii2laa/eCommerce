<?php

namespace App\Models;

use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use Translatable;
    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = ['translations'];

    protected  $translatedAttributes = ['name'];

    protected $fillable = ['parent_id','slug','is_active'];

    protected $hidden = ['translations'];

    protected $casts =[
        'is_active' => 'boolean'
    ];

    public function scopeParent($query){
        return $query -> whereNull('parent_id');
    }
    public function scopeChild($query){
        return $query -> whereNotNull('parent_id');
    }

    public function getActive(){
        return $this->is_active == 1 ? 'مفعل' : 'غير مفعل';

    }
    public function MainCat(){
        return $this->belongsTo(self::class,'parent_id');
    }
}
