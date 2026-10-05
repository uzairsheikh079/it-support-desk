<div>
    <!-- Very little is needed to make a happy life. - Marcus Aurelius -->
</div>
@props(['status'])

@php
    $classes = match ($status) {
        \App\Enums\TicketStatus::Open => 'border-sky-400/20 bg-sky-400/10 text-sky-200',
        \App\Enums\TicketStatus::InProgress => 'border-amber-400/20 bg-amber-400/10 text-amber-200',
        \App\Enums\TicketStatus::Resolved => 'border-emerald-400/20 bg-emerald-400/10 text-emerald-200',
        \App\Enums\TicketStatus::Closed => 'border-slate-400/20 bg-slate-400/10 text-slate-300',
    };
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center rounded-full border px-2.5 py-1 text-xs font-semibold {$classes}"]) }}>
    {{ $status->label() }}
</span>
