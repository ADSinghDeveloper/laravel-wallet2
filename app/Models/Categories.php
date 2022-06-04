<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','color_id','icon_id','user_id'];

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

    /**
     * Get the Color record associated with the category.
     */
    public function color()
    {
        return $this->belongsTo('App\Models\Colors')->withDefault([
            'code' => Colors::first()->code,
        ]);
    }

    /**
     * Get the Icon record associated with the category.
     */
    public function icon()
    {
        return $this->belongsTo('App\Models\Icons')->withDefault([
            'code' => Icons::first()->code,
        ]);
    }

    /**
     * Get the Transaction that owns the Category.
     */
    public function transaction()
    {
        return $this->hasOne('App\Models\Transactions');
    }
}
