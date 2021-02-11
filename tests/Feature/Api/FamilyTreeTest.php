<?php

namespace Tests\Feature\Api;

use App\Models\FamilyTree;
use App\Models\Person;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FamilyTreeTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /**
     * Fetching family tree collection when user is not authenticated.
     *
     * @return void
     */
    public function testFetchingFamilyTreesWhenUnauthenticated()
    {
        $response = $this->json('GET', '/api/family-trees');

        $response->assertStatus(401);
    }

    /**
     * Fetching family tree collection when no family trees added.
     *
     * @return void
     */
    public function testFetchingEmptyFamilyTrees()
    {
        $user = User::factory()->createOne();

        $response = $this->actingAs($user)->json('GET', '/api/family-trees');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => []])
            ->assertJsonCount(0, 'data');
    }

    /**
     * Fetching family tree collection when some family trees are present.
     *
     * @return void
     */
    public function testFetchingFamilyTrees()
    {
        $user = User::factory()->createOne();
        $headPerson = Person::factory()->createOne([
            'owner_id' => $user->id,
        ]);

        FamilyTree::factory()
            ->count(5)
            ->create([
                'owner_id' => $user->id,
                'head_person_id' => $headPerson->id,
            ]);

        $response = $this->actingAs($user)->json('GET', '/api/family-trees');

        $response->assertStatus(200)
            ->assertJsonStructure(['data' => [
                '*' => [
                    'name'
                ]
            ]])
            ->assertJsonCount(5, 'data');
    }

    public function testCreatingFamilyTree()
    {
        $user = User::factory()->createOne();

        $name = $this->faker->name;

        $response = $this->actingAs($user)->json('POST', '/api/family-trees', [
            'name' => $name,
            'personGender' => $this->faker->randomElement(['male', 'female']),
            'personName' => $this->faker->firstName,
            'personSurname' => $this->faker->lastName,
            'personBirthdate' => $this->faker->date('Y-m-d'),
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['name']])
            ->assertJsonFragment(['name' => $name]);
    }
}
