<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class LogTicketCreated implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    /** @var list<int> */
    public array $backoff = [10, 30, 60];

    public function __construct(
        public string $publicId,
        public string $subject,
        public string $priority,
    ) {
        $this->onQueue('support');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Support ticket created.', [
            'ticket_id' => $this->publicId,
            'subject' => $this->subject,
            'priority' => $this->priority,
        ]);
    }
}
