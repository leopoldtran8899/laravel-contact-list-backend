<?php

namespace Tests\Unit;

use App\Models\Company;
use App\Models\Contact;
use App\Models\Group;
use App\Models\Note;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\TestingRelationshipSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Schema;
// use PHPUnit\Framework\TestCase; // Get error of A facade root has not been set
use Tests\TestCase;

class ModelsTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TestingRelationshipSeeder::class);

    }
    // Group
    public function test_group_has_many_companies(): void
    {
        $group = Group::first();
        $this->assertEquals(2, $group->companies->count());
    }
    // Company
    public function test_company_belongs_to_one_group(): void
    {
        $company = Company::first();
        $this->assertInstanceOf(Group::class, $company->group);
    }
    public function test_company_has_many_contacts(): void
    {
        $company1 = Company::first();
        $this->assertEquals(2, $company1->contacts->count());
    }
    public function test_company_has_many_users(): void
    {
        $company1 = Company::first();
        $this->assertEquals(2, $company1->users->count());
        $this->assertInstanceOf(User::class, $company1->users->first());
    }
    // Contact
    public function test_contact_has_one_user(): void
    {
        $contact1 = Contact::first();
        $this->assertInstanceOf(User::class, $contact1->user);
    }
    public function test_contact_belongs_to_one_company(): void
    {
        $contact1 = Contact::first();
        $this->assertInstanceOf(Company::class, $contact1->company);
    }
    public function test_contact_has_many_notes(): void
    {
        $contact1 = Contact::first();
        $this->assertEquals(2, $contact1->notes->count());
        $this->assertInstanceOf(Note::class, $contact1->notes->first());
    }
    // User
    public function test_user_has_one_role(): void
    {
        $user1 = User::first();
        $this->assertInstanceOf(Role::class, $user1->role);
    }

    public function test_user_has_one_contact(): void
    {
        $user1 = User::first();
        $this->assertInstanceOf(Contact::class, $user1->contact);
    }

    public function test_user_has_many_notes(): void
    {
        $user2 = User::latest('id', 'decs')->first();
        $this->assertEquals(1, $user2->notes->count());
    }
    // Role
    public function test_role_has_many_users(): void
    {
        $role1 = Role::first();
        $this->assertEquals(2, $role1->users->count());
    }
    // Note
    public function test_note_has_one_creator(): void
    {
        $note = Note::first();
        $this->assertInstanceOf(User::class, $note->creator);
    }
    public function test_note_has_one_contact(): void
    {
        $note = Note::first();
        $this->assertInstanceOf(Contact::class, $note->contact);
    }
}