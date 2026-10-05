<?php

namespace Database\Factories;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(TicketStatus::cases());

        return [
            'public_id' => (string) Str::uuid(),
            'subject' => fake()->randomElement([
                'Laptop cannot connect to Wi-Fi',
                'Printer queue is blocked',
                'New employee account setup',
                'VPN connection fails after update',
                'Email client repeatedly requests password',
            ]),
            'description' => fake()->paragraphs(2, true),
            'requester_name' => fake()->name(),
            'requester_email' => fake()->safeEmail(),
            'priority' => fake()->randomElement(TicketPriority::cases()),
            'status' => $status,
            'assigned_to' => fake()->optional(0.7)->name(),
            'resolved_at' => $status->isResolved() ? fake()->dateTimeBetween('-5 days') : null,
        ];
    }

    public function open(): static
    {
        return $this->state(fn (): array => [
            'status' => TicketStatus::Open,
            'resolved_at' => null,
        ]);
    }

    public function resolved(): static
    {
        return $this->state(fn (): array => [
            'status' => TicketStatus::Resolved,
            'resolved_at' => now(),
        ]);
    }

    public function critical(): static
    {
        return $this->state(fn (): array => [
            'priority' => TicketPriority::Critical,
        ]);
    }
}
