<?php namespace Sukohi\PackageCreator;

use Illuminate\Support\ServiceProvider;

class PackageCreatorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = true;

	/**
	 * Bootstrap the application events.
	 *
	 * @return void
	 */
	public function boot()
	{
		$this->loadViewsFrom(__DIR__.'/views', 'package-creator');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->app->singleton('command.package_creator:make', function ($app) {

			return $app['Sukohi\PackageCreator\Commands\PackageCreatorCommand'];

		});
		$this->commands('command.package_creator:make');
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return ['package-creator'];
	}

}