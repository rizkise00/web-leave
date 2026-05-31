<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_root_redirect_ke_login(): void
    {
        $this->get('/')->assertRedirect('/login');
    }
}
