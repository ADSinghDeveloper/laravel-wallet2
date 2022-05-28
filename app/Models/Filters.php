<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Filters extends Model
{    
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'filters';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','value','user_id'];

    /**
     * Scope a query to only include accounts of a given user.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param Integer $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUser($query, $userId = null)
    {
        if($userId == null){
            $userId = \Auth::user()->id;
        }
        return $query->where('user_id', $userId);
    }

}
