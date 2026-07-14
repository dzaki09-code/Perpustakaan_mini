<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_update_their_profile(): void
    {
        $user = User::create([
            'name' => 'Member User',
            'email' => 'member@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $response = $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => 'Updated Name',
                'email' => 'updated@example.com',
                'password' => 'new-password',
                'password_confirmation' => 'new-password',
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Name',
            'email' => 'updated@example.com',
        ]);

        $user->refresh();
        $this->assertTrue(Hash::check('new-password', $user->password));
    }

    public function test_authenticated_user_can_upload_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::create([
            'name' => 'Member User',
            'email' => 'photo@example.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_USER,
            'status' => User::STATUS_ACTIVE,
        ]);

        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this->actingAs($user)
            ->put(route('profile.update'), [
                'name' => $user->name,
                'email' => $user->email,
                'photo' => $file,
            ]);

        $response->assertRedirect(route('profile.edit'));
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertNotNull($user->profile_photo_path);
        $this->assertTrue(Storage::disk('public')->exists($user->profile_photo_path));
    }
}

