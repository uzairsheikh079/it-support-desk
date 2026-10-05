<div>
    <!-- Breathing in, I calm body and mind. Breathing out, I smile. - Thich Nhat Hanh -->
</div>
@props(['priority'])

@php
    $classes = match ($priority) {
        \App\Enums\TicketPriority::Low => 'text-slate-300',
        \App\Enums\TicketPriority::Medium => 'text-cyan-200',
        \App\Enums\TicketPriority::High => 'text-orange-200',
        \App\Enums\TicketPriority::Critical => 'text-rose-300',
    };
@endphp

<span {{ $attributes->merge(['class' => "text-xs font-semibold uppercase tracking-wider {$classes}"]) }}>
    {{ $priority->label() }}
</span>
