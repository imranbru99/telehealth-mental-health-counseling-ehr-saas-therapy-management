<?php

namespace App\Filament\Widgets;

use App\Models\Appointment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Carbon;

class AppointmentsChartWidget extends ChartWidget
{
    protected static ?string $heading = 'Appointment Activity (Last 7 Days)';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $tenantId = app()->has('current_tenant_id') ? app('current_tenant_id') : null;

        $days = collect(range(6, 0))->map(function ($dayOffset) {
            return Carbon::today()->subDays($dayOffset);
        });

        $labels = $days->map(fn ($d) => $d->format('D, M j'))->toArray();

        $data = $days->map(function ($day) use ($tenantId) {
            return Appointment::when($tenantId, fn ($q) => $q->where('tenant_id', $tenantId))
                ->whereDate('scheduled_at', $day)
                ->count();
        })->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Appointments',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#0f766e',
                    'backgroundColor' => 'rgba(15, 118, 110, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
