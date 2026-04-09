@extends('admin.layout')

@section('title', 'Admin - Overview')

@section('content')
<!-- Dashboard Header -->
<div class="mb-10">
<h2 class="text-3xl font-extrabold font-headline tracking-tight text-on-surface">Tableau de Bord Global</h2>
<p class="text-on-surface-variant font-medium mt-1">Real-time overview of clinical performance and patient traffic.</p>
</div>
<!-- Bento Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
<!-- Doctors Stat -->
<div class="bg-surface-container-low p-6 rounded-xl hover:shadow-lg transition-all duration-300 group">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-primary-fixed text-primary rounded-lg group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined" data-icon="medical_services">medical_services</span>
</div>
</div>
<h3 class="text-sm font-bold text-on-surface-variant mb-1">Active Doctors</h3>
<p class="text-4xl font-extrabold font-headline text-on-surface">{{ $doctorsCount }}</p>
</div>
<!-- Patients Stat -->
<div class="bg-surface-container-low p-6 rounded-xl hover:shadow-lg transition-all duration-300 group">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-secondary-fixed text-secondary rounded-lg group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined" data-icon="group">group</span>
</div>
</div>
<h3 class="text-sm font-bold text-on-surface-variant mb-1">Total Patients</h3>
<p class="text-4xl font-extrabold font-headline text-on-surface">{{ $patientsCount }}</p>
</div>
<!-- Appointments Today -->
<div class="bg-surface-container-low p-6 rounded-xl hover:shadow-lg transition-all duration-300 group">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-tertiary-fixed text-tertiary rounded-lg group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined" data-icon="calendar_today">calendar_today</span>
</div>
@if($todayRdv > 0)
<span class="text-xs font-bold text-tertiary px-2 py-1 bg-tertiary/10 rounded-full">High Traffic</span>
@endif
</div>
<h3 class="text-sm font-bold text-on-surface-variant mb-1">Today's Appointments</h3>
<p class="text-4xl font-extrabold font-headline text-on-surface">{{ $todayRdv }}</p>
</div>
<!-- Monthly Consultations -->
<div class="bg-surface-container-low p-6 rounded-xl hover:shadow-lg transition-all duration-300 group">
<div class="flex justify-between items-start mb-4">
<div class="p-3 bg-primary-fixed text-primary rounded-lg group-hover:scale-110 transition-transform">
<span class="material-symbols-outlined" data-icon="analytics">analytics</span>
</div>
@if($monthlyConsultations > 0)
<span class="text-xs font-bold text-primary px-2 py-1 bg-primary/10 rounded-full">Active</span>
@endif
</div>
<h3 class="text-sm font-bold text-on-surface-variant mb-1">Monthly Consultations</h3>
<p class="text-4xl font-extrabold font-headline text-on-surface">{{ $monthlyConsultations }}</p>
</div>
</div>
<!-- Main Insights Section -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
<!-- 7-Day Evolution Chart -->
<div class="lg:col-span-2 bg-surface-container-lowest p-8 rounded-xl shadow-[0_10px_30px_-5px_rgba(0,106,97,0.08)]">
<div class="flex items-center justify-between mb-8">
<div>
<h3 class="text-lg font-bold font-headline text-on-surface">System Activity</h3>
<p class="text-sm text-on-surface-variant">Weekly appointments & registrations</p>
</div>
</div>
<div class="relative h-64 w-full">
    <canvas id="appointmentsChart"></canvas>
</div>
</div>
<!-- Side User Statistics Chart -->
<div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_10px_30px_-5px_rgba(0,106,97,0.08)]">
<div class="flex items-center justify-between mb-8">
<div>
<h3 class="text-lg font-bold font-headline text-on-surface">User Distribution</h3>
<p class="text-sm text-on-surface-variant">Live role breakdown</p>
</div>
</div>
<div class="relative h-64 w-full flex justify-center">
    <canvas id="usersChart"></canvas>
</div>
</div>
</div>
<!-- Latest Users Table Section -->
<div class="mt-10 bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0_10px_30px_-5px_rgba(0,106,97,0.08)]">
<div class="p-6 flex items-center justify-between border-b border-outline-variant/10">
<h3 class="text-lg font-bold font-headline text-on-surface">Latest Users</h3>
</div>
<div class="overflow-x-auto">
@if($latestUsers->isEmpty())
<div class="p-8 text-center text-on-surface-variant">Aucun utilisateur trouvé.</div>
@else
<table class="w-full text-left">
<thead class="bg-surface-container-low text-[10px] font-bold uppercase tracking-widest text-outline">
<tr>
<th class="px-8 py-4">Nom</th>
<th class="px-8 py-4">Equipe</th>
<th class="px-8 py-4">Email</th>
<th class="px-8 py-4">Inscription</th>
<th class="px-8 py-4 text-right">Actions</th>
</tr>
</thead>
<tbody class="divide-y divide-outline-variant/10">
@foreach($latestUsers as $user)
<tr class="hover:bg-surface-container-low/50 transition-colors">
<td class="px-8 py-4">
<div class="flex items-center gap-3">
<div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-xs">{{ substr($user->name, 0, 2) }}</div>
<span class="text-sm font-bold text-on-surface">{{ $user->name }}</span>
</div>
</td>
<td class="px-8 py-4">
    @if($user->role === 'DOCTOR')
    <span class="px-3 py-1 bg-primary-fixed/30 text-primary-container text-[10px] font-bold rounded-full uppercase">Doctor</span>
    @elseif($user->role === 'PATIENT')
    <span class="px-3 py-1 bg-secondary-fixed/30 text-secondary text-[10px] font-bold rounded-full uppercase">Patient</span>
    @elseif($user->role === 'SECRETARY')
    <span class="px-3 py-1 bg-tertiary-fixed/30 text-tertiary text-[10px] font-bold rounded-full uppercase">Secretary</span>
    @else
    <span class="px-3 py-1 bg-surface-variant text-on-surface pb-[2px] text-[10px] font-bold rounded-full uppercase">{{ $user->role }}</span>
    @endif
