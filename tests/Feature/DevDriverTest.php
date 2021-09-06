<?php

namespace HollyIT\LaravelStatic\Tests\Feature;

use HollyIT\LaravelStatic\AssetLibrary;
use HollyIT\LaravelStatic\Tests\TestCase;

class DevDriverTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setFileDriver('dev');
        $this->setupDefaultLibraries();
    }

    /** @test * */
    public function it_resolves_hot_file_urls()
    {
        $this->repository->add(
            AssetLibrary::create('test')
            ->publicPath(__DIR__ . '/../support/libraries/hot/public')
            ->withJs('hot.js')
        );

        $required = $this->repository->require(['test', 'core']);
        $this->assertStringContainsString('http://localhost:8080/hot.js', $required->scripts());
        $this->assertStringContainsString('http://localhost/_laravel_static/core/core.js?t=1', $required->scripts());
    }
}
