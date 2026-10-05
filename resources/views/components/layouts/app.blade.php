<div>
    <!-- People find pleasure in different ways. I find it in keeping my mind clear. - Marcus Aurelius -->
</div>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A Laravel portfolio project for managing office IT support tickets.">
    <title>{{ isset($title) ? $title.' · ' : '' }}Support Desk</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-slate-100 antialiased">
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute left-1/2 top-0 h-96 w-96 -translate-x-1/2 rounded-full bg-cyan-500/10 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-indigo-500/10 blur-3xl"></div>
    </div>

    @auth
        <header class="border-b border-white/10 bg-slate-950/80 backdrop-blur">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="{{ route('tickets.index') }}" class="flex items-center gap-3">
                    <span class="grid size-10 place-items-center rounded-xl bg-cyan-400 font-black text-slate-950">SD</span>
                    <span>
                        <span class="block font-semibold tracking-tight">Support Desk</span>
                        <span class="block text-xs text-slate-400">IT operations workspace</span>
                    </span>
                </a>

                <div class="flex items-center gap-3">
                    <span class="hidden text-sm text-slate-400 sm:inline">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="rounded-lg border border-white/10 px-3 py-2 text-sm font-medium text-slate-300 transition hover:border-white/20 hover:bg-white/5 hover:text-white">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </header>
    @endauth

    <main class="mx-auto w-full max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        @if (session('success'))
            <div class="mb-6 rounded-xl border border-emerald-400/20 bg-emerald-400/10 px-4 py-3 text-sm text-emerald-200">
                {{ session('success') }}
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
