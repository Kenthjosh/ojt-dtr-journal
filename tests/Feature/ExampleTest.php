<?php

test('the login page is accessible', function () {
    $response = $this->get('/intern/login');

    $response->assertStatus(200);
});
