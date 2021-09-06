# Dependencies and Requires With

### Dependency management

One of the features of Laravel Static is dependency management. This is best described with an example. Let's start with our editor library from the getting started section.

We want a system where we can have a few different types of editors, which are rendered in our site via a general editor.js file. 

Let's say we have a package that supplies TinyMCE. In that package we would define the library:


```php
Event::listen('laravel-static:register-libraries', function ($repository) {
    $repository->add(
        AssetLibrary::create('tinymce')
            ->publicPath(__DIR__.'/../public')
            ->withJs('tinymce.js')
            ->withCss('tinymce.css')
    );
});
```

Now we head back to our library we defined in getting started, but that now depends on our new TinyMCE library. We simply add that in as a requirement:

```php
Event::listen('laravel-static:register-libraries', function ($repository) {
    $repository->add(
        AssetLibrary::create('editor')
            ->publicPath(__DIR__.'/../public')
            ->withJs('editor.js')
            ->withCss('editor.css')
            ->dependsOn('tinymce')
    );
});
```

By adding our dependsOn, when the stylesheets or javascript files are rendered in our template, they are rendered before the actual library. So our scripts in this example would be:

- tinymce.js
- editor.js

And there's no need to require tinymce in your controller. Simply requiring editor will automatically in tinymce.

### Requiring With

So we just saw how to add a dependency, but what about the reverse? What if we want to always include a library that extends a base library. We'll extend our example above to better explain.

You've got TinyMCE and your base editor file going in, but you found a great plugin you want to try with TinyMCE. Generally you would have to add in the files with that plugin, then load them in your template and instantiate them when you create the editor. Using Laravel Static, you can simplify that process.

In your new TinyMCE plugin package:

```php
Event::listen('laravel-static:register-libraries', function ($repository) {
    $repository->add(
        AssetLibrary::create('tinymce-plugin')
            ->publicPath(__DIR__.'/../public')
            ->withJs('tinymce-plugin.js')
            ->withCss('tinymce-plugin.css')
            ->dependsOn('tinymce')
            ->requireWith('tinymce')
    );
});
```

Now whenever tinymce is required, our new tinymce-plugin is automatically added after it.

This should provide coverage for most needs, but sometimes you may need to do further altering, such as requiring based upon user access. For that we have a special event that is called whenever a library is added to the stack:

```php
Event::listen('laravel-static:library-required', function (HollyIT\LaravelStatic\AssetLibrary $library, HollyIT\LaravelStatic\RequiredLibraries $required) {
        
        
});
```

Now you can perform whatever logic is needed to alter the requires and the libraries.
