<?php

namespace Tests\Feature\Admin;

use App\Models\ProgressPost;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Passport\Passport;
use Tests\TestCase;

class MediaUploadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    private function actAsAdmin(): void
    {
        Passport::actingAs(User::factory()->create());
    }

    public function test_admin_can_upload_an_image_and_gets_a_url(): void
    {
        $this->actAsAdmin();

        $res = $this->post('/api/admin/media', [
            'file' => UploadedFile::fake()->create('photo.jpg', 200, 'image/jpeg'),
        ]);

        $res->assertCreated()->assertJsonStructure(['id', 'url', 'file_path']);
        $this->assertNotEmpty($res->json('url'));
        Storage::disk('public')->assertExists($res->json('file_path'));
    }

    public function test_upload_rejects_non_image_files(): void
    {
        $this->actAsAdmin();

        $this->post('/api/admin/media', [
            'file' => UploadedFile::fake()->create('evil.exe', 10, 'application/octet-stream'),
        ])->assertStatus(422);
    }

    public function test_uploaded_image_can_be_attached_to_a_progress_post_and_appears_on_the_site(): void
    {
        $this->actAsAdmin();

        $mediaId = $this->post('/api/admin/media', [
            'file' => UploadedFile::fake()->create('p.jpg', 100, 'image/jpeg'),
        ])->json('id');

        $post = ProgressPost::factory()->create();
        $this->putJson("/api/admin/progress/{$post->id}", ['image_id' => $mediaId])->assertOk();

        $site = $this->getJson('/api/cms/site?lang=pl')->assertOk()->json();
        $this->assertNotEmpty($site['progress'][0]['imgUrl']);
    }

    public function test_media_upload_requires_authentication(): void
    {
        $this->post('/api/admin/media', [
            'file' => UploadedFile::fake()->create('p.jpg', 100, 'image/jpeg'),
        ], ['Accept' => 'application/json'])->assertUnauthorized();
    }
}
