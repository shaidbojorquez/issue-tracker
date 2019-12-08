<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Laravel\Passport\Passport;
use App\User;
use App\Issue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class IssueTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $user = factory(User::class)->create();#necesito estar autenticado
        Passport::actingAs($user);#metodo de lavravel passport para las pruebas, hace que mis purebas tengan en su cabecera el token para el usuario que cree
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
        $issue = factory(Issue::class)->make();#creo el issue, no lo guarda en bd solo crea la instancia
        $issueData = [
            "data" => [
                "type"       => "issues",
                "attributes" => $issue->toArray()#del issue que cree, pasa los atributos del modelo a un arrelgo
            ]
        ];

        // When
        $response = $this->json('POST', '/api/issue', $issueData);#lo envio a mi api para que se guarde en base de datos

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(201);

        // Assert the response has the correct structure
        $response->assertJsonStructure([#Solo que tenga data
            "data"
        ]);

        $body = $response->decodeResponseJson();#trnasforma el json que devuelve la api a un arreglo

        // Assert the model was created
        // with the correct data
        $response->assertJsonFragment([
            "data" => [
                "type" => "issues",
                "id" => $body['data']['id'],#el id de mi issue
                "attributes" => array_merge($issue->toArray(), ['assignee' => null]),#para segurarme de que regrese con lo que yo le mande
                "links" => [
                    "self" => route('issue.show', [$body['data']['id']])
                ]
            ]
        ]);

        // Assert model is on the database
        $this->assertDatabaseHas(
            'issues',
            array_merge(['id' => $body['data']['id']], $issue->toArray())#Busca lo que le envie en la base de datos
        );

        return $body;#Respuesta de la api
    }

    /**
     * Testing update issues.
     *
     * @return void
     */
    public function testCanUpdateIssues()
    {
        // Given
        $issue = factory(Issue::class)->create();#Lo guarda en la base de datos
        $issueAttributes = $issue->toArray();#Saca los atributos del issue que creaste y los pone en un arreglo

        // We remove attributes that are not needed for update, No me sirven para actualizar
        unset($issueAttributes['created_at']);
        unset($issueAttributes['updated_at']);
        unset($issueAttributes['id']);

        $issueData = [
            "data" => [
                "type"       => "issues",
                "attributes" => array_merge($issueAttributes, ['title' => '(ES) Nuevo título'])#Le pongo otro titulo
            ]
        ];

        // When
        $response = $this->json('PUT', '/api/issue/' . $issue->id, $issueData);#Le mando a la ruta el id del issue y los datos nuevos

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);

        // Assert the response has the correct structure
        $response->assertJsonStructure([
            "data"
        ]);

        $body = $response->decodeResponseJson(); #solo hace esto en esta linea

        // Assert the model was created
        // with the correct data Que la respuesta tenga ese json
        $response->assertJsonFragment([
            "data" => [
                "type" => "issues",
                "id" => $body['data']['id'],
                "attributes" => array_merge($issueAttributes, ['assignee' => null, 'title' => '(ES) Nuevo título']),
                "links" => [
                    "self" => route('issue.show', [$body['data']['id']])
                ]
            ]
        ]);

        // Assert model is on the database
        $this->assertDatabaseHas(
            'issues',
            array_merge(['id' => $body['data']['id']], $issueData['data']['attributes'])
        );

        return $body;
    }

    public function testCanAttachFileToIssue()
    {
        $issue = factory(Issue::class)->create();

        $file = UploadedFile::fake()->create('document3.pdf', 25);#Nombre y tamaño
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
        $issue = factory(Issue::class)->create();
        $response = $this->json('DELETE', '/api/issue/' . $issue->id);

        // Then
        // Assert it sends the correct HTTP Status
        $response->assertStatus(200);
    }
}
