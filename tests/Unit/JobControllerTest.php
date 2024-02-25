<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\JobModel;
use App\Models\User;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Foundation\Testing\RefreshDatabase;

class JobControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    $token = $user->createToken('test-token')->accessToken;
    $this->withHeader('Authorization', 'Bearer ' . $token);
    // Create some dummy job data
    JobModel::factory()->create(['created_by' => $user->id]);
    // Make a request to the index endpoint
    $response = $this->json('GET', '/api/job');

    // Assert the response structure and other necessary checks
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'result',
                 'status',
                 'message',
             ]);
}

public function testStore()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    $token = $user->createToken('test-token')->accessToken;
    $this->withHeader('Authorization', 'Bearer ' . $token);

    $data = [
        'title' => 'Test Job',
        'company_name' => 'Test Company',
        'location' => 'Test Location',
        'description' => 'Test Description',
        'application_instruments' => 'Test Instruments',
    ];

    // Make a request to the store endpoint
    $response = $this->json('POST', '/api/job', $data);

    // Assert the response structure and other necessary checks
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'result',
                 'status',
                 'message',
             ])
             ->assertJson([
                 'status' => true,
                 'message' => 'Created Successfully',
             ]);
}

public function testUpdate()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    $token = $user->createToken('test-token')->accessToken;
    $this->withHeader('Authorization', 'Bearer ' . $token);

     // Create a job record
     $job = JobModel::factory()->create(['created_by' => $user->id]);

     $updatedData = [
         'title' => 'Updated Job',
         'company_name' => 'Updated Company',
         'location' => 'Updated Location',
         'description' => 'Updated Description',
         'application_instruments' => 'Updated Instruments',
     ];

     // Make a request to the update endpoint
     $response = $this->json('PUT', "/api/job/{$job->id}", $updatedData);

     // Assert the response structure and other necessary checks
     $response->assertStatus(200)
              ->assertJson([
                  'status' => true,
                  'message' => 'Updated Successfully',
              ]);

     // Optionally, you can assert the updated data in the database
     $this->assertDatabaseHas('job_models', $updatedData);
}

public function testDestroy()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    $token = $user->createToken('test-token')->accessToken;
    $this->withHeader('Authorization', 'Bearer ' . $token);

     // Create a job record
     $job = JobModel::factory()->create(['created_by' => $user->id]);

     // Make a request to the destroy endpoint
     $response = $this->json('DELETE', "/api/job/{$job->id}");

     // Assert the response structure and other necessary checks
     $response->assertStatus(200)
              ->assertJson([
                  'status' => true,
                  'message' => 'Job soft deleted',
              ]);

    $this->assertSoftDeleted('job_models', ['id' => $job->id]);
}

public function testSearch()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    $token = $user->createToken('test-token')->accessToken;
    $this->withHeader('Authorization', 'Bearer ' . $token);

    $jobs = JobModel::factory(4)->create(['created_by' => $user->id]);

    // Perform a search for jobs
    $response = $this->json('GET', '/api/job/search', [
        'keyword' => 'developer',
        'location' => 'city',
        'company_name' => 'example',
    ]);

    // Assert the response structure and other necessary checks
    $response->assertStatus(200)
             ->assertJsonStructure([
                 'result',
                 'status',
                 'message',
             ])
             ->assertJson([
                 'status' => true,
                 'message' => 'Search results for jobs',
             ]);

    // Additional assertions as needed for the specific structure or content of the result
}

public function testApplyForJob()
{
    $user = User::factory()->create();
    $this->actingAs($user);
    $token = $user->createToken('test-token')->accessToken;
    $this->withHeader('Authorization', 'Bearer ' . $token);

    $job = JobModel::factory()->create(['created_by' => $user->id]);
        // Perform a request to apply for the job
        $response = $this->json('GET', '/api/job/apply', [
            'user_id' => $user->id,
            'job_id' => $job->id,
            'resume' => 'resume.pdf',
            'cover_letter' => 'cover.pdf',
        ]);
        $response->assertStatus(200)
        ->assertJsonStructure([
            'result',
            'status',
            'message',
        ])
        ->assertJson([
            'status' => true,
            'message' => 'Job application submitted successfully',
        ]);

}

public function testJobListing()
{

    $user = User::factory()->create();
    $this->actingAs($user);
    $token = $user->createToken('test-token')->accessToken;
    $this->withHeader('Authorization', 'Bearer ' . $token);

     // Create a job record
     $job = JobModel::factory()->create(['created_by' => $user->id]);
   // Make a request to the index endpoint
   $response = $this->json('GET', '/api/job/list');

   // Assert the response structure and other necessary checks
   $response->assertStatus(200)
            ->assertJsonStructure([
                'result',
                'status',
                'message',
            ]);
}
}
