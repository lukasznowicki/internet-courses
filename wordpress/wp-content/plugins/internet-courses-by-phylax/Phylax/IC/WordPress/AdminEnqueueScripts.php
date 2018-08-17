<?php
/**
 * internet-courses
 * Copyright 2018 phylax.pl Łukasz Nowicki
 */

namespace Phylax\IC\WordPress;


use const Phylax\IC\PLUGIN_URL;

class AdminEnqueueScripts {

	protected $post_type;

	public function __construct( $post_type ) {
		$this->post_type = $post_type;
		add_action( 'admin_enqueue_scripts', [ $this, 'adminEnqueueScripts' ] );
	}

	public function adminEnqueueScripts( $hook ) {
		if ( 'post-new.php' !== $hook ) {
			return;
		}
		global $post;
		if ( $this->post_type !== $post->post_type ) {
			return;
		}
		wp_enqueue_style( 'ic-admin-style', PLUGIN_URL . 'assets/css/admin.min.css', [], NULL );
		wp_enqueue_script('ic-admin-script', PLUGIN_URL . 'assets/js/admin.min.js', ['jquery'], NULL, TRUE );
	}

}