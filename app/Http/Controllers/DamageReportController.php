<?php

namespace App\Http\Controllers;

use App\Models\DamageReport;
use App\Http\Requests\StoreDamageReportRequest;
use App\Http\Requests\UpdateDamageReportRequest;

class DamageReportController extends Controller
{
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
     * @return \Illuminate\Http\Response
     */
    public function store(StoreDamageReportRequest $request)
    {
        //
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
