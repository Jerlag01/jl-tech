<?php

namespace App\Http\Controllers\Support;

use App\Actions\PostTicketMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTicketMessageRequest;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;

class TicketMessageController extends Controller
{
    public function store(StoreTicketMessageRequest $request, Ticket $ticket, PostTicketMessage $postMessage): RedirectResponse
    {
        $this->authorize('reply', $ticket);

        $postMessage->handle($ticket, $request->user(), $request->validated('message'), $request->file('attachments', []));

        return redirect(support_route('tickets.show', $ticket))->withFragment('reply')->with('status', __('support.flash.replied'));
    }
}
