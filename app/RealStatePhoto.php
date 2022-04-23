<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RealStatePhoto extends Model
{
    protected $table = 'real_state_photos';
    protected $fillable = ['photo', 'is_thumb'];

    public function realState(){
        return $this->belongsTo(RealState::class, 'real_state_id', 'id');
    }
}
