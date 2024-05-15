<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DefaultSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'logo',
    ];

    // Relations
    public function planTypes()
    {
        return $this->hasMany(PlanType::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
