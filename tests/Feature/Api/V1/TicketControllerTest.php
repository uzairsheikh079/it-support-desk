<?php

namespace Tests\Feature\Api\V1;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        config(['supportdesk.api_key' => 'test-api-key']);
    }

    public function test_authorized_request_returns_paginated_tickets(): void
    {
        $ticket = Ticket::factory()->create();

        $response = $this->withHeader('X-API-Key', 'test-api-key')
            ->getJson(route('api.tickets.index'));

        $response
            ->assertOk()
            ->assertJsonPath('data.0.id', $ticket->public_id)
            ->assertJsonStructure([
                'data' => [[
                    'id',
                    'subject',
                    'description',
                    'requester' => ['name', 'email'],
                    'priority',
                    'status',
                    'assigned_to',
                    'resolved_at',
                    'created_at',
                    'updated_at',
                ]],
                'links',
                'meta',
            ]);
    }

    public function test_valid_payload_creates_ticket_and_returns_201(): void
    {
        $response = $this->withHeader('X-API-Key', 'test-api-key')
            ->postJson(route('api.tickets.store'), [
                ...$this->validPayload(),
                'status' => TicketStatus::Closed->value,
            ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.subject', 'Monitor flickers intermittently')
            ->assertJsonPath('data.status', TicketStatus::Open->value);
        $this->assertDatabaseHas('tickets', [
            'subject' => 'Monitor flickers intermittently',
            'status' => TicketStatus::Open->value,
        ]);
    }

    public function test_invalid_payload_returns_422_and_creates_nothing(): void
    {
        $response = $this->withHeader('X-API-Key', 'test-api-key')
            ->postJson(route('api.tickets.store'), [
                'subject' => '',
                'priority' => 'urgent',
            ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'subject',
                'description',
                'requester_name',
                'requester_email',
                'priority',
            ]);
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_authorized_request_updates_ticket(): void
    {
        $ticket = Ticket::factory()->open()->create();

        $response = $this->withHeader('X-API-Key', 'test-api-key')
            ->putJson(route('api.tickets.update', $ticket), [
                ...$this->payloadFor($ticket),
                'status' => TicketStatus::Resolved->value,
            ]);

        $response
            ->assertOk()
            ->assertJsonPath('data.status', TicketStatus::Resolved->value);
        $this->assertNotNull($ticket->refresh()->resolved_at);
    }

    public function test_authorized_request_deletes_ticket_and_returns_204(): void
    {
        $ticket = Ticket::factory()->create();

        $response = $this->withHeader('X-API-Key', 'test-api-key')
            ->deleteJson(route('api.tickets.destroy', $ticket));

        $response->assertNoContent();
        $this->assertModelMissing($ticket);
    }

    /**
     * @return array<string, string|null>
     */
    private function validPayload(): array
    {
        return [
            'subject' => 'Monitor flickers intermittently',
            'description' => 'The external monitor loses signal every few minutes.',
            'requester_name' => 'Jamie Smith',
            'requester_email' => 'jamie@example.com',
            'priority' => TicketPriority::Medium->value,
            'assigned_to' => null,
        ];
    }

    /**
     * @return array<string, string|null>
     */
    private function payloadFor(Ticket $ticket): array
    {
        return [
            'subject' => $ticket->subject,
            'description' => $ticket->description,
            'requester_name' => $ticket->requester_name,
            'requester_email' => $ticket->requester_email,
            'priority' => $ticket->priority->value,
            'assigned_to' => $ticket->assigned_to,
        ];
    }
}
