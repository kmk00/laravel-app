<?php


use App\Models\Channels;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('get request', function () {
    
    test('return an empty array if there are no channels', function () {
        $response = $this->getJson('/api/channels');
        $response->assertStatus(200)
                ->assertJsonCount(0);
    });

    
    test('get all channels', function () {
        
        Channels::factory()->count(3)->create();

        $response = $this->getJson('/api/channels');

        $response->assertStatus(200)
                ->assertJsonCount(3)
                ->assertJsonStructure([
                    '*' => ['id', 'name', 'clientsCount']
                ]);
    });
});

describe('post request', function () {
    
    test('create a new channel', function () {
        $channel = Channels::factory()->make();
        $response = $this->postJson('/api/channels', $channel->toArray());
        $response->assertStatus(200)
                ->assertJson(['message' => 'Channel created successfully']);

        $this->assertDatabaseHas('channels', [
            'name' => $channel->name,
            'clientsCount' => $channel->clientsCount
        ]);

    });

    test('throw error if name is empty', function () {
        $channel = Channels::factory()->make(['name' => '']);
        $response = $this->postJson('/api/channels', $channel->toArray());
        $response->assertStatus(422)
                ->assertJson(['message' => 'The name field is required.',
                            'errors' => ['name' => ['The name field is required.']]]);
    });

    test('throw error if clientsCount is empty', function () {
        $channel = Channels::factory()->make(['clientsCount' => '']);
        $response = $this->postJson('/api/channels', $channel->toArray());
        $response->assertStatus(422)
                ->assertJson(['message' => 'The clients count field is required.',
                            'errors' => ['clientsCount' => ['The clients count field is required.']]]);
    });

    test('throw error if name already exists', function () {
        $channel = Channels::factory()->make(['name' => 'Google', 'clientsCount' => 725]);
        $channel->save();

        $this->postJson('/api/channels', $channel->toArray());
        $response = $this->postJson('/api/channels', $channel->toArray());
        $response->assertStatus(400)
                ->assertJson(['error' => 'Channel with the same name already exists']);
    });

    test('throw error if clientsCount is less than 0', function () {
        $channel = Channels::factory()->make(['name' => 'Google', 'clientsCount' => -1]);
        $response = $this->postJson('/api/channels', $channel->toArray());
        $response->assertStatus(400)
                ->assertJson(['error' => 'clientsCount must be greater than 0']);
    });
});

describe('delete request', function () {


    test('delete existing channel', function () {
        $channel = Channels::factory()->create();
        $response = $this->deleteJson('/api/channels/' . $channel->id);
        $response->assertStatus(200)
                ->assertJson(['message' => 'Channel deleted successfully']);

        $this->assertDatabaseMissing('channels', [
            'name' => $channel->name,
            'clientsCount' => $channel->clientsCount
        ]);
    });
});

describe('put request', function () {
    

    
    test('update a channel', function () {
        $channel = Channels::factory()->create();
        $newChannel = Channels::factory()->make(['name' => 'Google', 'clientsCount' => 725]);
        $response = $this->putJson('/api/channels/' . $channel->id, $newChannel->toArray());
        $response->assertStatus(200)
                ->assertJson(['message' => 'Channel updated successfully']);

        $this->assertDatabaseHas('channels', [
            'name' => $newChannel->name,
            'clientsCount' => $newChannel->clientsCount
        ]);
    });

    test('return an error message if name is empty', function () {
        $channel = Channels::factory()->create();
        $newChannel = Channels::factory()->make(['name' => '']);
        $response = $this->putJson('/api/channels/' . $channel->id, $newChannel->toArray());
        $response->assertStatus(422)
                ->assertJson(['message' => 'The name field is required.',
                            'errors' => ['name' => ['The name field is required.']]]);
    });

    test('return an error message if clientsCount is empty', function () {
        $channel = Channels::factory()->create();
        $newChannel = Channels::factory()->make(['clientsCount' => '']);
        $response = $this->putJson('/api/channels/' . $channel->id, $newChannel->toArray());
        $response->assertStatus(422)
                ->assertJson(['message' => 'The clients count field is required.',
                            'errors' => ['clientsCount' => ['The clients count field is required.']]]);
    });

    test('return an error message if name already exists', function () {
        $channel1 = Channels::factory()->make(['name' => 'Google', 'clientsCount' => 725]);
        $channel2 = Channels::factory()->make(['name' => 'newGoogle', 'clientsCount' => 325]);

        $channel1->save();
        $channel2->save();

        $response = $this->putJson('/api/channels/' . $channel1->id, $channel2->toArray());
        $response->assertStatus(400)
                ->assertJson(['error' => 'Channel with the same name already exists']);
    });

    test('return an error message if clientsCount is less than 0', function () {
        $channel = Channels::factory()->create();
        $newChannel = Channels::factory()->make(['clientsCount' => -1]);
        $response = $this->putJson('/api/channels/' . $channel->id, $newChannel->toArray());
        $response->assertStatus(400)
                ->assertJson(['error' => 'clientsCount must be greater than 0']);
    });
    
});