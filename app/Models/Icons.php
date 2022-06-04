<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Icons extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'icons';

    /**
     * Default color.
     *
     * @var string
     */
    const DEFAULT_ICON = 'account_balance_wallet';

    /**
     * Get the Accounts that owns the color.
     */
    public function categories()
    {
        return $this->hasOne('App\Models\Categories');
    }
}
