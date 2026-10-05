<?php

namespace App\Actions;

use App\Enums\TicketStatus;
use App\Models\Ticket;

class UpdateTicketAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(Ticket $ticket, array $data): Ticket
    {
        $status = TicketStatus::from($data['status']);

        $ticket->update([
            ...$data,
            'resolved_at' => $status->isResolved() ? ($ticket->resolved_at ?? now()) : null,
        ]);

        return $ticket->refresh();
    }
}
