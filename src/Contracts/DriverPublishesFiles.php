<?php

namespace HollyIT\LaravelStatic\Contracts;

use HollyIT\LaravelStatic\AssetLibrary;

interface DriverPublishesFiles
{
    public function publish(AssetLibrary $library);

    public function unpublish(AssetLibrary $library);
}
