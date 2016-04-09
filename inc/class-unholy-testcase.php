<?php

use Masterminds\HTML5;

abstract class Unholy_Testcase extends WP_UnitTestcase {

	public function setUp() {
		parent::setUp();
		$this->setup_permalink_structure();
		$_SERVER['REQUEST_METHOD'] = 'GET';
	}

	protected function get_permalink_as_dom( $path ) {
		ob_start();
		$this->go_to( $path );
		$this->load_template();
		$output = ob_get_contents();
		ob_end_flush();

		$html5 = new HTML5();
		$dom = $html5->loadHTML($output);

		return $dom;
	}

	protected function get_feed_as_dom( $path ) {
		ob_start();
		$this->go_to( $path );
		@$this->do_feed();
		$output = ob_get_contents();
		ob_end_flush();

		$doc = new DOMDocument;
		$doc->loadXML( $output );

		return $doc;
	}

	/**
	 * Copy-pasta of wp-includes/template-loader.php
	 */
	private function load_template() {
		do_action( 'template_redirect' );

		$template = false;
		if	 ( is_404()			&& $template = get_404_template()			) :
		elseif ( is_search()		 && $template = get_search_template()		 ) :
		elseif ( is_front_page()	 && $template = get_front_page_template()	 ) :
		elseif ( is_home()		   && $template = get_home_template()		   ) :
		elseif ( is_post_type_archive() && $template = get_post_type_archive_template() ) :
		elseif ( is_tax()			&& $template = get_taxonomy_template()	   ) :
		elseif ( is_attachment()	 && $template = get_attachment_template()	 ) :
			remove_filter('the_content', 'prepend_attachment');
		elseif ( is_single()		 && $template = get_single_template()		 ) :
		elseif ( is_page()		   && $template = get_page_template()		   ) :
		elseif ( is_category()	   && $template = get_category_template()	   ) :
		elseif ( is_tag()			&& $template = get_tag_template()			) :
		elseif ( is_author()		 && $template = get_author_template()		 ) :
		elseif ( is_date()		   && $template = get_date_template()		   ) :
		elseif ( is_archive()		&& $template = get_archive_template()		) :
		elseif ( is_comments_popup() && $template = get_comments_popup_template() ) :
		elseif ( is_paged()		  && $template = get_paged_template()		  ) :
		else :
			$template = get_index_template();
		endif;
		/**
		 * Filter the path of the current template before including it.
		 *
		 * @since 3.0.0
		 *
		 * @param string $template The path of the template to include.
		 */

		if ( $template = apply_filters( 'template_include', $template ) ) {
			$template_contents = file_get_contents( $template );
			$included_header = $included_footer = false;
			if ( false !== stripos( $template_contents, 'get_header();' ) ) {
				do_action( 'get_header', null );
				locate_template( 'header.php', true, false );
				$included_header = true;
			}
			include( $template );
			if ( false !== stripos( $template_contents, 'get_footer();' ) ) {
				do_action( 'get_footer', null );
				locate_template( 'footer.php', true, false );
				$included_footer = true;
			}
			if ( $included_header && $included_footer ) {
				global $wp_scripts;
				$wp_scripts->done = array();
			}
		}

		return;
	}

	/**
	 * Copy pasta of the RSS feed loader
	 */
	private function do_feed() {
		global $posts, $post, $wp_did_header, $wp_query, $wp_rewrite, $wpdb, $wp_version, $wp, $id, $comment, $user_ID;

		$feed = get_query_var( 'feed' );

		// Remove the pad, if present.
		$feed = preg_replace( '/^_+/', '', $feed );

		if ( $feed == '' || $feed == 'feed' )
			$feed = get_default_feed();

		$hook = 'do_feed_' . $feed;
		if ( ! has_action( $hook ) )
			wp_die( __( 'ERROR: This is not a valid feed template.' ), '', array( 'response' => 404 ) );

		/**
		 * Fires once the given feed is loaded.
		 *
		 * The dynamic hook name, $hook, refers to the feed name.
		 *
		 * @since 2.1.0
		 *
		 * @param bool $is_comment_feed Whether the feed is a comment feed.
		 */
		if ( 'do_feed_rss2' === $hook ) {
			if ( is_array( $wp_query->query_vars ) ) {
				extract( $wp_query->query_vars, EXTR_SKIP );
			}
			include ABSPATH . WPINC . '/feed-rss2.php';
		} else {
			do_action( $hook, $wp_query->is_comment_feed );
		}
	}

	/**
	 * Set up permalink structure
	 */
	private function setup_permalink_structure() {
		global $wp_rewrite;

		$structure = get_option( 'permalink_structure' );

		$wp_rewrite->init();
		$wp_rewrite->set_permalink_structure( $structure );

		create_initial_taxonomies();

		$wp_rewrite->flush_rules();
	}

}
