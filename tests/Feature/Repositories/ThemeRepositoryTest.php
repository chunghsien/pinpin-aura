<?php

declare(strict_types=1);

namespace Tests\Feature\Repositories;

use App\Models\InstalledTheme;
use App\Repositories\ThemeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    public function testGetActiveSiteTheme()
    {
        $slug = 'test-theme';
        $model = InstalledTheme::create([
            'use_type' => 'site',
            'name' => $slug,
            'slug' => $slug,
            'is_active' => TRUE,
            'installed_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $repo = new ThemeRepository();
        $theme = $repo->getActiveSiteTheme();

        $this->assertNotNull($theme);
        $this->assertEquals('test-theme', $theme->slug);
    }
}
