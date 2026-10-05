<div>
    <!-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca -->
</div>
<x-layouts.app :title="$ticket->subject">
    <div class="mx-auto max-w-5xl">
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
            <div>
                <a href="{{ route('tickets.index') }}" class="text-sm font-medium text-cyan-300 hover:text-cyan-200">← Back to tickets</a>
                <h1 class="mt-3 text-3xl font-bold tracking-tight">{{ $ticket->subject }}</h1>
                <p class="mt-2 font-mono text-xs text-slate-500">{{ $ticket->public_id }}</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('tickets.edit', $ticket) }}" class="rounded-xl bg-cyan-400 px-4 py-2.5 text-sm font-bold text-slate-950 transition hover:bg-cyan-300">Edit ticket</a>
                <form method="POST" action="{{ route('tickets.destroy', $ticket) }}" onsubmit="return confirm('Delete this ticket?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-xl border border-rose-400/20 px-4 py-2.5 text-sm font-semibold text-rose-300 transition hover:bg-rose-400/10">Delete</button>
                </form>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-[1fr_300px]">
            <section class="rounded-2xl border border-white/10 bg-white/5 p-6 sm:p-8">
                <h2 class="text-sm font-semibold uppercase tracking-wider text-slate-500">Issue description</h2>
                <p class="mt-5 whitespace-pre-line text-base leading-8 text-slate-200">{{ $ticket->description }}</p>
            </section>

            <aside class="grid content-start gap-4">
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <div class="flex items-center justify-between gap-4">
                        <x-status-badge :status="$ticket->status" />
                        <x-priority-badge :priority="$ticket->priority" />
                    </div>
                </div>

                <dl class="grid gap-5 rounded-2xl border border-white/10 bg-white/5 p-5 text-sm">
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-slate-500">Requester</dt>
                        <dd class="mt-1 font-medium text-white">{{ $ticket->requester_name }}</dd>
                        <dd class="mt-1 text-slate-400">{{ $ticket->requester_email }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-slate-500">Assigned to</dt>
                        <dd class="mt-1 font-medium text-white">{{ $ticket->assigned_to ?: 'Unassigned' }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs uppercase tracking-wider text-slate-500">Created</dt>
                        <dd class="mt-1 text-slate-300">{{ $ticket->created_at->format('M j, Y · H:i') }}</dd>
                    </div>
                    @if ($ticket->resolved_at)
                        <div>
                            <dt class="text-xs uppercase tracking-wider text-slate-500">Resolved</dt>
                            <dd class="mt-1 text-slate-300">{{ $ticket->resolved_at->format('M j, Y · H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </aside>
        </div>
    </div>
</x-layouts.app>
