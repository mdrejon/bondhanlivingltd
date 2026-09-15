<?php

namespace Tests;

use App\Support\PublicHotelContext;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // PublicHotelContext is a static, request-scoped holder — fine in
        // production (fresh PHP process per request) but the test client makes
        // multiple requests per process, so a hotel-prefixed request in one test
        // could otherwise leak into the next.
        PublicHotelContext::clear();
    }
}
