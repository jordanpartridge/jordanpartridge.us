<?php

use function Pest\Laravel\get;

it('can access services overview page', function () {
    get('/services')
        ->assertStatus(200)
        ->assertSee('Laravel Development Services', false);
});

it('can access code audit service page', function () {
    get('/services/code-audit')
        ->assertStatus(200)
        ->assertSee('Laravel Code Audit Service');
});

it('can access performance optimization service page', function () {
    get('/services/performance-optimization')
        ->assertStatus(200)
        ->assertSee('Laravel Performance Optimization');
});

it('can access custom development service page', function () {
    get('/services/custom-development')
        ->assertStatus(200)
        ->assertSee('Laravel Custom Development');
});

it('can access work with me page', function () {
    get('/work-with-me')
        ->assertStatus(200)
        ->assertSee('Work With Me');
});
