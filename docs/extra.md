# Advanced

**Changing the locations of manifests and hot files.**

By default, Laravel Static will look in the public path you defined with your library for a mix-manifest.json or hot file. These files are the same ones generated by Laravel Mix. If you don't want these published to your public folders, you can change their location by calling manifestPath or hotPath on the Asset Library and supplying it with the absolute path, including the file name to each method.

It should be noted that the parsing is expecting files generated through Laravel Mix. If you are using some other bundler or manifest generator, then you will need to create a custom driver to handle parse those files.


**Customized Rendering**

When javascript tags and stylesheets are rendered, they are simply returning the HTML tags to include the associated files. At times, you may wish to take this further. For example, what if you have a core javascript library that must be instantiated before the child libraries are included? Well, to handle that asset libraries can define a callback or a view for javascript rendering and style sheet rendering.

```php
renderJsWith(callable | string | null $callbackOrView)

renderCssWith(callable | string | null $callbackOrView)
```

You can either supply a view name or a callable to either function. When supplying a callable the first parameter given in the callback will be an array containing the rendered lines to include files (ie: <script> tags for javascript). When using a view, you will have a $scripts array for javascript and a $styles array for style sheets. Both views are also passed the $library reference to the asset library.

