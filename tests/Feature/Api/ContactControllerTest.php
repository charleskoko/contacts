<?php

namespace Tests\Feature\Api;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use DatabaseTransactions;

    private $user;
    private $contacts;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory(['name' => 'test user', 'email' => 'test.user@gmail.com'])->create();
        $this->contacts = Contact::factory(10)->create(['user_id' => $this->user->id]);
    }

    public function testUserCanStoreContact()
    {
        $newContactData = [
            'name' => 'new test user',
            'email' => 'newTestUser@email.com',
        ];
        Sanctum::actingAs($this->user);
        $response = $this->post(route('api.v1.contact_store'), $newContactData);
        $response->assertStatus(201);
        $this->assertDatabaseHas('contacts', $newContactData);
        $response->assertJsonStructure(['status', 'message', 'data']);
        $response->assertJsonCount('4', 'data');
    }

    public function testUserCanUpdateContact()
    {
        $oldData = ['email' => 'notUpdateUser@gmail.com', 'name' => 'not updated user', 'user_id' => $this->user->id];
        $contact = Contact::factory()->create($oldData);
        $newData = [
            'email' => 'updateUser@gmail.com',
            'name' => 'user updated'
        ];
        Sanctum::actingAs($this->user);
        $response = $this->patch(route('api.v1.contact_update', $contact->id), $newData);
        $response->assertStatus(200);
        $this->assertDatabaseHas('contacts', $newData);
        $this->assertDatabaseMissing('contacts', $oldData);
        $response->assertJsonStructure(['status', 'message', 'data']);
        $response->assertJsonCount('4', 'data');
    }

    public function testUserCanSeeHisContactList()
    {
        Sanctum::actingAs($this->user);
        $response = $this->get(route('api.v1.contact_index'));
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'data']);
        $response->assertJsonCount('10', 'data');
    }

    public function testUserCanDeleteContact()
    {
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        Sanctum::actingAs($this->user);
        $response = $this->delete(route('api.v1.contact_destroy',$contact->id));
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'data']);
    }
    public function testUserCanSeeContact(){
        $contact = Contact::factory()->create(['user_id' => $this->user->id]);
        Sanctum::actingAs($this->user);
        $response = $this->get(route('api.v1.contact_destroy',$contact->id));
        $response->assertStatus(200);
        $response->assertJsonStructure(['status', 'message', 'data']);
        $response->assertJsonCount('4', 'data');
    }

}
