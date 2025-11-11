<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Facades\Log;

class NewCourrierNotification extends Notification implements ShouldBroadcast
{
    public $courrier;

    public function __construct($courrier)
    {
        $this->courrier = $courrier;
        Log::info('NewCourrierNotification created', ['courrier_id' => $courrier->id]);
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function toDatabase($notifiable)
    {
        Log::info('Saving notification to database');
        return [
            'message' => 'Nouveau courrier reçu: ' . $this->courrier->reference_arrive,
            'courrier_id' => $this->courrier->id,
            'reference' => $this->courrier->reference_arrive,
        ];
    }

    public function toBroadcast($notifiable)
    {
        Log::info('Broadcasting notification', [
            'user_id' => $notifiable->id,
            'courrier_id' => $this->courrier->id
        ]);
        
        return new BroadcastMessage([
            'message' => 'Nouveau courrier reçu: ' . $this->courrier->reference_arrive,
            'courrier_id' => $this->courrier->id,
            'reference' => $this->courrier->reference_arrive,
        ]);
    }

    public function broadcastOn()
    {
        return new \Illuminate\Broadcasting\PrivateChannel('App.Models.User.' . $this->courrier->user_id);
    }
} 