<?php

namespace Tests\Unit;

use App\Http\Middleware\PostOwner;
use Illuminate\Http\Request;
use Mockery;
use App\Services\PostService;
use PHPUnit\Framework\TestCase;
use Illuminate\Database\Eloquent\Collection;

class MainTest extends TestCase
{
    public function testThatTrueIsTrue()
    {
        $this->assertTrue(true);
    }

    public function testPostServiceReturningCollection()
    {
        $mockedPostService = Mockery::mock(PostService::class);
        $mockedPostService->shouldReceive('list')->once();
        $this->assertInstanceOf(Collection::class, $mockedPostService->list());
    }
}
