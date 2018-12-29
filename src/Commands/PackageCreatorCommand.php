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
    protected $signature = 'make:package';

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
	private $_dir = 'packages';

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
	 * Whether need route.
	 *
	 * @var boolean
	 */
	private $_route_flag = false;

	/**
	 * Whether need command.
	 *
	 * @var boolean
	 */
	private $_command_flag = false;

	/**
	 * Whether need migration.
	 *
	 * @var boolean
	 */
	private $_migration_flag = false;

	/**
	 * Whether need translation.
	 *
	 * @var boolean
	 */
	private $_translation_flag = false;

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
		$result = $this->init();

		if(!$result) {

            $this->warn('Canceled.' ."\n");
            die();

        }

        $this->createDirectories();
        $this->createFiles();
        $this->resultMessage();
        $this->comment('Done!' ."\n");
    }

	private function init() {

        $required_questions = [
            '_vendor' => 'Vendor Name? (e.g. your-name)',
            '_package' => 'Package Name? (e.g. package-name)',
            '_dir' => 'Package Directory? (Default: packages)',
        ];

        foreach ($required_questions as $key => $required_question) {

            $answer = strtolower($this->ask($required_question));

            if(empty($answer)) {

                if($key === '_dir') {

                    continue;

                }

                return false;

            }

            $this->{$key} = $answer;

        }

        $optional_questions = [
            '_publish_flag' => 'Publish File(s)?',
            '_views_flag' => 'Load View(s)?',
            '_route_flag' => 'Load Route(s)?',
            '_command_flag' => 'Load Command(s)?',
            '_migration_flag' => 'Load Migration(s)?',
            '_translation_flag' => 'Load Translation(s)?',
        ];

        foreach ($optional_questions as $key => $optional_question) {

            $this->{$key} = $this->confirm($optional_question);

        }

		return true;

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
				'publish_flag' => $this->_publish_flag,
				'command_flag' => $this->_command_flag,
				'route_flag' => $this->_route_flag,
				'migration_flag' => $this->_migration_flag,
				'translation_flag' => $this->_translation_flag,
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

		$additional_path_keys = [
		    '_views_flag' => 'views',
            '_migration_flag' => 'migrations',
            '_translation_flag' => 'translations',
        ];

        foreach ($additional_path_keys as $key => $path_key) {

            if($this->{$key}) {

                $paths[$path_key] = base_path($this->_dir .'/'. $vendor .'/'. $package .'/src/'. $path_key);

            }

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

		$service_provider = view('package-creator::message.service_provider')->with([
            'package' => $this->getPackageNameStudly(),
            'vendor' => $this->getVendorNameStudly(),
        ])->render();
		$alias = view('package-creator::message.alias')->with([
            'package' => $this->getPackageNameStudly(),
            'vendor' => $this->getVendorNameStudly(),
        ])->render();
        $composer_autoload = view('package-creator::message.composer_autoload')->with([
            'package' => $this->getPackageNameStudly(),
            'package_dir' => $this->_dir,
            'vendor' => $this->getVendorNameStudly(),
        ])->render();

        $this->comment('******************************');
        $this->comment('*   New package generated!   *');
        $this->comment('******************************'. "\n");
        $this->line('Now You need to add the next lines to `/config/app.php`'. "\n");
        $this->info('1) '. $service_provider);
        $this->info('2) '. $alias. "\n");
        $this->line('Also add the next to `/composer.json` in "autoload" > "psr-4"'. "\n");
        $this->info('3) '. $composer_autoload ."\n");
        $this->line('Execute the next commands.'. "\n");
        $this->info('4) composer dumpautoload -o');
        $this->info('5) php artisan config:clear'. "\n");

	}

}
