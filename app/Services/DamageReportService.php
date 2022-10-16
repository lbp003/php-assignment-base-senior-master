<?php

namespace App\Services;

use App\Http\Requests\StoreDamageReportRequest;
use App\Http\Requests\UpdateStateDamageReportRequest;
use App\Models\DamageReport;
use App\Models\RepairShop;
use App\Repositories\DamageReportRepository;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use KMLaravel\GeographicalCalculator\Facade\GeoFacade;

class DamageReportService
{
    /**
     * The damage report repository instance.
     *
     * @var \App\Repositories\DamageReportRepository
     */
    protected $damageReportRepository;

    /**
     * The repair shop service instance.
     *
     * @var \App\Services\RepairShopService
     */
    protected $repairShopService;

    public function __construct(
        DamageReportRepository $damageReportRepository,
        RepairShopService $repairShopService,
    ) {
        $this->damageReportRepository = $damageReportRepository;
        $this->repairShopService = $repairShopService;
    }

    /**
     * Handle damage report POST request.
     *
     * @param  \App\Http\Requests\StoreDamageReportRequest  $request
     * @return \App\Models\DamageReport  $damageReport
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

        return $this->damageReportRepository->handleSave($damageReport);
    }

    /**
     * Handle damage report GET all request.
     *
     * @param string $state
     * @return array $array
     */
    public function handleGetAllDamageReports(string $state = null)
    {
        return $this->damageReportRepository->getAllDamageReports($state);
    }

    /**
     * Handle damage report GET one request.
     *
     * @param \App\Models\DamageReport $damageReport
     * @return \App\Models\DamageReport  $damageReport
     */
    public function handleGetDamageReport(DamageReport $damageReport)
    {
        $id = $damageReport->id;

        return $this->damageReportRepository->getDamageReportById($id);
    }

    /**
     * Handle damage report approval request.
     *
     * @param \App\Http\Requests\UpdateStateDamageReportRequest  $request
     * @return \App\Models\DamageReport
     */
    public function handleDamageReportApproval(
        UpdateStateDamageReportRequest $request,
        int $id
    ) {
        $isApproved = $request->is_approved;
        $stateBy = $request->state_by;

        $damageReport = $this->damageReportRepository->getDamageReportById($id);

        $currentState = $damageReport->state;

        if (
            $currentState === DamageReport::STATE_APPROVED ||
            $currentState === DamageReport::STATE_REJECTED
        ) {
            throw ValidationException::withMessages(['The resource has already updated.']);
        }

        if ($isApproved === true) {
            $damageReport->state = DamageReport::STATE_APPROVED;
            $damageReport->state_by = $stateBy;

            $latitude = $damageReport->latitude;
            $longitude = $damageReport->longitude;

            $location = [
                $latitude,
                $longitude,
            ];

            $repairShopsInArea = $this->checkLocationInGivenArea($location);

            if (!empty($repairShopsInArea)) {
                // @todo Send email to customer
            }
        } else {
            $damageReport->state = DamageReport::STATE_REJECTED;
            $damageReport->state_by = $stateBy;
            $damageReport->reason = $request->reason;
        }

        return $this->damageReportRepository->handleSave($damageReport);
    }

    /**
     * Handle all repair shops in area.
     *
     * @param array $location
     * @return array $repairShopsInArea
     */
    protected function checkLocationInGivenArea(array $location)
    {
        $repairShops = $this->repairShopService->handleGetAllRepairShopsLatLongs();
        $repairShopsInArea = [];

        foreach ($repairShops as $repairShop) {
            $isInArea = GeoFacade::setMainPoint([$location[0], $location[1]])
            // diameter in kilo meter
             ->setDiameter(RepairShop::REPAIR_SHOP_DISTANCE)
            // point to check, do not insert more than one point here.
             ->setPoint([$repairShop['latitude'], $repairShop['longitude']])
             ->isInArea();
            // the result is true or false

            if ($isInArea === true) {
                $repairShopsInArea[] = $repairShop;
            }
        }

        return $repairShopsInArea;
    }
}
