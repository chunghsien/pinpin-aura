<?php

declare(strict_types=1);

namespace Tests\Feature\Services;

use App\Models\InstalledTheme;
use App\Repositories\ThemeRepositoryInterface;
use App\Services\ThemeService;
use Mockery;
use Tests\TestCase;

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
            ->andReturn(NULL);

        $service = new ThemeService($mockRepo);
        $this->assertEquals('default::layouts.app', $service->getLayout());
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
