<?php

namespace HollyIT\LaravelStatic\Http\Controllers;

use GuzzleHttp\Psr7\MimeType;
use Illuminate\Routing\Controller;
use HollyIT\LaravelStatic\StaticRepository;

class StaticController extends Controller
{
    public function handle($library, $path, StaticRepository $repository)
    {
        $library = $repository->findFromPath($library);
        if (! $library) {
            abort(404);
        }

        $file = realpath($library->getPublicPath()) . '/' .  $path;
        if (! file_exists($file)) {
            abort(404);
        }

        return response(
            file_get_contents($file),
            200,
            [
                'Content-Type' => MimeType::fromFilename($file) ?? 'text/plain',
            ]
        )->setLastModified(\DateTime::createFromFormat('U', (string) filemtime($file)));
    }
}
