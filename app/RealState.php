<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealState extends Model
{
    protected $table = "real_states";
    protected $fillable = ['user_id', 'title', 'description', 'content', 'price', 'bathrooms', 'bedrooms', 'property_area', 'total_property_area', 'slug'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'real_state_categories', 'real_state_id', 'category_id');
    }
    public function realStatePhotos()
    {
        return $this->hasMany(RealStatePhoto::class, 'real_state_id', 'id');
    }
}
