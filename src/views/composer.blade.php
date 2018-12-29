{
    "name": "{!! $vendor !!}/{!! $package !!}",
    "description": "Description here.",
    "authors": [
        {
            "name": "{!! $vendor !!}",
            "email": "test@example.com"
        }
    ],
    "license": "MIT",
    "require": {
        "laravel/framework": "~{!! app()->version() !!}"
    },
    "autoload": {
        "psr-4": {
            "{!! studly_case($vendor) !!}\\{!! studly_case($package) !!}\\": "src/"
        }
    },
    "minimum-stability": "stable",
    "extra": {
        "laravel": {
            "providers": [
                "{!! studly_case($vendor) !!}\\{!! studly_case($package) !!}\\{!! studly_case($package) !!}ServiceProvider"
            ],
            "aliases": {
                "{!! studly_case($package) !!}": "{!! studly_case($vendor) !!}\\{!! studly_case($package) !!}\\Facades\\{!! studly_case($package) !!}"
            }
        }
    }
}
