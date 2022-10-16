<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreDamageReportRequest;
use App\Http\Requests\UpdateDamageReportRequest;
use App\Http\Requests\UpdateStateDamageReportRequest;
use App\Models\DamageReport;
use App\Services\DamageReportService;
use Exception;
use Illuminate\Http\Request;

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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $state = $request->state;

            $damageReports = $this->damageReportService->handleGetAllDamageReports($state);

            return response()->json(
                [
                  'success' => true,
                  'data' => $damageReports,
                ],
                200
            );
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
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreDamageReportRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreDamageReportRequest $request)
    {
        try {
            $damageReport = $this->damageReportService->handleStoreDamageReportRequest($request);

            return response()->json(
                [
                  'success' => true,
                  'data' => $damageReport,
                ],
                201
            );
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(DamageReport $damageReport)
    {
        try {
            $damageReport = $this->damageReportService->handleGetDamageReport($damageReport);

            return response()->json(
                [
                  'success' => true,
                  'data' => $damageReport,
                ],
                200
            );
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

    /**
     * Update the specified resource's state in storage.
     *
     * @param  \App\Http\Requests\UpdateStateDamageReportRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function approval(
        UpdateStateDamageReportRequest $request,
        int $id
    ) {
        try {
            $damageReport = $this->damageReportService->handleDamageReportApproval($request, $id);

            return response()->json(
                [
                  'success' => true,
                  'data' => $damageReport,
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(
                [
                    'message' => $e->getMessage(),
                ],
                400
            );
        }
    }
}
