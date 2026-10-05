<?php

namespace Tests\Feature;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Jobs\LogTicketCreated;
use App\Models\Ticket;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class TicketControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('tickets.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_and_filter_tickets(): void
    {
        $user = User::factory()->create();
        $matchingTicket = Ticket::factory()->critical()->open()->create([
            'subject' => 'VPN access unavailable',
        ]);
        Ticket::factory()->resolved()->create([
            'subject' => 'Printer repaired',
        ]);

        $response = $this->actingAs($user)->get(route('tickets.index', [
            'search' => 'VPN',
            'status' => TicketStatus::Open->value,
            'priority' => TicketPriority::Critical->value,
        ]));

        $response
            ->assertOk()
            ->assertSee($matchingTicket->subject)
            ->assertDontSee('Printer repaired');
    }

    public function test_valid_payload_creates_ticket_and_redirects_to_detail(): void
    {
        $user = User::factory()->create();
        Queue::fake([LogTicketCreated::class]);

        $response = $this->actingAs($user)->post(route('tickets.store'), $this->validPayload());

        $ticket = Ticket::query()->sole();
        $response
            ->assertRedirect(route('tickets.show', $ticket))
            ->assertSessionHas('success', 'Ticket created successfully.');
        $this->assertDatabaseHas('tickets', [
            'subject' => 'Cannot connect to office Wi-Fi',
            'status' => TicketStatus::Open->value,
        ]);
        Queue::assertPushed(
            LogTicketCreated::class,
            fn (LogTicketCreated $job): bool => $job->publicId === $ticket->public_id,
        );
    }

    public function test_empty_payload_returns_required_field_errors(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('tickets.create'))
            ->post(route('tickets.store'), []);

        $response
            ->assertRedirect(route('tickets.create'))
            ->assertSessionHasErrors([
                'subject',
                'description',
                'requester_name',
                'requester_email',
                'priority',
            ]);
        $this->assertDatabaseCount('tickets', 0);
    }

    public function test_resolving_ticket_sets_resolution_time(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->open()->create();

        $response = $this->actingAs($user)->put(route('tickets.update', $ticket), [
            ...$this->payloadFor($ticket),
            'status' => TicketStatus::Resolved->value,
        ]);

        $response->assertRedirect(route('tickets.show', $ticket));
        $this->assertSame(TicketStatus::Resolved, $ticket->refresh()->status);
        $this->assertNotNull($ticket->resolved_at);
    }

    public function test_ticket_detail_escapes_requester_content(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create([
            'description' => "Printer error <script>alert('xss')</script>",
        ]);

        $response = $this->actingAs($user)->get(route('tickets.show', $ticket));

        $response
            ->assertOk()
            ->assertSee('&lt;script&gt;', false)
            ->assertDontSee("<script>alert('xss')</script>", false);
    }

    public function test_authenticated_user_can_delete_ticket(): void
    {
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create();

        $response = $this->actingAs($user)->delete(route('tickets.destroy', $ticket));

        $response
            ->assertRedirect(route('tickets.index'))
            ->assertSessionHas('success', 'Ticket deleted successfully.');
        $this->assertModelMissing($ticket);
    }

    public function test_database_seeder_creates_demo_tickets_with_public_ids(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->assertDatabaseCount('tickets', 12);
        $this->assertSame(12, Ticket::query()->whereNotNull('public_id')->count());
    }

    /**
     * @return array<string, string|null>
     */
    private function validPayload(): array
    {
        return [
            'subject' => 'Cannot connect to office Wi-Fi',
            'description' => 'The laptop disconnects every few minutes in the meeting room.',
            'requester_name' => 'Alex Morgan',
            'requester_email' => 'alex@example.com',
            'priority' => TicketPriority::High->value,
            'assigned_to' => 'Support Team',
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
