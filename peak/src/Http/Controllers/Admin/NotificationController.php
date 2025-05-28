<?php

namespace Qoraiche\Peak\Http\Controllers\Admin;

use Qoraiche\Peak\Http\Controllers\Controller;
use Illuminate\Foundation\Application;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Inertia\Inertia;
use Inertia\Response;
use Qoraiche\Peak\Traits\HandlesPermissions;

class NotificationController extends Controller
{
    use HandlesPermissions;

    /**
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->authorizeAction('view', null, 'notifications');

        $unreadNotifications = $request->user()->unreadNotifications;
        $unreadNotificationsCount = $unreadNotifications->count();
        $notifications = $request->type === 'unread' ? $request->user()->unreadNotifications : $request->user()->notifications;

        return Inertia::render('Admin/Notifications/Index', [
            'isUnread' => $request->has('type') && $request->type === 'unread',
            'notifications' => $notifications,
            'unreadNotifications' => $unreadNotifications,
            'unreadNotificationsCount' => $unreadNotificationsCount
        ]);
    }

    /**
     * @param Request $request
     * @param string $id
     * @return Application|RedirectResponse|Redirector
     */
    public function show(Request $request, string $id)
    {
        $this->authorizeAction('view', null, 'notifications');

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
            default => redirect()->route('admin.notifications.index'),
        };
    }

    /**
     * @param Request $request
     * @return void
     */
    public function markAsRead(Request $request)
    {
        $this->authorizeAction('view', null, 'notifications');

        $request->user()->unreadNotifications->markAsRead();
    }
}