</td>
<td class="px-8 py-4">
<span class="text-xs font-medium text-on-surface">{{ $user->email }}</span>
</td>
<td class="px-8 py-4 text-sm text-on-surface-variant">{{ $user->created_at->format('M d, Y') }}</td>
<td class="px-8 py-4 text-right">
<form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
    @csrf
    @method('DELETE')
    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
        <span class="material-symbols-outlined text-sm" data-icon="delete">delete</span>
    </button>
</form>
</td>
</tr>
@endforeach
</tbody>
</table>
@endif
</div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('appointmentsChart').getContext('2d');
        const chartData = @json($last7Days);
        
        const labels = chartData.map(item => item.label);
        const appointmentsData = chartData.map(item => item.count);
        const registrationsData = chartData.map(item => item.registrations);
        const loginsData = chartData.map(item => item.logins);

        // Styling gradients for Appointments
        let gradientStroke = ctx.createLinearGradient(0, 0, 700, 0);
        gradientStroke.addColorStop(0, '#006a61');
        gradientStroke.addColorStop(1, '#39b8fd');

        let gradientFill = ctx.createLinearGradient(0, 0, 0, 300);
        gradientFill.addColorStop(0, 'rgba(0, 106, 97, 0.4)');
        gradientFill.addColorStop(1, 'rgba(0, 106, 97, 0.05)');

        // Styling gradients for Registrations
        let regGradientStroke = ctx.createLinearGradient(0, 0, 700, 0);
        regGradientStroke.addColorStop(0, '#fd7e14');
        regGradientStroke.addColorStop(1, '#ffc107');

        let regGradientFill = ctx.createLinearGradient(0, 0, 0, 300);
        regGradientFill.addColorStop(0, 'rgba(253, 126, 20, 0.4)');
        regGradientFill.addColorStop(1, 'rgba(253, 126, 20, 0.05)');

        // Styling gradients for Logins
        let loginGradientStroke = ctx.createLinearGradient(0, 0, 700, 0);
        loginGradientStroke.addColorStop(0, '#6200ee');
        loginGradientStroke.addColorStop(1, '#9c4dff');

        let loginGradientFill = ctx.createLinearGradient(0, 0, 0, 300);
        loginGradientFill.addColorStop(0, 'rgba(98, 0, 238, 0.4)');
        loginGradientFill.addColorStop(1, 'rgba(98, 0, 238, 0.05)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Appointments',
                        data: appointmentsData,
                        borderColor: gradientStroke,
                        backgroundColor: gradientFill,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#006a61',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'New Registrations',
                        data: registrationsData,
                        borderColor: regGradientStroke,
                        backgroundColor: regGradientFill,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#fd7e14',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'User Logins',
                        data: loginsData,
                        borderColor: loginGradientStroke,
                        backgroundColor: loginGradientFill,
                        borderWidth: 3,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6200ee',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { 
                    legend: { 
                        display: true,
                        position: 'top',
                        labels: { usePointStyle: true, boxWidth: 8 }
                    } 
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1, color: '#6d7a77' },
                        grid: { borderDash: [4, 4], color: '#e0e3e5' },
                        border: { display: false }
                    },
                    x: {
                        ticks: { color: '#6d7a77' },
                        grid: { display: false },
                        border: { display: false }
                    }
                }
            }
        });

        // User Distribution Chart
        const userCtx = document.getElementById('usersChart').getContext('2d');
        const doctorsCount = {{ $doctorsCount }};
        const patientsCount = {{ $patientsCount }};
        const secretariesCount = {{ $secretariesCount }};
        
        new Chart(userCtx, {
            type: 'doughnut',
            data: {
                labels: ['Doctors', 'Patients', 'Secretaries'],
                datasets: [{
                    data: [doctorsCount, patientsCount, secretariesCount],
                    backgroundColor: [
                        '#006a61', // Primary Teal
                        '#39b8fd', // Secondary Blue
                        '#fd7e14'  // Orange/Tertiary
                    ],
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            font: {
                                family: "'Inter', sans-serif",
                                size: 12,
                                weight: '600'
                            },
                            color: '#4a5568'
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
