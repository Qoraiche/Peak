<?php

namespace Qoraiche\Peak\Services\Admin;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Laravel\Cashier\Cashier;
use Qoraiche\Peak\Facades\Card;
use Qoraiche\Peak\Facades\Widget;
use Qoraiche\Peak\Models\Changelog;
use Qoraiche\Peak\Models\Doc;
use Qoraiche\Peak\Models\Post;
use Qoraiche\Peak\Models\Report;
use Qoraiche\Peak\Models\Roadmap;
use Qoraiche\Peak\Models\Subscription;
use Qoraiche\Peak\Models\Ticket;
use Qoraiche\Peak\Models\Transaction;
use Qoraiche\Peak\Models\User;

class AdminOverviewService
{
    /**
     * @return array
     */
    public function getCardAndWidgetsData(): array
    {
        $cards = Card::all();
        $widgets = Widget::all('default');

        $mergedData = Arr::collapse(array_merge(
            Arr::pluck($cards, 'data'),
            Arr::pluck($widgets, 'data')
        ));

        return $mergedData;
    }

    /**
     * @return string
     */
    public function getTodayEarnings(): string
    {
        // Get the current currency code (from session, request, or default)
        $currencyCode = config('cashier.currency');

        // Define the start and end of the current day
        $startOfDay = now()->startOfDay();
        $endOfDay = now()->endOfDay();

        // Query the transactions table for today's totals in cents
        $totalCents = DB::table('transactions')
            ->whereIn('status', [Transaction::SUCCESSFUL_STATUS, Transaction::COMPLETED_STATUS])
            ->whereBetween('created_at', [$startOfDay, $endOfDay]) // Match today's date range
            ->sum('total'); // Assuming 'total' stores the amount in cents

        // Format the total amount in the selected currency
        return Cashier::formatAmount($totalCents, $currencyCode);
    }

    /**
     * @return int
     */
    public function getActiveUsersCount(): int
    {
        return User::query()->verified()->count();
    }

    /**
     * @return int
     */
    public function getActiveSubscribersCount(): int
    {
        return User::whereHas('subscriptions', function ($query) {
            $query->active();
        })->count();
    }

    /**
     * @return int
     */
    public function getTotalBlogPosts(): int
    {
        return Post::query()->published()->count();
    }

    /**
     * @return int
     */
    public function getTotalSupportTickets(): int
    {
        return Ticket::query()->count();
    }

    /**
     * @return int
     */
    public function getTotalChangelogs(): int
    {
        return Changelog::query()->published()->count();
    }

    /**
     * @return int
     */
    public function getTotalRoadmapItems(): int
    {
        return Roadmap::query()->published()->count();
    }

    /**
     * @return int
     */
    public function getTotalDocsPages(): int
    {
        return Doc::query()
            ->published()
            ->count();
    }

    public function getRecentSubscribers(int $limit = 5)
    {
        return Subscription::query()->with('user')
            ->active()
            ->latest()
            ->limit($limit)
            ->get();
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getRecentRegisteredUsers(int $limit = 5)
    {
        return User::query()->with('roles')
            ->verified()
            ->latest()
            ->limit($limit)
            ->get();
    }

    public function getRecentBlogPosts(int $limit = 5)
    {
        return Post::query()
            ->published()
            ->latest()
            ->limit($limit)
            ->get()
            ->map(function ($blogPost) {
                $locale = current_session_locale();

                return [
                    'id' => $blogPost->id,
                    'title' => $blogPost->getTranslation('title', $locale),
                    'slug' => $blogPost->getTranslation('slug', $locale),
                    'user' => $blogPost->user,
                    'locale_image' => $blogPost->locale_image,
                    'created_at' => $blogPost->created_at,
                    'published_at' => $blogPost->published_at
                ];
            });
    }

    /**
     * @param int $limit
     * @return Collection
     */
    public function getRecentReports(int $limit = 5)
    {
        return Report::query()->with('user')->latest()->limit($limit)->get();
    }

    /**
     * @param int $limit
     * @return Collection
     */
    public function getRecentSupportTickets(int $limit = 5)
    {
        return Ticket::query()->with('user')->withCount('comments')->latest()->limit($limit)->get();
    }

    /**
     * @param int $limit
     * @return Collection
     */
    public function getRecentRoadmaps(int $limit = 5)
    {
        return Roadmap::query()->with('user')->published()->latest()->limit($limit)->get()->map(function (Roadmap $roadmap) {
            $locale = current_session_locale();

            return [
                'id' => $roadmap->id,
                'slug' => $roadmap->getTranslation('slug', $locale),
                'title' => $roadmap->getTranslation('title', $locale),
                'body' => $roadmap->getTranslation('body', $locale),
                'user' => $roadmap->user,
                'upvoters_count' => $roadmap->upvoters_count,
                'published_at' => $roadmap->published_at,
                'created_at' => $roadmap->created_at
            ];
        });
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getRecentChangelogs(int $limit = 5)
    {
        return Changelog::query()->with('user')->published()->latest()->limit($limit)->get()->map(function (Changelog $changelog) {
            $locale = current_session_locale();

            return [
                'id' => $changelog->id,
                'title' => $changelog->getTranslation('title', $locale),
                'body' => $changelog->getTranslation('body', $locale),
                'user' => $changelog->user,
                'published_at' => $changelog->published_at,
                'created_at' => $changelog->created_at,
            ];
        });
    }

    /**
     * @param int $limit
     * @return mixed
     */
    public function getRecentDocumentationPages(int $limit = 5)
    {
        return Doc::query()->with('user')->published()->latest()->limit($limit)->get()->map(function (Doc $doc) {
            $locale = current_session_locale();

            return [
                'id' => $doc->id,
                'title' => $doc->getTranslation('title', $locale),
                'slug' => $doc->getTranslation('slug', $locale),
                'body' => $doc->getTranslation('body', $locale),
                'user' => $doc->user,
                'published_at' => $doc->published_at,
                'created_at' => $doc->created_at
            ];
        });
    }
}
