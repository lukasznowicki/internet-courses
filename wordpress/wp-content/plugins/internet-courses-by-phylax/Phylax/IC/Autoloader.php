<?php

namespace Phylax\IC;

/**
 * Class Autoloader
 *
 * @package Phylax\IC
 */
class Autoloader {

	/**
	 * @var array
	 */
	protected $namespaces = [];

	/**
	 * @var bool
	 */
	public $error = FALSE;

	/**
	 * @return bool
	 */
	public function register() {
		try {
			spl_autoload_register( [ $this, 'loadClass' ] );
		} catch (\Exception $exception) {
			if ( \WP_DEBUG ) {
				echo '<p>Runtime error while registering spl autoloader in Internet Courses plugin.</p>';
			}
			$this->error = TRUE;
		}

		return TRUE;
	}

	/**
	 * @param $prefix
	 * @param $dir
	 * @param bool $prepend
	 *
	 * @return bool
	 */
	public function addNamespace( $prefix, $dir, $prepend = FALSE ) {
		$prefix = trim( $prefix, '\\' ) . '\\';
		$dir    = rtrim( $dir, DS ) . '/';
		if ( isset( $this->namespaces[ $prefix ] ) === FALSE ) {
			$this->namespaces[ $prefix ] = [];
		}
		if ( $prepend ) {
			array_unshift( $this->namespaces[ $prefix ], $dir );
		} else {
			array_push( $this->namespaces[ $prefix ], $dir );
		}

		return TRUE;
	}

	/**
	 * @param $class
	 *
	 * @return bool|string
	 */
	public function loadClass( $class ) {
		$prefix = $class;
		while ( FALSE !== $pos = strrpos( $prefix, '\\' ) ) {
			$prefix         = substr( $class, 0, $pos + 1 );
			$relative_class = substr( $class, $pos + 1 );
			$mapped_file    = $this->loadMappedFile( $prefix, $relative_class );
			if ( $mapped_file ) {
				return $mapped_file;
			}
			$prefix = rtrim( $prefix, '\\' );
		}
		$this->error = TRUE;

		return FALSE;
	}

	/**
	 * @param $prefix
	 * @param $relative_class
	 *
	 * @return bool|string
	 */
	protected function loadMappedFile( $prefix, $relative_class ) {
		if ( isset( $this->namespaces[ $prefix ] ) === FALSE ) {
			return FALSE;
		}
		foreach ( $this->namespaces[ $prefix ] as $base_dir ) {
			$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';
			if ( $this->fileCall( $file ) ) {
				return $file;
			}
		}
		$this->error = TRUE;

		return FALSE;
	}

	/**
	 * @param $file
	 *
	 * @return bool
	 */
	protected function fileCall( $file ) {
		if ( is_readable( $file ) ) {
			require_once $file;

			return TRUE;
		}
		$this->error = TRUE;

		return FALSE;
	}

}