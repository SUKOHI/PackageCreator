{!! '<' !!}?php

namespace {!! studly_case($vendor) !!}\{!! studly_case($package) !!};

use Illuminate\Support\ServiceProvider;

class {!! studly_case($package) !!}ServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap the application events.
     *
     * {!! '@' !!}return void
     */
    public function boot()
    {
@if($route_flag)
        // Route
        $this->loadRoutesFrom(__DIR__.'/routes.php');

@endif
@if($views_flag)
        // View
        $this->loadViewsFrom(__DIR__.'/views', '{!! str_slug($package) !!}');

@endif
@if($migration_flag)
        // Migration
        $this->loadMigrationsFrom(__DIR__.'/migrations');

@endif
@if($translation_flag)
        // Translation
        $this->loadTranslationsFrom(__DIR__.'/translations', '{!! str_slug($package) !!}');

@endif
@if($publish_flag)
        // Publishing File
        $this->publishes([
            __DIR__.'/PATH/TO/ASSETS' => 'PATH/TO/DESTINATION',
        ], 'ASSET-GROUP-TAG');

@endif
@if($command_flag)
        // Command
        if ($this->app->runningInConsole()) {
            $this->commands([
                // {!! studly_case($package) !!}Command::class,
            ]);
        }

@endif
    }
    /**
     * Register the service provider.
     *
     * {!! '@' !!}return void
     */
    public function register()
    {
        $this->app->singleton('{!! str_slug($package) !!}', function()
        {
            return new {!! studly_case($package) !!};
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * {!! '@' !!}return array
     */
    public function provides()
    {
        return ['{!! str_slug($package) !!}'];
    }

}