# Introduction

This package provides a few classes you should familiarize yourself with. By design, it is even driven to make it possible for package authors to provide easier ways to supply static files with their packages. The event driven registration will allow you to define libraries if a consuming project is requiring this package. If it isn't, then it simply gets ignored.

###### HollyIT\LaravelStatic\StaticRepository

This is a repository to store all registered asset libraries your project contains.

###### HollyIT\LaravelStatic\AssetLibrary

This is the heart of it all. A library can contain javascript, stylesheets or both. You can also define the attributes required for each tag, such as a media tag when including a stylesheet.

AssetLibraries can also have dependencies. When an asset library requires another asset library, the system takes care to make sure the files are injected in the proper order.

###### HollyIT\LaravelStatic\AssetLibraryFileResolvers\FileResolver

The file resolver is what determines the URLs for your static assets. There are various drivers available, so you can utilize the method best for you. The following drivers are available out of the box, but you can also easily create your own.

- File
- Lazy
- Dev

###### HollyIT\LaravelStatic\RequiredLibraries

This is how you require your libraries and render them out in your Blade templates. In your controller you create a new RequiredLibraries, adding in any libraries you need and then passing it to your view for rendering.

Next up: [Getting started](getting-started.md)
