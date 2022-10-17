<?php

namespace App\Services;

use App\Http\Requests\StoreDamageReportRequest;
use App\Http\Requests\UpdateStateDamageReportRequest;
use App\Models\DamageReport;
use App\Models\Image;
use App\Notifications\NotifyCustomerOnRepairShopsAssigned;
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

    /**
     * The customer service instance.
     *
     * @var \App\Services\CustomerService
     */
    protected $customerService;

    public function __construct(
        DamageReportRepository $damageReportRepository,
        RepairShopService $repairShopService,
        CustomerService $customerService,
    ) {
        $this->damageReportRepository = $damageReportRepository;
        $this->repairShopService = $repairShopService;
        $this->customerService = $customerService;
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

        $damageReport = $this->damageReportRepository->handleSave($damageReport);

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $damageReportId = $damageReport->id;

            $this->handleImages($images, $damageReportId);
        }

        return $damageReport;
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
                foreach ($repairShopsInArea as $repairShop) {
                    $damageReport->repairShops()->attach($repairShop['id']);
                }

                $customer = $this->customerService->handleGetCustomer($damageReport->customer_id);
                $customer->notify(new NotifyCustomerOnRepairShopsAssigned($repairShopsInArea, $customer));
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
             ->setDiameter(config('app.distance_radius'))
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

    /**
     * Handle uploaded images.
     *
     * @param array $images
     * @param int $damageReportId
     */
    protected function handleImages(array $images, int $damageReportId): void
    {
        foreach ($images as $file) {
            $realName = $file->getClientOriginalName();
            $path = $file->store('damage_report_images');

            $image = new Image();

            $image->damage_report_id = $damageReportId;
            $image->path = $path;
            $image->filename = $realName;

            $this->damageReportRepository->handleImageSave($image);
        }
    }
}
