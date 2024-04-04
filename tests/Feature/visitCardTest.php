<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class visitCardTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }
    //* use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_store_visit_card()
    {
        // Create a user for testing purposes
        $user = User::factory()->create();

        // Simulate authentication
        $this->actingAs($user);

        // Create request data
        $requestData = [
            'name' => $user->name,
            'email' => 'john@example.com',
            'tel' => '123456789',
            'adress' => '123 Main St',
            'company' => 'Acme Inc',
            'description' => 'Lorem ipsum'
        ];

        // Call the store method
        $response = $this->post(route('visitcards.store'), $requestData);

        // Assert that the response is successful
        $response->assertStatus(201);

        // Assert that the visit card was created in the database
        $this->assertDatabaseHas('visitcards', [
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => 'john@example.com',
            'tel' => '123456789',
            'adress' => '123 Main St',
            'company' => 'Acme Inc',
            'description' => 'Lorem ipsum'
        ]);
    }

    public function test_update_visit_card()
    {
        // Create a user for testing purposes
        $user = User::factory()->create();

        // Create a visit card for the user
        $visitCard = $user->visitCards()->create([
            'name' => 'Old Name',
            'email' => 'old_email@example.com',
            'tel' => '987654321',
            'adress' => '456 Second St',
            'company' => 'Old Company',
            'description' => 'Old Description'
        ]);

        // Simulate authentication
        $this->actingAs($user);

        // Create request data for updating the visit card
        $requestData = [
            'name' => 'New Name',
            'email' => 'new_email@example.com',
            'tel' => '123456789',
            'adress' => '789 Third St',
            'company' => 'New Company',
            'description' => 'New Description'
        ];

        // Call the update method
        $response = $this->put(route('visitcards.update', $visitCard->id), $requestData);

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the visit card was updated in the database
        $this->assertDatabaseHas('visitcards', [
            'id' => $visitCard->id,
            'user_id' => $user->id,
            'name' => 'New Name',
            'email' => 'new_email@example.com',
            'tel' => '123456789',
            'adress' => '789 Third St',
            'company' => 'New Company',
            'description' => 'New Description'
        ]);
    }

    public function test_delete_visit_card()
    {
        // Create a user for testing purposes
        $user = User::factory()->create();

        // Create a visit card for the user
        $visitCard = $user->visitCards()->create([
            'name' => 'Test Name',
            'email' => 'test_email@example.com',
            'tel' => '123456789',
            'adress' => '123 Test St',
            'company' => 'Test Company',
            'description' => 'Test Description'
        ]);

        // Simulate authentication
        $this->actingAs($user);

        // Call the delete method
        $response = $this->delete(route('visitcards.destroy', $visitCard->id));

        // Assert that the response is successful
        $response->assertStatus(204);

        // Assert that the visit card was deleted from the database
        $this->assertDatabaseMissing('visitcards', [
            'id' => $visitCard->id,
        ]);
    }

    public function test_index_visit_cards()
    {
        // Create a user for testing purposes
        $user = User::factory()->create();

        // Create visit cards for the user
        $visitCard1 = $user->visitCards()->create([
            'name' => 'Visit Card 1',
            'email' => 'card1@example.com',
            'tel' => '123456789',
            'adress' => '123 Main St',
            'company' => 'Company 1',
            'description' => 'Description 1'
        ]);

        $visitCard2 = $user->visitCards()->create([
            'name' => 'Visit Card 2',
            'email' => 'card2@example.com',
            'tel' => '987654321',
            'adress' => '456 Second St',
            'company' => 'Company 2',
            'description' => 'Description 2'
        ]);

        // Simulate authentication
        $this->actingAs($user);

        // Call the index method
        $response = $this->get(route('visitcards.index'));

        // Assert that the response is successful
        $response->assertStatus(200);

        // Assert that the visit cards belonging to the user are present in the response
        $response->assertJsonFragment([
            'name' => 'Visit Card 1',
            'email' => 'card1@example.com',
            'tel' => '123456789',
            'adress' => '123 Main St',
            'company' => 'Company 1',
            'description' => 'Description 1'
        ]);

        $response->assertJsonFragment([
            'name' => 'Visit Card 2',
            'email' => 'card2@example.com',
            'tel' => '987654321',
            'adress' => '456 Second St',
            'company' => 'Company 2',
            'description' => 'Description 2'
        ]);
    }


}
