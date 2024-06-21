<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        // 'user_id',
        'subscription_id',
        'notif_id',
        // 'notification_channel',
        'amount',
        'paid_at',
        'status'
        // 'notification_status',
        // 'notification_content',
    ];

    // public function user()
    // {
    //     return $this->belongsTo(User::class, 'user_id');
    // }

    // public function subscription()
    // {
    //     return $this->belongsTo(Subscription::class, 'subscription_id');
    // }
}
