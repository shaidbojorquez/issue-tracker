<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\User;
use App\Issue;
use App\Project;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Http\Resources\Issue as IssueResource;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\Project as ProjectResource;

class IssueTest extends TestCase
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
     * Testing list issues.
     *
     * @return void
     */
    public function testCanListIssues()
    {
        // When
        $response = $this->json('GET', '/api/issue');

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
     * Testing create issues.
     *
     * @return void
     */
    public function testCanCreateIssues()
    {
        // Given
        $assignTo = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $project->users()->sync([auth()->user()->id, $assignTo->id]); #Valida que ambos pertenezcamos
        $project->save();

        $issue = factory(Issue::class)->make();
        $issueAttributes = $issue->toArray();
        unset($issueAttributes['creator']);

        $issueData = [
            "data" => [
                "type"       => "issues",
                "attributes" => array_merge($issueAttributes, [
                    'project_id' => $project->id,
                    'assigned_to' => $assignTo->id
                ])
            ]
        ];

        // When
        $response = $this->json('POST', '/api/issue', $issueData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "data"
        ]);

        $body = $response->decodeResponseJson();

        unset($issueData['data']['attributes']['project_id']);
        // Assert the model was created
        // with the correct data
        $response->assertJsonFragment([
            "data" => [
                "type" => "issues",
                "id" => $body['data']['id'],
                "attributes" => $body['data']['attributes'],
                "links" => [
                    "self" => route('issue.show', [$body['data']['id']])
                ]
            ]
        ]);

        // Assert model is on the database
        $this->assertDatabaseHas(
            'issues',
            array_merge(['id' => $body['data']['id']])
        );

        return $body;
    }

    /**
     * Testing update issues.
     *
     * @return void
     */
    public function testCanUpdateIssues()
    {
        // Given
        $assignTo = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $project->users()->sync([auth()->user()->id, $assignTo->id]);
        $project->save();

        $issue = factory(Issue::class)->create();
        $issueAttributes = $issue->toArray();

        // We remove attributes that are not needed for update
        unset($issueAttributes['created_at']);
        unset($issueAttributes['updated_at']);
        unset($issueAttributes['id']);
        unset($issueAttributes['creator']);

        $issueData = [
            "data" => [
                "type"       => "issues",
                "attributes" => array_merge($issueAttributes, [
                    'title' => '(ES) Nuevo título',
                    'project_id' => $project->id,
                    'assigned_to' => $assignTo->id
                ])
            ]
        ];

        // When
        $response = $this->json('PUT', '/api/issue/' . $issue->id, $issueData);

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
                "type" => "issues",
                "id" => $body['data']['id'],
                "attributes" => $body['data']['attributes'],
                "links" => [
                    "self" => route('issue.show', [$body['data']['id']])
                ]
            ]
        ]);

        // Assert model is on the database
        $this->assertDatabaseHas(
            'issues',
            ['id' => $body['data']['id']]
        );

        return $body;
    }

    public function testCanAttachFileToIssue()
    {
        $issue = factory(Issue::class)->create();
        $issue->setCreator(auth()->user()->id);
        $issue->save();
        $file = UploadedFile::fake()->create('document3.pdf', 25);
        $response = $this->json('POST', '/api/issue/' . $issue->id . '/attach', [
            'attachments' => $file,
        ]);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);

        // Assert the file was stored...
        Storage::disk('local')->assertExists('disk' . DIRECTORY_SEPARATOR . 'issues' . DIRECTORY_SEPARATOR . 'issue_' . $issue->id . DIRECTORY_SEPARATOR . 'attachment_' . $file->name);
    }

    public function testCanDeleteIssue()
    {
        $project = factory(Project::class)->create();
        $project->users()->sync([auth()->user()->id]);
        $project->save();

        $issue = factory(Issue::class)->create();
        $issue->project_id = $project->id;
        $issue->setAssignedTo(auth()->user()->id);
        $issue->save();

        $response = $this->json('DELETE', '/api/issue/' . $issue->id);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
    }

    /**
     * Testing user cant create issue if he is not in the project.
     *
     * @return void
     */
    public function testUserCantCreateIssues()
    { #No lo puede crear por que no asignoa  nadie al proyecto
        // Given
        $assignTo = factory(User::class)->create();
        $project = factory(Project::class)->create();

        $issue = factory(Issue::class)->make();
        $issueAttributes = $issue->toArray();
        unset($issueAttributes['creator']);

        $issueData = [
            "data" => [
                "type"       => "issues",
                "attributes" => array_merge($issueAttributes, [
                    'project_id' => $project->id,
                    'assigned_to' => $assignTo->id
                ])
            ]
        ];

        // When
        $response = $this->json('POST', '/api/issue', $issueData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
    }

    /**
     * Testing user cant be assigned to an issue if he is not in the project.
     *
     * @return void
     */
    public function testUserCantBeAssignedIssues()
    { #No puede ser asignado al issue
        // Given
        $assignTo = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $project->users()->sync([$this->user->id]);
        $project->save();

        $issue = factory(Issue::class)->make();
        $issueAttributes = $issue->toArray();
        unset($issueAttributes['creator']);

        $issueData = [
            "data" => [
                "type"       => "issues",
                "attributes" => array_merge($issueAttributes, [
                    'project_id' => $project->id,
                    'assigned_to' => $assignTo->id
                ])
            ]
        ];

        // When
        $response = $this->json('POST', '/api/issue', $issueData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
    }

    /**
     * Testing user cant update issues if he is not in the project.
     *
     * @return void
     */
    public function testCantUpdateIssues()
    {
        // Given
        $assignTo = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $project->users()->sync([$assignTo->id]);
        $project->save();

        $issue = factory(Issue::class)->create();
        $issueAttributes = $issue->toArray();

        // We remove attributes that are not needed for update
        unset($issueAttributes['created_at']);
        unset($issueAttributes['updated_at']);
        unset($issueAttributes['id']);
        unset($issueAttributes['creator']);

        $issueData = [
            "data" => [
                "type"       => "issues",
                "attributes" => array_merge($issueAttributes, [
                    'title' => '(ES) Nuevo título',
                    'project_id' => $project->id,
                    'assigned_to' => $assignTo->id
                ])
            ]
        ];

        // When
        $response = $this->json('PUT', '/api/issue/' . $issue->id, $issueData);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);

    }

    public function testCantAttachFileToIssueCreator(){
        $assignTo = factory(User::class)->create();
        $issue = factory(Issue::class)->create();
        $issue->setCreator( $assignTo->id);
        $issue->save();
        $file = UploadedFile::fake()->create('document3.pdf', 500);
        $response = $this->json('POST', '/api/issue/' . $issue->id . '/attach', [
            'attachments' => $file,
        ]);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(401);
    }

    public function testCantAttachFileToIssue()
    {
        $issue = factory(Issue::class)->create();

        $file = UploadedFile::fake()->create('document3.exe', 50000);
        $response = $this->json('POST', '/api/issue/' . $issue->id . '/attach', [
            'attachments' => $file,
        ]);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(422);
    }

    /**
     * Testing user cant delete issues if he is not the creator/owner of the issue.
     *
     * @return void
     */
    public function testCantDeleteIssue()
    {
        $assignTo = factory(User::class)->create();
        $project = factory(Project::class)->create();
        $project->users()->sync([$assignTo->id]);
        $project->save();

        $issue = factory(Issue::class)->create();
        $issue->project_id = $project->id;
        $issue->setAssignedTo($assignTo->id);
        $issue->setCreator($assignTo->id); // diferent of current auth user
        $issue->save();

        $response = $this->json('DELETE', '/api/issue/' . $issue->id);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(401);
    }

}
