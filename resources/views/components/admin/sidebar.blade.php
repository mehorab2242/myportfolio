@props([
    'items' => [],
])

<aside
    id="admin-sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-64 -translate-x-full flex-col bg-secondary-token text-slate-900 transition-transform duration-200 ease-out lg:translate-x-0"
    aria-label="Admin navigation"
>
    {{-- Brand --}}
    <div class="flex h-16 items-center gap-3 border-b border-black/10 px-5">
        <span class="flex h-9 w-9 items-center justify-center rounded-xl bg-primary-token text-white shadow-primary-glow">
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8"/>
                <path d="M3 9h18M9 9v11" stroke="currentColor" stroke-width="1.8"/>
            </svg>
        </span>
        <div class="min-w-0">
            <p class="truncate text-sm font-semibold tracking-tight text-slate-900">{{ config('app.name', 'MyPortfolio') }}</p>
            <p class="truncate text-xs text-slate-600">Admin panel</p>
        </div>
    </div>

    {{-- Menu --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
        @foreach ($items as $item)
            @php
                $isActive = request()->routeIs($item['route']);
            @endphp
            <a
                href="{{ route($item['route']) }}"
                class="group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition
                    {{ $isActive
                        ? 'bg-primary-soft text-primary-token ring-1 ring-inset ring-primary-soft'
                        : 'text-slate-800 hover:bg-black/5 hover:text-slate-950' }}"
                @if ($isActive) aria-current="page" @endif
            >
                <x-icon :name="$item['icon']" class="h-5 w-5 shrink-0 {{ $isActive ? 'text-primary-token' : 'text-slate-600 group-hover:text-slate-900' }}" />
                <span>{{ $item['label'] }}</span>
            </a>
        @endforeach
    </nav>

    {{-- Logout --}}
    <div class="border-t border-black/10 p-3">
        <button
            type="button"
            data-logout
            class="flex w-full items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium text-slate-800 transition hover:bg-rose-500/10 hover:text-rose-700 disabled:cursor-not-allowed disabled:opacity-60"
        >
            <x-icon name="log-out" class="h-5 w-5 shrink-0" />
            <span>Log out</span>
        </button>
    </div>
</aside>

{{-- Mobile overlay --}}
<div
    id="admin-sidebar-overlay"
    class="fixed inset-0 z-30 hidden bg-slate-950/60 backdrop-blur-sm lg:hidden"
    aria-hidden="true"
></div>
