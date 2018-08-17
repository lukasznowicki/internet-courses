<?php
/**
 * internet-courses
 * Copyright 2018 phylax.pl Łukasz Nowicki
 */

namespace Phylax\IC\WordPress;

use const Phylax\IC\TD;

/**
 * Class PostType
 *
 * @package Phylax\IC\WordPress
 */
class PostType {

	protected $mb_after_title = 'mb_after_title';
	protected $post_type;

	/**
	 * PostType constructor.
	 */
	public function __construct() {
		$this->post_type = _x( 'course', 'Course post type name', TD );
		add_action( 'edit_form_after_title', [ $this, 'editFormAfterTitle' ] );
		add_action( 'init', [ $this, 'init' ] );
	}

	/**
	 * @return string
	 */
	public function getRegisteredPostType() {
		return $this->post_type;
	}

	public function editFormAfterTitle() {
		global $post, $wp_meta_boxes;
		do_meta_boxes( get_current_screen(), $this->mb_after_title, $post );
		unset( $wp_meta_boxes[ $post->post_type ][ $this->mb_after_title ] );
	}

	public function courseContent( $post ) {
		?>
		<div id="internet_course_metabox">
			<div id="icm_source_list">
				<h3><?php echo __( 'Click or drag source to the right', TD ); ?></h3>
				<ul>

				</ul>
				<div id="icm_source_view">
					<label for="icm_source_post"><input type="checkbox"
														id="icm_source_post"> <?php echo __( 'Posts', TD ); ?>
					</label>
					<label for="icm_source_page"><input type="checkbox"
														id="icm_source_page"> <?php echo __( 'Pages', TD ); ?>
					</label>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return void
	 */
	public function metaBoxCallback( \WP_Post $post ) {
		add_meta_box( 'course_content', __( 'Course content', TD ), [
			$this,
			'courseContent',
		], $post->post_type, $this->mb_after_title, 'high' );
	}

	/**
	 * Register post type
	 *
	 * @return void
	 */
	public function init() {
		$register = register_post_type( $this->post_type, [
			'labels'               => [
				'name'                  => _x( 'Courses', 'Label name of the custom Course post type', TD ),
				'singular_name'         => _x( 'Course', 'Label singular name of the custom Course post type', TD ),
				'add_new'               => _x( 'Add new', 'course', TD ),
				'add_new_item'          => __( 'Add new course', TD ),
				'edit_item'             => _x( 'Edit', 'course', TD ),
				'new_item'              => _x( 'New', 'course', TD ),
				'view_item'             => _x( 'View', 'course', TD ),
				'view_items'            => _x( 'View all', 'courses', TD ),
				'search_items'          => _x( 'Search', 'course', TD ),
				'not_found'             => __( 'No courses found', TD ),
				'not_found_in_trash'    => __( 'No courses found in trash', TD ),
				'all_items'             => __( 'All courses', TD ),
				'archives'              => __( 'Course archives', TD ),
				'attributes'            => __( 'Course attributes', TD ),
				'insert_into_item'      => __( 'Insert into course', TD ),
				'uploaded_to_this_item' => __( 'Uploaded to this course', TD ),
				'featured_image'        => __( 'Featured image', TD ),
				'set_featured_image'    => __( 'Set featured image', TD ),
				'remove_featured_image' => __( 'Remove featured image', TD ),
				'use_featured_image'    => __( 'Use as featured image', TD ),
				'menu_name'             => _x( 'Courses', 'Menu name', TD ),
				'filter_items_list'     => __( 'Filter courses list', TD ),
				'items_list_navigation' => __( 'Courses list navigation', TD ),
				'items_list'            => __( 'Courses list', TD ),
				'name_admin_bar'        => _x( 'Course', 'in admin bar', TD ),
			],
			'description'          => __( 'List of available courses' ),
			'public'               => TRUE,
			'menu_position'        => 20,
			'menu_icon'            => 'dashicons-slides',
			'supports'             => [
				'title',
				'editor',
				'author',
				'thumbnail',
			],
			'register_meta_box_cb' => [ $this, 'metaBoxCallback' ],
			'has_archive'          => TRUE,
			'delete_with_user'     => FALSE,
		] );
		if ( is_a( $register, 'WP_Error' ) ) {
			if ( \WP_DEBUG ) {
				echo 'Internet courses cannot register required post type, this is not recoverable, exiting.';
			}
			exit;
		}
	}

}