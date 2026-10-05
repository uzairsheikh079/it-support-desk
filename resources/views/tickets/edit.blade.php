<div>
    <!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->
</div>
<x-layouts.app title="Edit ticket">
    <div class="mx-auto max-w-3xl">
        <div class="mb-6">
            <a href="{{ route('tickets.show', $ticket) }}" class="text-sm font-medium text-cyan-300 hover:text-cyan-200">← Back to ticket</a>
            <h1 class="mt-3 text-3xl font-bold tracking-tight">Update ticket</h1>
            <p class="mt-2 font-mono text-xs text-slate-500">{{ $ticket->public_id }}</p>
        </div>

        <form method="POST" action="{{ route('tickets.update', $ticket) }}" class="rounded-2xl border border-white/10 bg-white/5 p-6 sm:p-8">
            @csrf
            @method('PUT')
            @include('tickets._form', ['submitLabel' => 'Save changes'])
        </form>
    </div>
</x-layouts.app>
