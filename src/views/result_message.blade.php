Now You need to add the next lines to `{!! config_path('app.php') !!}`

1) {!! $service_provider !!}
2) {!! $alias !!}

Also add the next to `{!! base_path('composer.json') !!}`

3) "{!! $vendor !!}\\{!! $package !!}\\": "{!! $package_dir !!}/{!! $vendor !!}/{!! $package !!}/src"

e.g.)
"autoload": {
  "psr-4": {
    "App\\": "app/",
    "{!! $vendor !!}\\{!! $package !!}\\": "{!! $package_dir !!}/{!! $vendor !!}/{!! $package !!}/src"
  }
}

At last call the next commands.

4) composer dumpautoload -o
5) php artisan config:cache