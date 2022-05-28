<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Colors extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'colors';

    /**
     * Default color.
     *
     * @var string
     */
    const DEFAULT_COLOR = '#9c27b0';

    /**
     * Get the Accounts that owns the color.
     */
    public function accounts()
    {
        return $this->hasOne('App\Accounts');
    }

    /**
     * Get the Accounts that owns the color.
     */
    static public function toHexdec($clrCode)
    {
        $clrHxArr = str_split(str_replace('#', '', $clrCode), 2);
        $clrArr = array();
        foreach($clrHxArr as $clrHx){
            $clrArr[] = hexdec($clrHx);
        }
        return implode(',', $clrArr);
    }
}
