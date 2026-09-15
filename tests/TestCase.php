<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * The users table has a non-nullable foreign key to user_statuses
     * (defaulting to id 1), so lookup tables must be seeded before any
     * user can be created via the factory.
     */
    protected bool $seed = true;
}
