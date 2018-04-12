# PackageCreator
A Laravel package to create own package.
(This is for Laravel 5+)

# Installation

Execute composer command.

    composer require sukohi/package-creator:2.*

Your Laravel's version is 5.5+, it is done!

Register the service provider in app.php

    'providers' => [
        ...Others...,  
        Sukohi\PackageCreator\PackageCreatorServiceProvider::class,
    ]

Now you should be able to execute `php artisan make:package` command.

# Basic usage

`php artisan make:package vendor_name package_name [package_dir=packages]`

*Arguments*

* 1st: Vendor name. I suppose it's your name.
* 2nd: Package name you want to create. If your package name consists of multiple words, it needs to be hyphen separated.
* 3rd: Packages directory name you'd like to put new package. (Optional, Default: packages)

e.g.)  
`php artisan make:package sukohi my-package [package_dir]`

After calling `make:package` command, some instructions will appear.
So please follow it.

# Options

`--views`

If your package needs to use own views, add this option.  

`--publish`

If your package needs to publish files like configurations or migrations, add this option.


# License

This package is licensed under the MIT License.

Copyright 2016 Sukohi Kuhoh