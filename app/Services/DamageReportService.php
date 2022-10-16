<?php

namespace App\Services;

use App\Http\Requests\StoreDamageReportRequest;
use App\Models\DamageReport;
use App\Repositories\DamageReportRepository;
use Carbon\Carbon;

class DamageReportService
{
    /**
     * The damage report repository instance.
     *
     * @var \App\Repositories\DamageReportRepository
     */
    protected $damageReportRepository;

    public function __construct(
        DamageReportRepository $damageReportRepository,
    ) {
        $this->damageReportRepository = $damageReportRepository;
    }

    /**
     * Handle damage report POST request.
     *
     * @param  \App\Http\Requests\StoreDamageReportRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handleStoreDamageReportRequest(StoreDamageReportRequest $request)
    {
        $damageReport = new DamageReport;

        $damageReport->customer_id = $request->customer_id;
        $damageReport->created_by = $request->created_by; // @todo this can replace with auth user
        $damageReport->description = $request->description;
        $damageReport->latitude = $request->latitude;
        $damageReport->longitude = $request->longitude;
        $damageReport->date = $request->date;
        $damageReport->state = DamageReport::STATE_NEW;
        $damageReport->damage_report_number = 'DRNO-' . Carbon::now()->timestamp;

        $lastInsertId = $this->damageReportRepository->handleSave($damageReport);

        return response()->json(
            [
              'success' => true,
              'last_insert_id' => $lastInsertId,
            ],
            201
        );
    }
}
