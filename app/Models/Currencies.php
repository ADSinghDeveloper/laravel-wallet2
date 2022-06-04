<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currencies extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'currencies';

    /**
     * Get the Accounts that owns the currency.
     */
    public function accounts()
    {
        return $this->hasOne('App\Models\Accounts');
    }
}
