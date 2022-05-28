<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Auth;

class Transactions extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'transactions';

    /**
     * Type constant
     *
     * @var array
     */
    const TYPE = [ 1 => 'expenses', 2 => 'income'];

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
            $userId = Auth::user()->id;
        }
        return $query->where('user_id', $userId);
    }

    /**
     * Get the Account record associated with the transaction.
     */
    public function account()
    {
        return $this->belongsTo('App\Accounts')->withDefault([
            'name' => '-',
        ]);
    }

    /**
     * Get the Category record associated with the transaction.
     */
    public function category()
    {
        return $this->belongsTo('App\Categories')->withDefault([
            'name' => '-',
        ]);
    }

    /**
     * Get the Category record associated with the transaction.
     */
    public function paymentmode()
    {
        return $this->belongsTo('App\PaymentModes')->withDefault([
            'name' => '-',
        ]);
    }

    /*
    *   $params array('aids'=> array(), 'cids' => array(), 'mods' => array(), 'type' => 1 or 2, 'from' => "YYYY/MM/DD", 'till' => "YYYY/MM/DD", 'search' => string)
    *   Or $params array('grand_total')
    */

    static public function totalAmount($params,$activeAccts = false)
    {
        if($activeAccts){
            $accountsAmt = DB::table('transactions')
            ->leftJoin('accounts', 'transactions.account_id', '=', 'accounts.id')
            ->where("transactions.user_id",Auth::user()->id)
            ->where("accounts.user_id",Auth::user()->id)
            ->where('status','1');
        }else{
            $accountsAmt = DB::table('transactions')->where("transactions.user_id",Auth::user()->id);
        }

        if(in_array('grand_total', $params)){
            $accountsAmt->select('transactions.account_id',DB::raw('SUM(transactions.amount) amount'));
            $filterFlag = false;
        }else{
            $accountsAmt->select(DB::raw('SUM(transactions.amount) amount'));
            $filterFlag = true;
        }

        $aidsKey = 'aids';
        $cidsKey = 'cids';
        $modsKey = 'mods';
        $typeKey = 'type';
        $fromKey = 'from';
        $tillKey = 'till';
        $searchKey = 'search';

        if(array_key_exists($aidsKey, $params) && count($params[$aidsKey]) > 0){
            $accountsAmt->whereIn('account_id', $params[$aidsKey]);
        }

        if(array_key_exists($cidsKey, $params) && count($params[$cidsKey]) > 0){
            $accountsAmt->whereIn('category_id', $params[$cidsKey]);
        }

        if(array_key_exists($modsKey, $params) && count($params[$modsKey]) > 0){
            $accountsAmt->whereIn('paymentmode_id', $params[$modsKey]);
        }

        if(array_key_exists($typeKey, $params) && in_array($params[$typeKey], [1,2])){
            $accountsAmt->where('type',$params[$typeKey]);
        }

        if(array_key_exists($fromKey, $params) && $params[$fromKey] != ''){
            $accountsAmt->where("date_time",'>=',$params[$fromKey]);
        }

        if(array_key_exists($tillKey, $params) && $params[$tillKey] != ''){
            $accountsAmt->where("date_time",'<', date('Y/m/d', strtotime($params[$tillKey]. ' + 1 days')));
        }

        if(array_key_exists($searchKey, $params) && $params[$searchKey] != ''){
            $accountsAmt->where(function ($query) use ($params,$searchKey) {
                $query->Where('title', 'like', '%'.$params[$searchKey].'%')
                      ->orWhere('transactions.amount', '=', $params[$searchKey]);
            });
        }

        if($filterFlag){
            $accountsAmt = $accountsAmt->first();
            return $accountsAmt->amount == null? 0 : $accountsAmt->amount;
        }else{
            $accountsAmt = $accountsAmt->groupBy('account_id')->get();
            $accountBalance = array();
            foreach ($accountsAmt as $accountData) {
                $accountBalance[$accountData->account_id] = $accountData->amount;
            }
            return $accountBalance;
        }
    }

    static function getTypeCode($type){
        if($key = array_search($type, self::TYPE)){
            return $key;
        }else{
            return false;
        }
    }
}
