{!! '<' !!}?php namespace {!! studly_case($vendor) !!}\{!! studly_case($package) !!};

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
@if($views_flag)
        $this->loadViewsFrom(__DIR__.'/views', '{!! str_slug($package) !!}');
@endif
@if($publish_flag)
        $this->publishes([
            __DIR__.'/PATH/TO/ASSETS' => 'PATH/TO/DESTINATION',
        ], 'ASSET-GROUP-TAG');
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