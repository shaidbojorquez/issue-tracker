<?php

namespace Tests\Feature;

use App\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\User;
use Laravel\Passport\Passport;

class ProjectTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    public function setUp(): void
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
        Passport::actingAs($this->user);
    }

    /**
     * Testing list projects.
     *
     * @return void
     */
    public function testCanListProjects()
    {
        // When
        $response = $this->json('GET', '/api/project');

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "data" => [
                '*' => [
                    'type',
                    'id',
                    'attributes'
                ]
            ]
        ]);
    }

    /**
     * Testing create projects.
     *
     * @return void
     */
    public function testCanCreateprojects()
    {
        // Given
        $project = factory(Project::class)->make();
        $projectData = [
            "data" => [
                "type"       => "projects",
                "attributes" => $project->toArray()
            ]
        ];

        // When
        $response = $this->json('POST', '/api/project', $projectData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "data"
        ]);

        $body = $response->decodeResponseJson();

        // Assert the model was created
        // with the correct data
        $response->assertJsonFragment([
            "data" => [
                "type" => "projects",
                "id" => $body['data']['id'],
                "attributes" => array_merge($project->toArray()),
                "links" => [
                    "self" => route('project.show', [$body['data']['id']])
                ]
            ]
        ]);

        // Assert model is on the database
        $this->assertDatabaseHas(
            'projects',
            array_merge(['id' => $body['data']['id']], $project->toArray())
        );

        return $body;
    }

    /**
     * Testing update projects.
     *
     * @return void
     */
    public function testCanUpdateProjects()
    {
        // Given
        $project = factory(Project::class)->create();
        $projectAttributes = $project->toArray();

        // We remove attributes that are not needed for update
        unset($projectAttributes['created_at']);
        unset($projectAttributes['updated_at']);
        unset($projectAttributes['id']);

        $projectData = [
            "data" => [
                "type"       => "projects",
                "attributes" => array_merge($projectAttributes, ['title' => '(ES) Nuevo título'])
            ]
        ];

        // When
        $response = $this->json('PUT', '/api/project/' . $project->id, $projectData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "data"
        ]);

        $body = $response->decodeResponseJson();

        // Assert the model was created
        // with the correct data
        $response->assertJsonFragment([
            "data" => [
                "type" => "projects",
                "id" => $body['data']['id'],
                "attributes" => array_merge($projectAttributes, ['title' => '(ES) Nuevo título']),
                "links" => [
                    "self" => route('project.show', [$body['data']['id']])
                ]
            ]
        ]);

        // Assert model is on the database
        $this->assertDatabaseHas(
            'projects',
            array_merge(['id' => $body['data']['id']], (array)$projectData['data']['attributes'])
        );

        return $body;
    }
    public function testCantUpdateProjectsDateFormat()
    {
        // Given
        $project = factory(Project::class)->create();
        $projectAttributes = $project->toArray();

        // We remove attributes that are not needed for update
        unset($projectAttributes['created_at']);
        unset($projectAttributes['updated_at']);
        unset($projectAttributes['id']);

        $projectData = [
            "data" => [
                "type"       => "projects",
                "attributes" => array_merge($projectAttributes, ['begin_date' => '(ES) Nuevo título'])
            ]
        ];

        // When
        $response = $this->json('PUT', '/api/project/' . $project->id, $projectData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);

    }
    public function testCanDeleteProject()
    {
        $project = factory(Project::class)->create();
        $response = $this->json('DELETE', '/api/project/' . $project->id);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
    }


    public function testCantDeleteProject()
    {
        //$project = factory(Project::class)->create();
        $response = $this->json('DELETE', '/api/project/' . '100');

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(404);
    }
    public function testCantUpdateProject()
    {
        //$project = factory(Project::class)->create();
        $response = $this->json('DELETE', '/api/project/' . '100');

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(404);

    }
}
