# Installation

Installation is the same as any other package:

```shell
composer require hollyit/laravel-static
```

### Configuration

Laravel Static ships with a default configuration, which should work for most needs. If you want to further configure things, then you can publish the configuration:

```shell
php artisan vendor:publish --provider="HollyIT\LaravelStatic\LaravelStaticServiceProvider" --tag="config"
```

The configuration file is fully documented, so feel free to explore the options available.

### Registering your first library

To keep things as lightweight as possible, library registration is called from an event you can register in your EventServiceProvider. Let's look at how a simple library might be registered:

```php
        Event::listen('laravel-static:register-libraries', function ($repository) {
            $repository->add(
                AssetLibrary::create('editor')
                    ->publicPath(__DIR__.'/../public')
                    ->withJs('editor.js')
                    ->withCss('editor.css')
            );
```
The first thing we are doing is calling the static create() method, which is supplied with the name of our library. After that we set the path of our public directory, which is the compiled assets. Once that is complete with add a Javascript file and CSS file. For brevity, we are adding only one of each.

Now we need a way to get these into our templates. In your controller you need to create the RequiredLibraries and pass it to your template:

_Controller:_
```php
public function handle( \HollyIT\LaravelStatic\StaticRepository $repository) 
{
  $libraries->require('editor')
  return view('layout', ['libraries' => $repository->require('editor')])
}
```
So we got the RequiredLibraries class being injected, but how do we get them into our template? Well we would simply edit the file:

_layout.blade.php_

```html
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    {!! $libraries->styles() !!}
</head>
<body>
[ all my html stuff ]

{!! $libraries->scripts() !!}
</body>
</html>
```

This is the basics of getting things going, but it won't work yet. Continue on to the next section about file resolvers to see how to make it all work.

Next up: [File Resolvers](file-resolvers.md)

