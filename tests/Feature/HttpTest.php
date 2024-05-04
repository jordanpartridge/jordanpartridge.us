<?php

it('can see home page', function () {
    $response = $this->get('/');
    $response->assertStatus(200);
});

it('can see engineering page', function () {
    $response = $this->get('/engineering');
    $response->assertStatus(200);
});

it('can see bike page', function () {
    $response = $this->get('/bike');
    $response->assertStatus(200);
});
