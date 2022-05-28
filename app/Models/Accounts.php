<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;
class Accounts extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'accounts';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','color_id','currency_id','status','user_id'];

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
     * Get the Color record associated with the account.
     */
    public function color()
    {
        return $this->belongsTo('App\Colors')->withDefault([
            'code' => Colors::first()->code,
        ]);
    }

    /**
     * Get the Currency record associated with the account.
     */
    public function currency()
    {
        return $this->belongsTo('App\Currencies')->withDefault([
            'code' => Icons::first()->code,
        ]);
    }

    /**
     * Get the Transaction that owns the Account.
     */
    public function transaction()
    {
        return $this->hasOne('App\Transactions');
    }

    static function currencyAmount(){
        $currAmt = DB::table('accounts')
            ->leftJoin('currencies', 'currencies.id', '=', 'accounts.currency_id')
            ->select('currency_id','code','currencies.name as name',DB::raw('SUM(amount) amount'))
            ->where("user_id",Auth::user()->id)
            ->where('status','1')
            ->groupBy('currency_id')->get();

        return $currAmt;
    }
}
