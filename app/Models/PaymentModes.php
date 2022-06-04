<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentModes extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'payment_modes';

    /**
     * Get the Transaction that owns the Payment Mode.
     */
    public function transaction()
    {
        return $this->hasOne('App\Models\Transactions');
    }
}
