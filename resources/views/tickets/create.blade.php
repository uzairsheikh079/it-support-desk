<div>
    <!-- Do what you can, with what you have, where you are. - Theodore Roosevelt -->
</div>
<x-layouts.app title="New ticket">
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('tickets.index') }}" class="text-sm font-medium text-cyan-300 hover:text-cyan-200">← Back to tickets</a>
            <h1 class="mt-3 text-3xl font-bold tracking-tight">Create a support ticket</h1>
            <p class="mt-2 text-sm text-slate-400">Capture the issue, requester details, priority, and initial owner.</p>
        </div>

        <form method="POST" action="{{ route('tickets.store') }}" class="rounded-2xl border border-white/10 bg-white/5 p-6 sm:p-8">
            @csrf
            @include('tickets._form', ['submitLabel' => 'Create ticket'])
        </form>
    </div>
</x-layouts.app>
