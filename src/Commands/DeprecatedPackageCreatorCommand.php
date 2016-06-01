<?php

namespace Sukohi\PackageCreator\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class DeprecatedPackageCreatorCommand extends Command
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
	protected $description = '(Deprecated) Use `make:package`';

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
		$this->error('This command is already deprecated. Please use `make:package` instead.');
	}
}

