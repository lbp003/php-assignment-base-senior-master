<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDamageReportRequest;
use App\Http\Requests\UpdateDamageReportRequest;
use App\Models\DamageReport;
use App\Services\DamageReportService;
use Exception;

class DamageReportController extends Controller
{
    /**
     * The damage report service instance.
     *
     * @var \App\Services\DamageReportService
     */
    protected $damageReportService;

    public function __construct(
        DamageReportService $damageReportService,
    ) {
        $this->damageReportService = $damageReportService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDamageReportRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDamageReportRequest $request)
    {
        try {
            return $this->damageReportService->handleStoreDamageReportRequest($request);
        } catch (Exception $e) {
            return response()->json(
                [
                'message' => $e->getMessage(),
            ],
                400
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\DamageReport  $damageReport
     * @return \Illuminate\Http\Response
     */
    public function show(DamageReport $damageReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\DamageReport  $damageReport
     * @return \Illuminate\Http\Response
     */
    public function edit(DamageReport $damageReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateDamageReportRequest  $request
     * @param  \App\Models\DamageReport  $damageReport
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateDamageReportRequest $request, DamageReport $damageReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\DamageReport  $damageReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(DamageReport $damageReport)
    {
        //
    }
}
