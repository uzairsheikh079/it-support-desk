<div>
    <!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
</div>
<x-layouts.app title="Tickets">
    <div class="flex flex-col gap-6">
        <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
            <div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-300">Operations overview</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight sm:text-4xl">IT support tickets</h1>
                <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-400">Track requests from intake through resolution with clear ownership and priority.</p>
            </div>
            <a href="{{ route('tickets.create') }}" class="inline-flex items-center justify-center rounded-xl bg-cyan-400 px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-cyan-300">
                New ticket
            </a>
        </div>

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($statuses as $status)
                <div class="rounded-2xl border border-white/10 bg-white/5 p-5">
                    <x-status-badge :status="$status" />
                    <p class="mt-4 text-3xl font-bold">{{ $statusCounts[$status->value] ?? 0 }}</p>
                    <p class="mt-1 text-xs uppercase tracking-wider text-slate-500">{{ $status->label() }} tickets</p>
                </div>
            @endforeach
        </div>

        <form method="GET" action="{{ route('tickets.index') }}" class="grid gap-3 rounded-2xl border border-white/10 bg-white/5 p-4 md:grid-cols-[1fr_180px_180px_auto]">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Search subject or requester"
                class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-2.5 text-sm text-white outline-none placeholder:text-slate-600 focus:border-cyan-400/60">
            <select name="status" class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-2.5 text-sm text-white outline-none focus:border-cyan-400/60">
                <option value="">All statuses</option>
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
            <select name="priority" class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-2.5 text-sm text-white outline-none focus:border-cyan-400/60">
                <option value="">All priorities</option>
                @foreach ($priorities as $priority)
                    <option value="{{ $priority->value }}" @selected(request('priority') === $priority->value)>{{ $priority->label() }}</option>
                @endforeach
            </select>
            <button type="submit" class="rounded-xl border border-white/10 px-4 py-2.5 text-sm font-semibold transition hover:bg-white/5">Filter</button>
        </form>

        <div class="overflow-hidden rounded-2xl border border-white/10 bg-white/5">
            <div class="overflow-x-auto">
                <table class="w-full min-w-[820px] text-left">
                    <thead class="border-b border-white/10 bg-white/[0.03] text-xs uppercase tracking-wider text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-medium">Ticket</th>
                            <th class="px-5 py-4 font-medium">Requester</th>
                            <th class="px-5 py-4 font-medium">Priority</th>
                            <th class="px-5 py-4 font-medium">Status</th>
                            <th class="px-5 py-4 font-medium">Owner</th>
                            <th class="px-5 py-4 font-medium">Created</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/5">
                        @forelse ($tickets as $ticket)
                            <tr class="transition hover:bg-white/[0.03]">
                                <td class="px-5 py-4">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="font-semibold text-white hover:text-cyan-300">{{ $ticket->subject }}</a>
                                    <p class="mt-1 font-mono text-xs text-slate-600">{{ str($ticket->public_id)->limit(12) }}</p>
                                </td>
                                <td class="px-5 py-4">
                                    <p class="text-sm text-slate-200">{{ $ticket->requester_name }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ $ticket->requester_email }}</p>
                                </td>
                                <td class="px-5 py-4"><x-priority-badge :priority="$ticket->priority" /></td>
                                <td class="px-5 py-4"><x-status-badge :status="$ticket->status" /></td>
                                <td class="px-5 py-4 text-sm text-slate-400">{{ $ticket->assigned_to ?: 'Unassigned' }}</td>
                                <td class="px-5 py-4 text-sm text-slate-400">{{ $ticket->created_at->diffForHumans() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-14 text-center text-sm text-slate-400">No tickets match the selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{ $tickets->links() }}
    </div>
</x-layouts.app>
