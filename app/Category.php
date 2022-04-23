<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;
    protected $table = 'categories';
    protected $fillable = ['name', 'description', 'slug'];

    public function realStates()
    {

        return $this->belongsToMany(RealState::class, 'real_state_categories', 'category_id', 'real_state_id');
    }
}
