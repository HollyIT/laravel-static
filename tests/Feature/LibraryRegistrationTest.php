<?php

namespace HollyIT\LaravelStatic\Tests\Feature;

use Illuminate\Support\Facades\Event;
use HollyIT\LaravelStatic\AssetLibrary;
use HollyIT\LaravelStatic\Tests\TestCase;
use HollyIT\LaravelStatic\RequiredLibraries;

class LibraryRegistrationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        $this->setupDefaultLibraries();
    }

    /** @test * */
    public function it_registers_libraries()
    {
        $this->assertRequiresOrder(['core', 'admin-theme'], $this->repository->require('admin-theme'));
    }

    /** @test */
    public function it_allows_alteration_of_requires()
    {
        $this->repository->add(AssetLibrary::create('extra'));
        $this->repository->add(AssetLibrary::create('extra2')->dependsOn('extra'));
        Event::listen('laravel-static:library-required', function ($library, $requires) {
            if ($library->getName() === 'admin-theme') {
                $requires->require('extra2');
            }
        });

        $this->assertRequiresOrder([
            'core',
            'admin-theme',
            'extra',
            'extra2',
        ], $this->repository->require('admin-theme'));
    }


}
