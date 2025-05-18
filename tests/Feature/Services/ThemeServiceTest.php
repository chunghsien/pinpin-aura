<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\ThemeService;
use App\Repositories\ThemeRepositoryInterface;
use App\Models\InstalledTheme;
use Mockery;

class ThemeServiceTest extends TestCase
{
    public function testGetLayoutWithActiveTheme()
    {
        $mockTheme = new InstalledTheme(['slug' => 'custom-theme']);

        $mockRepo = Mockery::mock(ThemeRepositoryInterface::class);
        $mockRepo->shouldReceive('getActiveSiteTheme')
            ->once()
            ->andReturn($mockTheme);

        $service = new ThemeService($mockRepo);
        $this->assertEquals('custom-theme::layouts.app', $service->getLayout());
    }

    public function testGetLayoutWithNoActiveTheme()
    {
        $mockRepo = Mockery::mock(ThemeRepositoryInterface::class);
        $mockRepo->shouldReceive('getActiveSiteTheme')
            ->once()
            ->andReturn(null);

        $service = new ThemeService($mockRepo);
        $this->assertEquals('default::layouts.app', $service->getLayout());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
