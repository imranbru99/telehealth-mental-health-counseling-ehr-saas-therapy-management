<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use App\Models\Client;
use App\Models\Invoice;
use App\Models\TherapySession;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PracticeOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $tenantId = app()->has('current_tenant_id') ? app('current_tenant_id') : null;

        $todayAppointments = Appointment::when($tenantId, fn ($q) => $q->where('tenant_id', $tenantId))
            ->whereDate('scheduled_at', today())
            ->count();

        $activeClients = Client::when($tenantId, fn ($q) => $q->where('tenant_id', $tenantId))
            ->where('status', 'active')
            ->count();

        $completedSessions = TherapySession::when($tenantId, fn ($q) => $q->where('tenant_id', $tenantId))
            ->where('status', 'completed')
            ->count();

        $totalRevenue = Invoice::when($tenantId, fn ($q) => $q->where('tenant_id', $tenantId))
            ->where('status', 'paid')
            ->sum('total_amount');

        return [
            Stat::make("Today's Appointments", $todayAppointments)
                ->description('Scheduled for today')
                ->descriptionIcon('heroicon-m-calendar')
                ->color('primary'),

            Stat::make('Active Clients', $activeClients)
                ->description('Total enrolled clients')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success'),

            Stat::make('Completed Sessions', $completedSessions)
                ->description('Sessions documented')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('secondary'),

            Stat::make('Total Revenue', '$' . number_format($totalRevenue, 2))
                ->description('Collected payments')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('warning'),
        ];
    }
}
