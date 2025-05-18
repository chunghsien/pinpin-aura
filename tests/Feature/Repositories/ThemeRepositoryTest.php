<?php

namespace Tests\Feature\Repositories;

use Tests\TestCase;
use App\Models\InstalledTheme;
use App\Repositories\ThemeRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
            'is_active' => true,
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
