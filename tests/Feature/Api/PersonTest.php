<?php

namespace Tests\Feature\Api;

use App\Models\FamilyTree;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PersonTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAddingNewPerson()
    {
        $user = User::factory()->createOne();
        $headPerson = Person::factory()->createOne([
            'owner_id' => $user->id,
        ]);

        FamilyTree::factory()
            ->createOne([
                'owner_id' => $user->id,
                'head_person_id' => $headPerson->id,
            ]);

        $personData = [
            'parent_id' => $headPerson->id,
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'birthdate' => $this->faker->date('Y-m-d'),
        ];

        $response = $this->actingAs($user)->json('POST', '/api/persons', $personData);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => [
                'name',
                'surname',
                'birthdate',
            ]]);

        $this->assertDatabaseHas('persons', $personData);
    }
}
