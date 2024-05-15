<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanType extends Model
{
    use HasFactory;

    protected $fillable = ['default_subscription_id', 'type', 'amount'];

    // Relations
    public function defaultSubscription()
    {
        return $this->belongsTo(DefaultSubscription::class);
    }
}
