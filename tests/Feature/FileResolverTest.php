<?php

namespace HollyIT\LaravelStatic\Tests\Feature;

use HollyIT\LaravelStatic\Tests\TestCase;

class FileResolverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupDefaultLibraries();
        $this->setFileDriver('file');
    }

    /** @test */
    public function it_resolves_urls_via_file_driver()
    {
        $required = $this->repository->require('admin-theme');
        $this->assertStringContainsString('/static/core/core.js?t=1', $required->scripts());
        $this->assertStringContainsString('/static/admin-theme/admin.js?t=1', $required->scripts());
    }

    /** @test */
    public function it_resolves_styles_via_file_driver()
    {
        $required = $this->repository->require('admin-theme');
        $this->assertStringContainsString('/static/core/core.css?t=1', $required->styles());
        $this->assertStringContainsString('/static/admin-theme/admin.css?t=1', $required->styles());
        $this->assertStringContainsString('rel="stylesheet"', $required->styles());
    }
}
