<?php

namespace App\Repositories;

use App\Models\DamageReport;

class DamageReportRepository
{
    public function handleSave(DamageReport $damageReport): DamageReport
    {
        $damageReport->save();

        return $damageReport;
    }

    public function getAllDamageReports(string $state = null): array
    {
        return DamageReport::orderBy('created_at', 'desc')
                ->where('state', $state)
                ->get()
                ->toArray();
    }

    public function getDamageReportById(int $id): DamageReport
    {
        return DamageReport::find($id);
    }
}
