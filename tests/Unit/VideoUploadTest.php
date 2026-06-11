<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Upload;
use App\Models\Clip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;

class VideoUploadTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function a_clip_belongs_to_a_video_upload_via_uuid(): void
    {
        $user = User::create([
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'name' => 'jkowalski',
            'avatar' => 'default.png',
            'email' => 'test_relation@example.com',
            'password' => bcrypt('secret'),
            'must_change_password' => false
        ]);

        $videoUuid = '550e8400-e29b-41d4-a716-446655440000';
        $video = Upload::create([
            'user_id' => $user->id,
            'uuid' => $videoUuid,
            'original_name' => 'mecz_finalowy',
            'extension' => 'mp4',
            'status' => 'processing'
        ]);

        $clip = Clip::create([
            'uuid' => $videoUuid,
            'filename' => 'sprint_120s.mp4',
            'path' => 'storage/clips/sprint_120s.mp4',
            'label' => 'sprint',
            'start_time' => 120
        ]);

        $this->assertNotNull($clip->videoUpload);
        $this->assertEquals($video->original_name, $clip->videoUpload->original_name);
        $this->assertEquals($videoUuid, $clip->videoUpload->uuid);
    }
}
