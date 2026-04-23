
@extends('secretary.layout')
@section('content')


<?php
use App\Models\User;
?>

            <div class="max-w-7xl mx-auto space-y-8">
                <!-- Page Header -->
                <div class="flex items-end justify-between gap-4 mb-4">
                    <div>
                        <h2 class="text-3xl font-extrabold font-headline tracking-tight text-on-surface mb-1">Management of pending Appointments. </h2>
                        <p class="text-on-surface-variant font-body">Database of pending appointments.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-primary">{{ $rendezVous->total() }} appointments</span>
                    </div>
            </div>
                <section class="col-span-12 xl:col-span-8 space-y-6">
            <div class="bg-surface-container-low rounded-xl overflow-hidden border border-outline-variant/5">
                @if($rendezVous->count() == 0)
                    <div class="p-10 text-center text-on-surface-variant font-medium">No pending appointments found.</div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="text-[11px] font-bold uppercase tracking-widest text-on-surface-variant bg-surface-container-low">
                                <th class="px-6 py-4">Patient</th>
                                <th class="px-6 py-4">medecin</th>
                                <th class="px-6 py-4 text-center">date/heure</th>
                                <th class="px-6 py-4">motif</th>
                                <th class="px-6 py-4">status </th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-surface-container">
                            @foreach($rendezVous as $rv)
                            <tr class="hover:bg-surface-bright transition-colors group">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                            <p class="text-sm font-bold text-on-surface">{{ User::where('id', $rv->patient_id)->value('name') }}</p>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                    <p class="text-sm font-medium text-on-surface">{{ User::where('id', $rv->medecin_id)->value('name')}}</p>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                        <p class="text-xs font-semibold text-on-surface">{{ $rv->date_heure }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                        <p class="text-xs font-semibold text-on-surface">{{ $rv->motif  }}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-primary"></span>
                                        <p class="text-xs font-semibold text-on-surface">{{ $rv->statut}}</p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                <div class="flex justify-end gap-2">

                                   <form action="{{ route('secretary.cancel', $rv) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-sm">cancel</span>
                                        </button>
                                    </form>
                                    <form action="{{ route('secretary.confirm', $rv) }}" method="POST">
                                        @csrf
                                        @method('PATCH')

                                        <button type="submit"
                                            class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-colors">
                                            <span class="material-symbols-outlined text-sm">check</span>
                                        </button>
                                    </form>
                                    

                                </div>
                            </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-center">
                    {{ $rendezVous->links() }}
                </div>
                @endif
@endsection
