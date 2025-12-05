<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'ip',

        // NEW Facebook fields
        'facebook_lead_id',
        'form_id',
        'ad_id',
        'page_id',
        'data'
    ];
}
