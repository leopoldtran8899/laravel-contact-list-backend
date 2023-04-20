<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Note;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class TestingRelationshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleEmployee = Role::factory()->create([
            'name' => 'Employee',
        ]);
        $roleManager = Role::factory()->create([
            'name' => 'Manager',
        ]);
        Group::factory()
            ->count(1)
            ->has(
                Company::factory()->count(2),
                'companies'
            )
            ->create();
        $companyIds = Company::all()->pluck('id');
        $managerUsers = User::factory()
            ->count(1)
            ->has(
                Contact::factory()
                    ->state(function (array $attributes, User $user) use ($companyIds) {
                        return [
                            'email' => $user->email,
                            'company_id' => Company::first()->id
                        ];
                    }),
                'contact'
            )
            ->create(['role_id' => $roleManager->id]);
        User::factory()
            ->count(1)
            ->has(
                Contact::factory()
                    ->state(function (array $attributes, User $user) use ($managerUsers) {
                        $manager = fake()->randomElement($managerUsers);
                        return [
                            'email' => $user->email,
                            'company_id' => Company::first()->id,
                            'supervisor_id' => $manager->id
                        ];
                    }),
                'contact'
            )
            ->create(['role_id' => $roleEmployee->id]);
        User::factory()
            ->count(1)
            ->has(
                Contact::factory()
                    ->state(function (array $attributes, User $user) use ($managerUsers) {
                        $manager = fake()->randomElement($managerUsers);
                        return [
                            'email' => $user->email,
                            'company_id' => Company::latest('id', 'desc')->first()->id,
                        ];
                    }),
                'contact'
            )
            ->create(['role_id' => $roleEmployee->id]);

        $contact1 = Contact::first();
        $user1 = User::latest('id', 'desc')->first();
        Note::factory()->create([
            'note' => fake()->sentence(),
            'contact_id' => $contact1->id,
            'creator_id' => $user1->id,
        ]);
        Note::factory()->create([
            'note' => fake()->sentence(),
            'contact_id' => $contact1->id,
            'creator_id' => $contact1->user->id,
        ]);
    }
}