<div>
    <!-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh -->
</div>
<div class="grid gap-6">
    <div class="grid gap-6 md:grid-cols-2">
        <label class="grid gap-2 md:col-span-2">
            <span class="text-sm font-medium text-slate-200">Subject</span>
            <input type="text" name="subject" value="{{ old('subject', $ticket->subject ?? '') }}" required maxlength="120"
                class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition focus:border-cyan-400/60 focus:ring-4 focus:ring-cyan-400/10">
            @error('subject')<span class="text-sm text-rose-300">{{ $message }}</span>@enderror
        </label>

        <label class="grid gap-2">
            <span class="text-sm font-medium text-slate-200">Requester name</span>
            <input type="text" name="requester_name" value="{{ old('requester_name', $ticket->requester_name ?? '') }}" required maxlength="100"
                class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition focus:border-cyan-400/60 focus:ring-4 focus:ring-cyan-400/10">
            @error('requester_name')<span class="text-sm text-rose-300">{{ $message }}</span>@enderror
        </label>

        <label class="grid gap-2">
            <span class="text-sm font-medium text-slate-200">Requester email</span>
            <input type="email" name="requester_email" value="{{ old('requester_email', $ticket->requester_email ?? '') }}" required maxlength="255"
                class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition focus:border-cyan-400/60 focus:ring-4 focus:ring-cyan-400/10">
            @error('requester_email')<span class="text-sm text-rose-300">{{ $message }}</span>@enderror
        </label>

        <label class="grid gap-2">
            <span class="text-sm font-medium text-slate-200">Priority</span>
            <select name="priority" required class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition focus:border-cyan-400/60">
                @foreach ($priorities as $priority)
                    <option value="{{ $priority->value }}" @selected(old('priority', isset($ticket) ? $ticket->priority->value : 'medium') === $priority->value)>{{ $priority->label() }}</option>
                @endforeach
            </select>
            @error('priority')<span class="text-sm text-rose-300">{{ $message }}</span>@enderror
        </label>

        <label class="grid gap-2">
            <span class="text-sm font-medium text-slate-200">Assigned to</span>
            <input type="text" name="assigned_to" value="{{ old('assigned_to', $ticket->assigned_to ?? '') }}" maxlength="100" placeholder="Optional"
                class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-400/60 focus:ring-4 focus:ring-cyan-400/10">
            @error('assigned_to')<span class="text-sm text-rose-300">{{ $message }}</span>@enderror
        </label>

        @isset($statuses)
            <label class="grid gap-2 md:col-span-2">
                <span class="text-sm font-medium text-slate-200">Status</span>
                <select name="status" required class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition focus:border-cyan-400/60">
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected(old('status', $ticket->status->value) === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
                @error('status')<span class="text-sm text-rose-300">{{ $message }}</span>@enderror
            </label>
        @endisset

        <label class="grid gap-2 md:col-span-2">
            <span class="text-sm font-medium text-slate-200">Description</span>
            <textarea name="description" rows="7" required minlength="10" maxlength="5000"
                class="rounded-xl border border-white/10 bg-slate-900/70 px-4 py-3 text-white outline-none transition focus:border-cyan-400/60 focus:ring-4 focus:ring-cyan-400/10">{{ old('description', $ticket->description ?? '') }}</textarea>
            @error('description')<span class="text-sm text-rose-300">{{ $message }}</span>@enderror
        </label>
    </div>

    <div class="flex flex-col-reverse gap-3 border-t border-white/10 pt-6 sm:flex-row sm:justify-end">
        <a href="{{ isset($ticket) ? route('tickets.show', $ticket) : route('tickets.index') }}" class="rounded-xl border border-white/10 px-4 py-3 text-center text-sm font-semibold text-slate-300 transition hover:bg-white/5">Cancel</a>
        <button type="submit" class="rounded-xl bg-cyan-400 px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-cyan-300">{{ $submitLabel }}</button>
    </div>
</div>
