<?php

namespace Tests\Unit;

use App\Models\Group;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class GroupTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function test_groups_database_has_expected_columns(): void
    {
        $this->assertTrue(
            Schema::hasColumns('groups', ['id', 'name']),
            1
        );
    }
    
    public function test_group_can_be_created(): void {
        $groupNameTest = 'Group 1';
        $group = Group::factory()->create([
            'name' => $groupNameTest
        ]);
        $this->assertInstanceOf(Group::class, $group);
        $this->assertEquals(1, Group::all()->count());
        $this->assertEquals($groupNameTest, $group->name);
    }
}