<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TicketResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->public_id,
            'subject' => $this->subject,
            'description' => $this->description,
            'requester' => [
                'name' => $this->requester_name,
                'email' => $this->requester_email,
            ],
            'priority' => $this->priority->value,
            'status' => $this->status->value,
            'assigned_to' => $this->assigned_to,
            'resolved_at' => $this->resolved_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
