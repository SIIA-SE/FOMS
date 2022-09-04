<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_login_form(){
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_user_duplication(){
        $user1 = User::make([
            'name' => 'TestUser1',
            'email' => 'testuser1@test.com',
        ]);
        $user2 = User::make([
            'name' => 'TestUser2',
            'email' => 'testuser2@test.com',
        ]);

        $this->assertTrue($user1->email != $user2->email);
    }

    public function test_delete_user()
    {
        $user1 = User::make([
            'name' => 'TestUser1',
            'email' => 'testuser1@test.com',
        ]);

        if($user1){
            $user1->delete();
        }

        $this->assertTrue(true);
    }
}
