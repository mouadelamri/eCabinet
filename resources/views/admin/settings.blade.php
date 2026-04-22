@extends('admin.layout')

@section('title', 'Admin - Settings')

@section('content')
<div class="max-w-7xl mx-auto space-y-12">
<!-- Page Header -->
<header class="flex flex-col gap-2">
<h2 class="text-4xl font-extrabold font-headline tracking-tight text-on-surface">Configuration du Cabinet</h2>
<p class="text-on-surface-variant max-w-2xl font-body">Manage global clinic parameters, administrative controls, and system-wide notification protocols within the secure sanctuary of eCabinet.</p>
</header>
<!-- Bento Grid Layout -->
<div class="grid grid-cols-12 gap-6">
<!-- Section: Clinic Protocols (Wide Card) -->
<section class="col-span-12 lg:col-span-8 bg-surface-container-low rounded-xl p-8 space-y-6">
<div class="flex justify-between items-end">
<div class="space-y-1">
<span class="text-[10px] uppercase font-bold tracking-[0.2em] text-primary">System Integrity</span>
<h3 class="text-2xl font-bold font-headline">Clinic-wide Notifications</h3>
</div>
<button class="px-6 py-2 bg-gradient-to-r from-primary to-primary-container text-on-primary rounded-lg text-sm font-semibold shadow-[0_10px_30px_-5px_rgba(0,106,97,0.15)] hover:scale-[1.02] transition-transform">
                            Add Alert Protocol
                        </button>
</div>
<!-- Notification Custom List -->
<div class="space-y-4">
@foreach($alertProtocols as $protocol)
<!-- Notification Item -->
<div class="flex items-center justify-between p-4 bg-surface-container-lowest rounded-xl hover:shadow-sm transition-shadow group">
<div class="flex items-center gap-4">
<div class="w-12 h-12 rounded-lg {{ $protocol->priority == 'Urgent' ? 'bg-primary-fixed text-on-primary-fixed' : ($protocol->priority == 'High' ? 'bg-tertiary-fixed text-on-tertiary-fixed' : 'bg-surface-container-high text-outline') }} flex items-center justify-center">
<span class="material-symbols-outlined" data-icon="{{ $protocol->icon }}" style="font-variation-settings: 'FILL' 1;">{{ $protocol->icon }}</span>
</div>
<div>
<h4 class="font-bold text-on-surface">{{ $protocol->name }}</h4>
<p class="text-xs text-on-surface-variant">{{ $protocol->description }}</p>
</div>
</div>
<div class="flex items-center gap-6">
<div class="text-right">
<p class="text-[10px] font-bold text-outline uppercase tracking-wider">Priority</p>
<span class="px-2 py-0.5 {{ $protocol->priority == 'Urgent' ? 'bg-primary-fixed text-on-primary-fixed' : ($protocol->priority == 'High' ? 'bg-tertiary-fixed text-on-tertiary-fixed' : 'bg-secondary-fixed text-on-secondary-fixed') }} text-[10px] font-bold rounded-full">{{ $protocol->priority }}</span>
</div>
<form action="{{ route('admin.settings.alert.toggle', $protocol) }}" method="POST">
    @csrf
    <button type="submit" class="w-10 h-6 {{ $protocol->is_active ? 'bg-primary' : 'bg-outline-variant/30' }} rounded-full relative cursor-pointer border-0 p-0 transition-colors">
        <div class="absolute {{ $protocol->is_active ? 'right-1' : 'left-1' }} top-1 w-4 h-4 bg-white rounded-full transition-all"></div>
    </button>
</form>
</div>
</div>
@endforeach
</div>
</section>
<!-- Section: Quick Controls (Tall Card) -->
<section class="col-span-12 lg:col-span-4 bg-primary text-on-primary rounded-xl p-8 relative overflow-hidden flex flex-col justify-between">
<div class="relative z-10">
<span class="text-[10px] uppercase font-bold tracking-[0.2em] opacity-80">Security Shield</span>
<h3 class="text-2xl font-bold font-headline mt-2 mb-4">Privacy Lockdown</h3>
<p class="text-sm opacity-90 font-body">Temporarily restrict all non-admin access to patient records during system maintenance or emergency audit.</p>
</div>
<div class="mt-8 space-y-4 relative z-10">
<form action="{{ route('admin.settings.lockdown') }}" method="POST">
    @csrf
    <button type="submit" class="w-full py-4 bg-surface-container-lowest {{ $lockdownStatus ? 'text-red-600' : 'text-primary' }} rounded-lg font-bold flex items-center justify-center gap-2 hover:bg-surface-container-low transition-colors">
        <span class="material-symbols-outlined" data-icon="lock">{{ $lockdownStatus ? 'lock_open' : 'lock' }}</span>
        {{ $lockdownStatus ? 'Deactivate Lockdown' : 'Activate Lockdown' }}
    </button>
</form>
<p class="text-[10px] text-center opacity-60 font-bold tracking-widest uppercase">Status: {{ $lockdownStatus ? 'System Locked Down' : 'System Unrestricted' }}</p>
</div>
<!-- Decorative Background Element -->
<div class="absolute -right-16 -bottom-16 w-64 h-64 bg-primary-container rounded-full opacity-20"></div>
</section>
        </div>
    </section>
</div>
</div>
</div>
@endsection
