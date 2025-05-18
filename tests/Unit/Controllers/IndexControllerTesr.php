<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Services\ThemeService;
use App\Http\Controllers\Controller;
use Mockery;

class DummySiteController extends Controller
{
    public function query()
    {
        // 不需要實作，僅為了測試 getLayout()
    }
}

class SiteControllerTest extends TestCase
{
    public function testGetLayoutUsingAppHelper()
    {
        // 1. Mock ThemeService 行為
        $mockService = Mockery::mock(ThemeService::class);
        $mockService->shouldReceive('getLayout')
            ->once()
            ->andReturn('mocked-theme::layouts.app');

        // 2. 使用 Laravel Container 替換 ThemeService
        $this->app->instance(ThemeService::class, $mockService);

        // 3. 測試 Dummy Controller
        $controller = new DummySiteController();
        $result = $this->invokeMethod($controller, 'getLayout');
        $this->assertEquals('mocked-theme::layouts.app', $result);
    }

    // 4. Helper 方法：呼叫 protected/private 方法
    protected function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
