<?php

namespace App\Repositories;

use App\Models\DamageReport;

class DamageReportRepository
{
    public function handleSave(DamageReport $damageReport): int
    {
        $damageReport->save();

        return $damageReport->id;
    }
}
