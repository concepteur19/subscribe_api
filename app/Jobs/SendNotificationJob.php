<?php

namespace App\Jobs;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use SebastianBergmann\Diff\Chunk;

class SendNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $notifications = Notification::where('notification_status', 'pending')
            // ->whereDate('sent_at', Carbon::now()->toDateString())
            ->where('sent_at', '<=', Carbon::now())
            ->get();

        foreach ($notifications as $notification) {
            try {
                // envoie du mail
                Mail::raw($notification->notification_content, function ($message) use ($notification) {
                    $message->to($notification->user->email)
                        ->subject('Subscription Reminder');
                });

                //  mise à jour du statut de la notif après l'envois du mail
                $notification->notification_status = 'sent';
                $notification->save();
            } catch (\Exception $e) {
                // Gestio de l'erreur (par exemple, journaliser l'erreur)
                Log::error('Error sending notification email: ' . $e->getMessage());
            }
        }
    }
}
