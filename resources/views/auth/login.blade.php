<div>
    <!-- Smile, breathe, and go slowly. - Thich Nhat Hanh -->
</div>
<x-layouts.app title="Sign in">
    <div class="mx-auto flex min-h-[78vh] max-w-md items-center">
        <section class="w-full rounded-3xl border border-white/10 bg-white/5 p-6 shadow-2xl shadow-cyan-950/20 sm:p-8">
            <div class="mb-8">
                <div class="mb-5 grid size-12 place-items-center rounded-2xl bg-cyan-400 font-black text-slate-950">SD</div>
                <p class="text-sm font-semibold uppercase tracking-[0.2em] text-cyan-300">Support operations</p>
                <h1 class="mt-2 text-3xl font-bold tracking-tight">Welcome back</h1>
                <p class="mt-2 text-sm leading-6 text-slate-400">Sign in to review, assign, and resolve office IT requests.</p>
            </div>

            <form method="POST" action="{{ route('login.store') }}" class="grid gap-5">
                @csrf

                <label class="grid gap-2">
                    <span class="text-sm font-medium text-slate-200">Email address</span>
                    <input type="email" name="email" value="{{ old('email', config('supportdesk.demo_email')) }}" required autofocus autocomplete="username"
                        class="rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white outline-none transition placeholder:text-slate-600 focus:border-cyan-400/60 focus:ring-4 focus:ring-cyan-400/10">
                    @error('email')<span class="text-sm text-rose-300">{{ $message }}</span>@enderror
                </label>

                <label class="grid gap-2">
                    <span class="text-sm font-medium text-slate-200">Password</span>
                    <input type="password" name="password" required autocomplete="current-password"
                        class="rounded-xl border border-white/10 bg-slate-900/80 px-4 py-3 text-white outline-none transition focus:border-cyan-400/60 focus:ring-4 focus:ring-cyan-400/10">
                    @error('password')<span class="text-sm text-rose-300">{{ $message }}</span>@enderror
                </label>

                <button type="submit" class="mt-2 rounded-xl bg-cyan-400 px-4 py-3 font-bold text-slate-950 transition hover:bg-cyan-300 focus:outline-none focus:ring-4 focus:ring-cyan-400/20">
                    Sign in to dashboard
                </button>
            </form>

            <div class="mt-6 rounded-xl border border-white/10 bg-slate-900/60 p-4 text-xs leading-5 text-slate-400">
                Demo account: <span class="font-medium text-slate-200">{{ config('supportdesk.demo_email') }}</span> / <span class="font-medium text-slate-200">password</span>
            </div>
        </section>
    </div>
</x-layouts.app>
