# File Resolvers

File resolvers transform the files you supply through your asset libraries to the actual URL of their location. To
accommodate different situations, like development, a few drivers are provided out of the box. These should fit a vast
majority of the use cases, but if you need something custom, you can easily register your own drivers.

### The Drivers

#### File

The file driver is the default driver. It requires you publish your files to your public directory and serves them the
same way any other Laravel application does.

Publication of files through the File driver is done via an artisan command:

```bash
php artisan static-assets:publish
```

This will remove the library folder under the publish_to directory that is configured in the file driver (by default
public/static) and then copy the content you specified in publicPath to the new folder. Folders under publish_to are
named based upon the library name, transformed to kebab case.

Please note: You should not manually add anything into your publish_to directory as that can be rewritten during
publishing.

When you decide to remove a library or even remove the whole system, a static-assets:unpublish command is also
available.

#### Lazy

Lazy does exactly what it's name implies - it lazy loads the files. These are done via a special route, which gets
automatically registered when utilizing this driver.

When serving the files through Laravel, it will attempt to determine the mime type based off the file extension. That
way regular static items, such as fonts and images can be served via the lazy route. Caching tags are also set based off
the file modified time.

If you're running a low traffic site, the lazy driver is perfectly acceptable for production, but it does come with some
overhead. If you start noticing performance issues, you should consider switching over to the file driver.

#### Dev

The dev driver is an extension of the lazy extension meant for development. When a file is injected the driver will look
for a hot file if you're running a webpack devserver (ie: mix hot). If it is found it will then it will rewrite the URL
to utilize the dev server. If not then it passes the file on to lazy rewriting.

### Cache Busting

Laravel Static provides cache busting based off a mix-manifest file found inside your libraries public directory. You
can override this location per library. See the [next section](dependencies.md) for more details.

To help keep this as lightweight as possible, the found manifests are cached by default. You can set how long you want
these cached for via the config. When using the file driver, the cache is automatically cleared whenever publishing or
unpublishing assets. For other drivers, you can execute the static-assets:clear-cache artisan command.

Next up: [Dependencies and Requried With](dependencies.md)
