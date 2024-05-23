<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $table = 'subscriptions';
    protected $fillable = [
        'user_id',
        'defaultSub_id',
        'service_name',
        'logo',
        'amount',
        'start_on',
        'end_on',
        'payment_method',
        'cycle',
        'plan_type',
        'reminder',
    ];

    //  relations
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function defaultSubscription()
    {
        return $this->belongsTo(DefaultSubscription::class);
    }


    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
