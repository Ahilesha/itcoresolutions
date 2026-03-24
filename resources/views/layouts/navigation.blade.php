@php
    $unreadNotificationsCount = 0;

    if (auth()->check()) {
        $unreadNotificationsCount = \App\Models\AppNotification::where('user_id', auth()->id())
            ->whereNull('read_at')
            ->count();
    }

    $user = auth()->user();

    $showPunch = $user && $user->hasAnyRole(['Admin', 'Operator']);
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between gap-4">
            {{-- Left side --}}
            <div class="flex items-center gap-6 min-w-0 flex-1">
                <a href="{{ route('dashboard') }}" class="flex items-center gap-3 shrink-0">
                    <img src="{{ asset('images/logo.png') }}"
                         alt="IT Core Solutions"
                         class="w-8 h-8 object-contain">

                    <span class="font-semibold text-gray-800 whitespace-nowrap hidden md:inline">
                        IT Core Solutions Inventory
                    </span>
                </a>

                {{-- Desktop navigation --}}
                <div class="hidden lg:flex items-center gap-5 flex-1 min-w-0">
                    <div class="flex items-center gap-5 overflow-x-auto whitespace-nowrap scrollbar-thin pb-1">
                        <a href="{{ route('dashboard') }}"
                           class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                           {{ request()->routeIs('dashboard') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            Dashboard
                        </a>

                        <a href="{{ route('notifications.index') }}"
                           class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                           {{ request()->routeIs('notifications.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <span>Notifications</span>
                            @if($unreadNotificationsCount > 0)
                                <span class="ml-2 inline-flex items-center justify-center text-xs px-2 py-0.5 rounded-full bg-red-600 text-white">
                                    {{ $unreadNotificationsCount }}
                                </span>
                            @endif
                        </a>

                        @can('units.view')
                            <a href="{{ route('units.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('units.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Units
                            </a>
                        @endcan

                        @can('materials.view')
                            <a href="{{ route('materials.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('materials.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Materials
                            </a>
                        @endcan

                        @can('products.view')
                            <a href="{{ route('products.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('products.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Products
                            </a>
                        @endcan

                        @can('orders.view')
                            <a href="{{ route('orders.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('orders.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Orders
                            </a>
                        @endcan

                        @can('orders.view')
                            <a href="{{ route('predictions.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('predictions.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Predictions
                            </a>
                        @endcan

                        @can('reports.view')
                            <a href="{{ route('reports.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('reports.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Reports
                            </a>
                        @endcan

                        @can('suppliers.view')
                            <a href="{{ route('suppliers.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('suppliers.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Suppliers
                            </a>
                        @endcan

                        @can('purchases.view')
                            <a href="{{ route('purchases.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('purchases.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Purchases
                            </a>
                        @endcan

                        @can('employees.view')
                            <a href="{{ route('employees.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('employees.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Employees
                            </a>
                        @endcan

                        @can('attendance.view')
                            <a href="{{ route('attendance.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('attendance.index') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Attendance
                            </a>
                        @endcan

                        @if($showPunch)
                            <a href="{{ route('attendance.punch') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('attendance.punch') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Punch
                            </a>
                        @endif

                        @can('users.view')
                            <a href="{{ route('users.index') }}"
                               class="inline-flex items-center px-1 py-2 border-b-2 text-sm font-medium shrink-0
                               {{ request()->routeIs('users.*') ? 'border-indigo-500 text-gray-900' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                                Users
                            </a>
                        @endcan
                    </div>
                </div>
            </div>

            {{-- Right side desktop --}}
            <div class="hidden lg:flex items-center shrink-0">
                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-600 bg-white hover:text-gray-800 focus:outline-none transition whitespace-nowrap">
                            <span>{{ Auth::user()->name }}</span>
                            <svg class="ms-2 h-4 w-4 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <div class="text-sm font-medium text-gray-800">{{ Auth::user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- Mobile / tablet hamburger --}}
            <div class="flex items-center lg:hidden shrink-0">
                <button @click="open = ! open"
                        class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-100 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- Mobile / tablet menu --}}
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden lg:hidden border-t border-gray-100 bg-white">
        <div class="px-4 pt-4 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('notifications.index')" :active="request()->routeIs('notifications.*')">
                <span class="inline-flex items-center">
                    {{ __('Notifications') }}
                    @if($unreadNotificationsCount > 0)
                        <span class="ml-2 inline-flex items-center justify-center text-xs px-2 py-0.5 rounded-full bg-red-600 text-white">
                            {{ $unreadNotificationsCount }}
                        </span>
                    @endif
                </span>
            </x-responsive-nav-link>

            @can('units.view')
                <x-responsive-nav-link :href="route('units.index')" :active="request()->routeIs('units.*')">
                    {{ __('Units') }}
                </x-responsive-nav-link>
            @endcan

            @can('materials.view')
                <x-responsive-nav-link :href="route('materials.index')" :active="request()->routeIs('materials.*')">
                    {{ __('Materials') }}
                </x-responsive-nav-link>
            @endcan

            @can('products.view')
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
                    {{ __('Products') }}
                </x-responsive-nav-link>
            @endcan

            @can('orders.view')
                <x-responsive-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')">
                    {{ __('Orders') }}
                </x-responsive-nav-link>
            @endcan

            @can('orders.view')
                <x-responsive-nav-link :href="route('predictions.index')" :active="request()->routeIs('predictions.*')">
                    {{ __('Predictions') }}
                </x-responsive-nav-link>
            @endcan

            @can('reports.view')
                <x-responsive-nav-link :href="route('reports.index')" :active="request()->routeIs('reports.*')">
                    {{ __('Reports') }}
                </x-responsive-nav-link>
            @endcan

            @can('suppliers.view')
                <x-responsive-nav-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')">
                    {{ __('Suppliers') }}
                </x-responsive-nav-link>
            @endcan

            @can('purchases.view')
                <x-responsive-nav-link :href="route('purchases.index')" :active="request()->routeIs('purchases.*')">
                    {{ __('Purchases') }}
                </x-responsive-nav-link>
            @endcan

            @can('employees.view')
                <x-responsive-nav-link :href="route('employees.index')" :active="request()->routeIs('employees.*')">
                    {{ __('Employees') }}
                </x-responsive-nav-link>
            @endcan

            @can('attendance.view')
                <x-responsive-nav-link :href="route('attendance.index')" :active="request()->routeIs('attendance.index')">
                    {{ __('Attendance') }}
                </x-responsive-nav-link>
            @endcan

            @if($showPunch)
                <x-responsive-nav-link :href="route('attendance.punch')" :active="request()->routeIs('attendance.punch')">
                    {{ __('Punch') }}
                </x-responsive-nav-link>
            @endif

            @can('users.view')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    {{ __('Users') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <div class="border-t border-gray-200 px-4 py-4">
            <div class="mb-3">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
