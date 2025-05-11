<?php

use Database\Seeders\FeaturedPodcastSeeder;

it('can see home page', function () {
    $this->seed(FeaturedPodcastSeeder::class);
    $response = $this->get('/');
    $response->assertStatus(200);
});

it('can see software-development page', function () {
    $response = $this->get('/software-development');
    $response->assertStatus(200);
});

it('can see bike page', function () {
    if (app()->environment('testing') && !app()->environment('local')) {
        $this->markTestSkipped('Skipping bike page test in testing environment.');
    }
    $response = $this->get('/bike');
    $response->assertStatus(200);
});
