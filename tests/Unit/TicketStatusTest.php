<?php

namespace Tests\Unit;

use App\Enums\TicketStatus;
use PHPUnit\Framework\TestCase;

class TicketStatusTest extends TestCase
{
    public function test_resolved_and_closed_statuses_are_terminal(): void
    {
        $this->assertTrue(TicketStatus::Resolved->isResolved());
        $this->assertTrue(TicketStatus::Closed->isResolved());
        $this->assertFalse(TicketStatus::Open->isResolved());
        $this->assertFalse(TicketStatus::InProgress->isResolved());
    }
}
