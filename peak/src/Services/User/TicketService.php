<?php

namespace Qoraiche\Peak\Services\User;

use Illuminate\Http\Request;
use Qoraiche\Peak\Http\Filters\User\LicensesSearchFilter;
use Qoraiche\Peak\Http\Filters\User\SupportTicketSearchFilter;
use Qoraiche\Peak\Models\Ticket;
use Qoraiche\Peak\Models\User;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TicketService
{
    /**
     * @param Request $request
     * @param $perPage
     * @param int|null $userId
     * @param string $searchQueryParam
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getSearchablePaginatedTickets(Request $request,
                                                          $perPage,
                                                  int     $userId = null,
                                                  string  $searchQueryParam = 'search')
    {
        $limitPagination = (int)$request->input('limit', $perPage);

        if (!in_array($limitPagination, [25, 50, 100])) {
            $limitPagination = $perPage;
        }

        $query = QueryBuilder::for(Ticket::class)->allowedFilters([
            Ticket::USER_ID_COLUMN_NAME,
            Ticket::STATUS_COLUMN_NAME,
            Ticket::PRIORITY_COLUMN_NAME,
            AllowedFilter::custom($searchQueryParam, new SupportTicketSearchFilter)
        ])->allowedSorts([
            Ticket::ID_COLUMN_NAME,
            Ticket::SUBJECT_COLUMN_NAME,
            Ticket::PRIORITY_COLUMN_NAME,
            Ticket::CREATED_AT
        ])->withCount('comments');

        if ($userId) {
            $query->where('user_id', $userId);
        }

        return $query->orderBy('updated_at', 'desc')
            ->paginate($limitPagination);
    }

    /**
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTicketsByUser(User $user)
    {
        return $user->tickets()->orderBy('updated_at', 'desc')->latest()->get();
    }
}
