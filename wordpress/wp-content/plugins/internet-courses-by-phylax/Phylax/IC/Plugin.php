<?php
/**
 * internet-courses
 * Copyright 2018 phylax.pl Łukasz Nowicki
 */

namespace Phylax\IC;

use Phylax\IC\WordPress\AdminEnqueueScripts;
use Phylax\IC\WordPress\PostType;

/**
 * Class Plugin
 *
 * @package Phylax\IC
 */
class Plugin {

	protected $postType;

	/**
	 * Plugin constructor.
	 */
	public function __construct() {
		$this->postType = new PostType();
		if ( is_admin() ) {
			new AdminEnqueueScripts( $this->postType->getRegisteredPostType() );
		}
	}

}