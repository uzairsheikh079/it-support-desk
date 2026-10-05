<?php

namespace App\Actions;

use App\Enums\TicketStatus;
use App\Jobs\LogTicketCreated;
use App\Models\Ticket;

class CreateTicketAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function handle(array $data): Ticket
    {
        $ticket = Ticket::query()->create([
            ...$data,
            'status' => TicketStatus::Open,
        ]);

        LogTicketCreated::dispatch(
            $ticket->public_id,
            $ticket->subject,
            $ticket->priority->value,
        );

        return $ticket;
    }
}
