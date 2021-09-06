<?php

namespace HollyIT\LaravelStatic\Tests\Feature;

use Illuminate\Support\Facades\File;
use HollyIT\LaravelStatic\Tests\TestCase;

class FilePublishTest extends TestCase
{
    protected string $tempPath = '';

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupDefaultLibraries();
        $this->tempPath = __DIR__ . '/../temp';
        config()->set('laravel-static.drivers.file.publish_to', $this->tempPath);
        $this->setFileDriver('file');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        //   File::cleanDirectory($this->tempPath);
    }

    /** @test */
    public function it_publishes_files()
    {
        $this->artisan('static-assets:publish');
        $this->assertFileExists(__DIR__ . '/../temp/core/core.js');
        $this->assertFileExists(__DIR__ . '/../temp/admin-theme/admin.js');
        File::cleanDirectory(config('laravel-static.drivers.file.publish_to'));
    }

    /** @test */
    public function it_unpublishes_files()
    {
        $this->artisan('static-assets:publish');
        $this->assertFileExists(__DIR__ . '/../temp/core/core.js');
        $this->assertFileExists(__DIR__ . '/../temp/admin-theme/admin.js');

        $this->artisan('static-assets:unpublish admin-theme');
        $this->assertDirectoryDoesNotExist(__DIR__ . '/../temp/admin-theme');

        $this->artisan('static-assets:unpublish --all');
        $this->assertDirectoryDoesNotExist(__DIR__ . '/../temp/core');
    }
}
