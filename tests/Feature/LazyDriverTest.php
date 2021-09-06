<?php

namespace HollyIT\LaravelStatic\Tests\Feature;

use HollyIT\LaravelStatic\Tests\TestCase;

class LazyDriverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupDefaultLibraries();
        $this->setFileDriver('lazy');
    }

    /** @test * */
    public function it_resolves_lazy_file_urls()
    {
        $required = $this->repository->require('admin-theme');
        $this->assertStringContainsString('http://localhost/_laravel_static/core/core.js?t=1', $required->scripts());
        $this->assertStringContainsString('http://localhost/_laravel_static/admin-theme/admin.js?t=1', $required->scripts());
    }

    /** @test */
    public function it_serves_js_files()
    {
        $response = $this->get('/_laravel_static/core/core.js?t=1');
        $response->assertOk();
        $response->assertHeader('content-type', 'text/javascript; charset=UTF-8');
        $this->assertStringContainsString('core.js', $response->content());
    }

    /** @test */
    public function it_serves_css_files()
    {
        $response = $this->get('/_laravel_static/core/core.css?t=1');
        $response->assertOk();
        $response->assertHeader('content-type', 'text/css; charset=UTF-8');
        $this->assertStringContainsString('core.css', $response->content());
    }
}
