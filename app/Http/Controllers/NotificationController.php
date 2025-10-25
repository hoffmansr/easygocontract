<?php

namespace App\Http\Controllers;
use App\Models\Contrat;


use Illuminate\Http\Request;

class NotificationController extends Controller
{
    function index()
    {
        $notifications = auth()->user()->notifications; // ou Contractant::find(...)->notifications
        $unreadCount = auth()->user()->unreadNotifications->count();
        return view('layouts.app1', compact('notifications', 'unreadCount'));
    }

    public function show($id)
{
    $contrat = Contrat::findOrFail($id);

    // Marquer la notification comme lue si elle existe dans la requête
    if (request()->has('notification_id')) {
        $notification = auth()->user()->notifications()->find(request('notification_id'));
        if ($notification) {
            $notification->markAsRead();
        }
    }

    return view('contrats.show', compact('contrat'));
}
}
