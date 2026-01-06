<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class DashboardSop extends Widget
{
    protected static string $view = 'filament.widgets.dashboard-sop';

    // Tambahkan ini agar lebar widget memenuhi layar
    protected int | string | array $columnSpan = 'full';

    // Opsional: Mengatur urutan (semakin kecil semakin di atas)
    protected static ?int $sort = 1;

    
}
