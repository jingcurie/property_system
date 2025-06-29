<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentalApplication extends Model
{
    protected $fillable = ['applicant_name', 'phone', 'start_date', 'end_date', 'message', 'status'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
}
