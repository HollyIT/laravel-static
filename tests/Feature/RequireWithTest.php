<?php

namespace HollyIT\LaravelStatic\Tests\Feature;

use HollyIT\LaravelStatic\AssetLibrary;
use HollyIT\LaravelStatic\Tests\TestCase;

class RequireWithTest extends TestCase
{

    protected function setUp(): void
    {
        parent::setUp();
        $this->setupDefaultLibraries();
    }

    /** @test * */
    public function it_injects_when_required_with_is_set()
    {
        $this->repository->add(AssetLibrary::create('admin-child')
            ->withJs('admin-child.js')
            ->requireWith('admin-theme')
        );
        $this->assertRequiresOrder([
            'core',
            'admin-theme',
            'admin-child',
        ], $this->repository->require('admin-theme'));
    }
}
