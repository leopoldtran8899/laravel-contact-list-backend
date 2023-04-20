<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
// use PHPUnit\Framework\TestCase; // Get error of A facade root has not been set
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function test_companiess_database_has_expected_columns(): void
    {
        $this->assertTrue(
            Schema::hasColumns('companies', ['id', 'name', 'group_id']),
            1
        );
    }

    public function test_company_can_be_created(): void
    {
        $groupNameTest = 'Group 1';
        $companyNameTest = 'Company 1';
        $group = Group::factory()->create([
            'name' => $groupNameTest
        ]);
        $company = Company::factory()->create([
            'name' => $companyNameTest,
            'group_id' => $group->id
        ]);
        $this->assertInstanceOf(Company::class, $company);
        $this->assertEquals(1, Company::all()->count());
        $this->assertEquals($companyNameTest, $company->name);
        
    }
}