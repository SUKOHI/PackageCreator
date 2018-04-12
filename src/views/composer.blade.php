{
    "name": "{!! $vendor !!}/{!! $package !!}",
    "description": "Your package description here.",
    "authors": [
        {
            "name": "{!! studly_case($vendor) !!}",
            "email": "test@example.com"
        }
    ],
    "require": {
        "laravel/framework": "~5.0"
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
