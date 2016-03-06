<?php

namespace Sukohi\PackageCreator\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class PackageCreatorCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'package_creator:make {vendor_name} {package_name} {package_dir=packages} {--views} {--publish}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new own package';

	/**
	 * Package directory.
	 *
	 * @var string
	 */
	private $_dir;

	/**
	 * Vendor name.
	 *
	 * @var string
	 */
	private $_vendor;

	/**
	 * Package name.
	 *
	 * @var string
	 */
	private $_package;

	/**
	 * The filesystem instance.
	 *
	 * @var File
	 */
	private $_files;

	/**
	 * Whether need views or not.
	 *
	 * @var boolean
	 */
	private $_views_flag = false;
	/**
	 * Whether need publishing.
	 *
	 * @var boolean
	 */
	private $_publish_flag = false;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

		$this->_files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$this->init();
		$this->createDirectories();
		$this->createFiles();
		$this->resultMessage();
    }

	private function init() {

		$this->_dir = strtolower($this->argument('package_dir'));
		$this->_vendor = strtolower($this->argument('vendor_name'));
		$this->_package = strtolower($this->argument('package_name'));
		$this->_views_flag = $this->option('views');
		$this->_publish_flag = $this->option('publish');

	}

	private function createDirectories() {

		foreach ($this->getDirectoryPaths() as $key => $path) {

			if(!$this->_files->isDirectory($path)) {

				if(!$this->_files->makeDirectory($path, 0777, true, true)) {

					$this->error('Creating a directory failed.');
					die();

				}

			} else {

				if($key == 'package' && !$this->confirm('Package Folder already exists. Overwrite? [y|N]')) {

					$this->info('Canceled.');
					die();

				}

			}

		}

	}

	private function createFiles() {

		$paths = $this->getFilePaths();

		foreach ($paths as $key => $path) {

			$contents = view('package-creator::'. $key, [
				'vendor' => $this->_vendor,
				'package' => $this->_package,
				'views_flag' => $this->_views_flag,
				'publish_flag' => $this->_publish_flag
			])->render();

			if(!$this->_files->put($path, $contents)) {

				$this->error('Creating a ServiceProvider file failed.');
				die();

			}

		}

	}

	private function getDirectoryPaths() {

		$vendor = $this->getVendorNameStudly();
		$package = $this->getPackageNameStudly();
		$paths = [
			'package_base' => base_path($this->_dir),
			'vendor' => base_path($this->_dir .'/'. $vendor),
			'package' => base_path($this->_dir .'/'. $vendor .'/'. $package),
			'package_src' => base_path($this->_dir .'/'. $vendor .'/'. $package .'/src'),
			'package_facades' => base_path($this->_dir .'/'. $vendor .'/'. $package .'/src/Facades')
		];

		if($this->_views_flag) {

			$paths['package_view'] = base_path($this->_dir .'/'. $vendor .'/'. $package .'/src/views');

		}

		return $paths;

	}

	private function getDirectoryPath($key) {

		return $this->getDirectoryPaths()[$key];

	}

	private function getFilePaths() {

		$package = $this->getPackageNameStudly();
		$package_path = $this->getDirectoryPath('package');
		$src_path = $this->getDirectoryPath('package_src');
		$facades_path = $this->getDirectoryPath('package_facades');

		return [
			'class' => $src_path .'/'. $package .'.php',
			'service_provider' => $src_path .'/'. $package .'ServiceProvider.php',
			'facades' => $facades_path .'/'. $package .'.php',
			'composer' => $package_path .'/composer.json'
		];

	}

	private function getVendorNameStudly() {

		return studly_case($this->_vendor);

	}

	private function getPackageNameStudly() {

		return studly_case($this->_package);

	}

	private function resultMessage() {

		$this->info("New package generated.\n");
		$vendor = $this->getVendorNameStudly();
		$package = $this->getPackageNameStudly();
		$service_provider = $vendor .'\\'. $package .'\\'. $package .'ServiceProvider::class';
		$alias = "'". $package ."' => ". $vendor ."\\". $package ."\\" ."Facades". "\\". $package ."::class";
		$comment = view('package-creator::result_message', [
			'package_dir' => $this->_dir,
			'vendor' => $this->getVendorNameStudly(),
			'package' => $this->getPackageNameStudly(),
			'service_provider' => $service_provider,
			'alias' => $alias
		])->render();
		$this->comment($comment);

	}

}
