<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    /**
     * @test
     * @group auth
     */
    public function signup()
    {
        $user = factory(User::class)->make();
        $this
            ->postJson('/api/auth/signup', [
                'name' => $user->name,
                'email' => $user->email,
                'password' => $user->password,
                'password_confirmation' => $user->password,
            ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
        $this->assertTrue(DB::table('users')->count() === 1);
    }

    /**
     * @test
     * @group auth
     */
    public function login()
    {
        $user = factory(User::class)->create([
            'password' => bcrypt('secret')
        ]);
        $this
            ->postJson('/api/auth/login', [
                'email' => $user->email,
                'password' => 'secret',
            ])
            ->assertSuccessful()
            ->assertJsonStructure([
                'access_token',
                'token_type',
                'expires_in',
            ]);
    }
}
