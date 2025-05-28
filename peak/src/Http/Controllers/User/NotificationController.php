<?php

namespace Qoraiche\Peak\Http\Controllers\User;

use Qoraiche\Peak\Http\Controllers\Controller;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;

class NotificationController extends Controller
{
    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $unreadNotifications = $request->user()->unreadNotifications;
        $unreadNotificationsCount = $unreadNotifications->count();
        $notifications = $request->type === 'unread' ? $request->user()->unreadNotifications : $request->user()->notifications;

        return Inertia::render('Dashboard/Account/Notifications', [
            'isUnread' => $request->has('type') && $request->type === 'unread',
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
            'unreadNotificationsCount' => $unreadNotificationsCount
        ]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return Application|RedirectResponse|Redirector|object
     */
    public function show(Request $request, string $id)
    {
        // Retrieve the notification by ID (ensure the user owns it)
        $notification = auth()->user()->notifications()->findOrFail($id);

        // Mark the notification as read
        $notification?->markAsRead();

        // Access the notification data (the 'type' and 'route' values)
        $notificationData = $notification?->data;
        $type = $notificationData['type'] ?? null;

        // Handle the notification action based on its type
        return match ($type) {
            'completed_export' => redirect($notificationData['link']),
            default => redirect()->route('dashboard.account.notifications.show')
        };
    }

    /**
     * @param Request $request
     * @return void
     */
    public function markAsRead(Request $request)
    {
        $request->user()->unreadNotifications->markAsRead();
    }
}
