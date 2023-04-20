<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Company;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Note;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
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
            ->count(3)
            ->has(
                Company::factory()->count(3),
                'companies'
            )
            ->create();
        $companyIds = Company::all()->pluck('id');
        $managerUsers = User::factory()
            ->count(5)
            ->has(
                Contact::factory()
                    ->state(function (array $attributes, User $user) use ($companyIds) {
                        return [
                            'email' => $user->email,
                            'company_id' => fake()->randomElement($companyIds)
                        ];
                    }),
                'contact'
            )
            ->create(['role_id' => $roleManager->id]);
        $employeeUsers = User::factory()
            ->count(15)
            ->has(
                Contact::factory()
                    ->state(function (array $attributes, User $user) use ($managerUsers) {
                        $manager = fake()->randomElement($managerUsers);
                        return [
                            'email' => $user->email,
                            'company_id' => $manager->contact->company_id,
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
                    ->state(function (array $attributes, User $user) use ($companyIds) {
                        return [
                            'email' => $user->email,
                            'company_id' => fake()->randomElement($companyIds)
                        ];
                    }),
                'contact'
            )
            ->create(['email' => 'admin1@mail.com', 'role_id' => $roleManager->id]);
        $contactIds = Contact::all()->pluck('id')->toArray();
        $userIds = User::all()->pluck('id')->toArray();
        for ($i = 0; $i < 15; $i++) {
            Note::factory()->create([
                'note' => fake()->sentence(),
                'contact_id' => fake()->randomElement($contactIds),
                'creator_id' => fake()->randomElement($userIds),
            ]);
        }

    }
}