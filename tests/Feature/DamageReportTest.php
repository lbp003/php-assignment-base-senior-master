<?php

namespace Tests\Feature;

use Tests\TestCase;

class DamageReportTest extends TestCase
{
    protected string $damageReportId;

    /**
     * Post damage reports.
     *
     * @return void
     */
    public function test_post_damage_reports()
    {
        $response = $this->post(
            '/api/damage-reports',
            [
            'customer_id'=> 3,
            'created_by' => 1,
            'description' => 'test description',
            'latitude' => 7.1529330,
            'longitude' => 81.2197660,
            'date' => '2022-10-15',
        ]
        );

        $response->assertCreated();
        $response->assertSee('id');
    }

    /**
     * Get all damage reports.
     *
     * @return void
     */
    public function test_get_all_damage_reports()
    {
        $response = $this->get('/api/damage-reports');

        $response->assertOk(200);
    }

    /**
     * Get damage report by id.
     *
     * @return void
     */
    public function test_get_damage_report()
    {
        $response = $this->get('/api/damage-reports/1');

        $response->assertOk(200);
    }

    /**
     * Update damage report state without reason.
     *
     * @return void
     */
    public function test_update_damage_report_approval_reject_with_out_reason()
    {
        $response = $this->put('/api/damage-reports/1/approval', [
            'is_approved' => false,
            'state_by' => 1,
            'reason' => null,
        ]);

        $response->assertStatus(400);
    }

    /**
     * Update damage report state with season.
     *
     * @return void
     */
    public function test_update_damage_report_approval_reject_with_reason()
    {
        $response = $this->put('/api/damage-reports/1/approval', [
            'is_approved' => false,
            'state_by' => 1,
            'reason' => null,
        ]);

        $response->assertStatus(200);
    }
}
