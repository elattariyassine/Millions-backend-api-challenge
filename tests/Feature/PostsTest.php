<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use App\Notifications\PostCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class PostsTest extends TestCase
{
    use RefreshDatabase,
        WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->setUpFaker();
    }

    protected function authenticate()
    {
        $user = User::create([
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            'password' => Hash::make($this->faker->password),
        ]);
        $token = JWTAuth::fromUser($user);

        return ['token' => $token, 'user' => $user];
    }

    function test_unauthenticated_user_can_view_feed()
    {
        $this->getJson(route('feed'))
            ->assertOk()
            ->assertJsonStructure([
                'data',
                'links',
                'meta',
            ]);
    }

    function test_unauthenticated_user_can_not_create_posts()
    {
        $this->postJson(route('posts.store'), Post::factory()->make()->toArray())
            ->assertUnauthorized()
            ->assertJsonPath('message', 'Unauthenticated.');
    }

    function test_authenticated_user_can_create_posts()
    {
        Storage::fake('public');
        Notification::fake();

        User::factory(10)->create();

        $auth = $this->authenticate();

        $post = Post::factory()->make()->toArray();
        $post['image'] = UploadedFile::fake()->image('post.jpg');
        $post['user_id'] = $auth['user']->uuid;

        $this->withHeaders(['Authorization' => 'Bearer ' . $auth['token']])
            ->postJson(route('posts.store'), $post)
            ->assertCreated()
            ->assertJson(function (AssertableJson $json) {
                return $json->hasAll(['uuid', 'user_id', 'description', 'image', 'updated_at', 'created_at']);
            });

        $otherUsers = User::query()->where('uuid', '!=', $auth['user']->uuid)->get();

        Notification::assertSentTo(
            $otherUsers,
            PostCreatedNotification::class
        );
    }

    public function test_user_can_only_delete_his_post()
    {
        $auth = $this->authenticate();
        $post = $auth['user']->posts()->save(Post::factory()->make());
        $this->assertDatabaseHas('posts', $post->toArray());
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $auth['token']])
            ->deleteJson(route('posts.delete', ['post' => $post->uuid]));
        $response->assertNoContent();
        $this->assertDatabaseMissing('posts', $post->toArray());
    }

    function test_user_can_not_delete_a_post_he_do_not_own()
    {
        $firstUser = $this->authenticate();
        $secondUser = $this->authenticate();
        $post = $firstUser['user']->posts()->save(Post::factory()->make());
        $this->withHeaders(['Authorization' => 'Bearer ' . $secondUser['token']])
            ->deleteJson(route('posts.delete', ['post' => $post->uuid]))
            ->assertForbidden();
    }

    function test_authenticated_user_can_react_to_posts()
    {
        $auth = $this->authenticate();

        User::factory()
            ->count('2')
            ->hasPosts(5)
            ->create();

        $post = Post::query()->inRandomOrder()->first();

        // like post
        $this->withHeaders(['Authorization' => 'Bearer ' . $auth['token']])
            ->postJson(route('posts.react', ['post' => $post->uuid]))
            ->assertNoContent();
        $this->assertDatabaseHas('post_likes', ['user_id' => $auth['user']->uuid, 'post_id' => $post->uuid]);

        // unlike post
        $this->withHeaders(['Authorization' => 'Bearer ' . $auth['token']])
            ->postJson(route('posts.react', ['post' => $post->uuid]))
            ->assertNoContent();
        $this->assertDatabaseMissing('post_likes', ['user_id' => $auth['user']->uuid, 'post_id' => $post->uuid]);
    }

    function test_authenticated_user_can_view_posts_likes()
    {
        $auth = $this->authenticate();

        User::factory()
            ->count('2')
            ->hasPosts(5)
            ->create();

        $this->withHeaders(['Authorization' => 'Bearer ' . $auth['token']])
            ->getJson(route('posts.likes', ['post' => Post::query()->inRandomOrder()->first()->uuid]))
            ->assertOk();
    }

    function test_posts_deletion_after_15_days()
    {
        Post::factory()
            ->for(User::factory()->create())
            ->create([
                'created_at' => now()->subDays(15),
            ]);

        $this->assertDatabaseCount('posts', 1);
        $this->artisan('model:prune', [
            '--model' => [Post::class],
        ]);
        $this->assertDatabaseCount('posts', 0);
    }
}
