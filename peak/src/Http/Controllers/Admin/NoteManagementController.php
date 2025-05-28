<?php

namespace Qoraiche\Peak\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Qoraiche\Peak\Http\Controllers\Controller;
use Qoraiche\Peak\Models\User;
use Qoraiche\Peak\Models\UserNote;
use Qoraiche\Peak\Traits\HandlesPermissions;

class NoteManagementController extends Controller
{
    use HandlesPermissions;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, User $user)
    {
        $this->authorizeAction('create', $user, 'users-notes');

        Validator::make($request->all(), [
            'comment' => 'required|max:1000|min:3'
        ])->validate();

        $user->userNotes()->create([
            UserNote::NOTE_COLUMN_NAME => $request->get('comment'),
            UserNote::POSTER_ID_COLUMN_NAME => auth()->id(),
            UserNote::USER_ID_COLUMN_NAME => $user->id,
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserNote $note)
    {
        $this->authorizeAction('edit', $note, 'users-notes');

        Validator::make($request->all(), [
            'comment' => 'required|max:1000|min:3'
        ])->validate();

        $note->update([
            'note' => $request->get('comment')
        ]);
    }

    /**
     * @param UserNote $note
     * @return void
     */
    public function destroy(UserNote $note)
    {
        $this->authorizeAction('delete', $note, 'users-notes');

        $note->delete();
    }
}
