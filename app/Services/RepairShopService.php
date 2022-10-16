<?php

namespace App\Services;

use App\Repositories\RepairShopRepository;

class RepairShopService
{
    /**
     * The repair shop repository instance.
     *
     * @var \App\Repositories\RepairShopRepository
     */
    protected $repairShopRepository;

    public function __construct(
        RepairShopRepository $repairShopRepository,
    ) {
        $this->repairShopRepository = $repairShopRepository;
    }

    /**
     * Handle GET all repair shop lat,long request.
     *
     * @return array $array
     */
    public function handleGetAllRepairShopsLatLongs()
    {
        return $this->repairShopRepository->getAllRepairShopsLatLongs();
    }
}
