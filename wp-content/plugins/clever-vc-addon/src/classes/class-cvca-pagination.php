<?php
/**
 * CVCA_Pagination
 *
 * @package    Zoo_Theme\Lib\Main
 */
class CVCA_Pagination
{
	/**
	 * WP_Query
	 */
	protected $query;

	/**
	 * Constructor
	 */
	function __construct(\WP_Query $query)
	{
		$this->query = $query;
	}

	/**
	 * AJAX render queried posts
	 *
	 * @internal    Used as a callback
	 */
	static function _ajax()
	{
        if (!isset($_GET['pagiNonce']) || !wp_verify_nonce($_GET['pagiNonce'], 'zoo-p4gi-n0nc3')) {
			wp_send_json_error( array(
				'success' => false,
				'message' => esc_html__('Invalid AJAX request!', 'cvca')
			) );
		}

        if (!isset($_GET['pagiPage']) || $_GET['pagiPage'] < 1) {
			wp_send_json_error( array(
				'success' => false,
				'message' => esc_html__('Invalid AJAX request!', 'cvca')
			) );
		}

		$template = !empty($_GET['pagiTemplate']) ? wp_normalize_path(urldecode($_GET['pagiTemplate'])) : 'inc/templates/posts/archive/grid-layout.php';
		$post_type = !empty($_GET['pagiPostType']) ? sanitize_key($_GET['pagiPostType']) : 'any';
		$posts_per_page = get_option('posts_per_page');

        $query = new WP_Query( array(
			'paged' => intval($_GET['pagiPage']),
			'post_type' => $post_type,
			'post_status' => 'publish',
			'posts_per_page' => $posts_per_page,
			'suppress_filters' => true,
			'ignore_sticky_posts' => true
		) );

        ob_start();

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                locate_template($template, true, false);
            endwhile;
        endif;

		$content = ob_get_clean();

        wp_reset_postdata();

        wp_send_json(array(
            'success' => true,
            'content' => $content,
        ));
	}

	/**
	 * Render
	 */
	function render()
	{
		$type = get_theme_mod('zoo_blog_pagination');

		$this->query->max_num_pages = intval($this->query->max_num_pages);
		$this->query->query_vars['paged'] = $this->query->query_vars['paged'] ? : 1;

		if (1 >= $this->query->max_num_pages || empty($this->query->posts)) {
			return;
		}

		$config = apply_filters( 'zoo_pagination_config_data', array(
			'maxPages'      => $this->query->max_num_pages,
			'pagiTemplate'  => urlencode('inc/templates/posts/archive/grid-layout.php'),
			'pagiPostType'  => $this->query->posts[0]->post_type,
			'pagiItemClass' => '.hentry',
			'transition'    => array(
				'type'  => 'fade',
				'speed' => 300
			),
		) );

		?><div id="<?php echo esc_attr(uniqid()) ?>" class="entry-pagination" data-config='<?php echo esc_attr( json_encode($config) ) ?>'><?php
		  	if ('infinity' === $type) {
		    	$this->theInfinitePagination($this->query);
		  	} elseif ('simple' === $type) {
		    	$this->theTextPagination($this->query);
		  	} elseif ('ajaxload' === $type) {
				$this->theLoadMorePagination($this->query);
			} else {
				$this->theNumericPagination($this->query);
			}
		?></div><?php
	}

	/**
	 * The previous button
	 */
	protected function thePreviousButton(\WP_Query $query)
	{
		if (1 < $this->query->query_vars['paged']) : ?>
		  	<li class="prev">
		    	<a rel="prev" href="<?php echo get_pagenum_link($this->query->query_vars['paged'] - 1) ?>">
		      		<?php echo apply_filters( 'zoo_pagination_prev_button_label', '&lang; ' . esc_html__('Prev Page', 'cvca') ) ?>
		    	</a>
		  	</li>
		<?php endif;
	}

	/**
	 * The next button
	 */
	protected function theNextButton(\WP_Query $query)
	{
		if ($this->query->max_num_pages > $this->query->query_vars['paged']) : ?>
		  	<li class="next">
		    	<a rel="next" href="<?php echo get_pagenum_link($this->query->query_vars['paged'] + 1) ?>">
		      		<?php echo apply_filters( 'zoo_pagination_next_button_label', esc_html__('Next Page', 'cvca') . ' &rang;' ) ?>
		    	</a>
		  	</li>
		<?php endif;
	}

	/**
	 * The numeric pagination
	 *
	 * @param  array  $pages  The current page and around pages which will be displayed.
	 */
	protected function theNumericPagination(\WP_Query $query, $pages = [])
	{
		// Get max number of pages.
		$maxpages = $this->query->max_num_pages;

		// Get current page.
		$current = $this->query->query_vars['paged'];

		// Add the first page to the array.
		if (1 === $current) $pages[] = $current;

		// Add current page and around pages to the displaying array.
		if (2 <= $current) {
		  	$pages[] = $current;
		  	for ($i = 1; $i <= 2; $i++) {
		    	if ($current + $i > $maxpages) break;
		    	$pages[] = $current - $i;
		    	$pages[] = $current + $i;
		  	}
		}

		// Make sure the pages are in correct order.
		$pages = array_filter( array_unique($pages) ); sort($pages);

		echo "<ul>";

		$this->thePreviousButton($this->query);

		// Show the first page.
		if ( !in_array(1, $pages) ) :
		  	$class = (1 === $current) ? ' class="active"' : '';
		  	?><li<?php echo esc_attr($class) ?>>
		    	<a href="<?php echo get_pagenum_link(1) ?>" aria-label="<?php esc_html_e('Go to page', 'cvca') ?> 1">1</a>
		  	</li><?php
		  	if ( !in_array(2, $pages) ) echo '<li role="presentation">&hellip;</li>';
		endif;

		// Show the current page and around pages.
		foreach ($pages as $page) :
		  	$class = ($current === $page) ? ' class="active"' : '';
		  	?><li<?php echo  esc_attr($class) ?>>
		    	<a href="<?php echo get_pagenum_link($page) ?>" aria-label="<?php esc_html_e('Go to page', 'cvca') . ' ' . $page ?>"><?php echo esc_html($page) ?></a>
		  	</li><?php
		endforeach;

		// Show the last page.
		if ( !in_array($maxpages, $pages) ) :
		  	if ( !in_array($maxpages - 1, $pages) ) echo '<li role="presentation">&hellip;</li>';
		  	$class = ($current === $maxpages) ? ' class="active"' : '';
		  	?><li<?php echo  esc_attr($class) ?>>
		    	<a href="<?php echo get_pagenum_link($maxpages) ?>" aria-label="<?php esc_html_e('Go to page', 'cvca') . ' ' . $maxpages ?>"><?php echo esc_html($maxpages) ?></a>
		  	</li><?php
		endif;

		echo "</ul>";

		$this->theNextButton($this->query);
	}

	/**
	 * The text pagination.
	 */
	protected function theTextPagination(\WP_Query $query)
	{
		echo "<ul>";

		$this->thePreviousButton($this->query);

		?><li class="current">
	  		<?php echo esc_html__('Page', 'cvca') . ' ' . $this->query->query_vars['paged'] . '/' . $this->query->max_num_pages ?>
		</li><?php

		$this->theNextButton($this->query);

		echo "</ul>";
	}

	/**
	 * Infinite pagination
	 */
	protected function theInfinitePagination(WP_Query $query)
	{
		$loading_icon = apply_filters('zoo_pagination_loading_icon', '<span class="spinner" aria-hidden="true" style="display:none"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></span>');

		?><button class="button load-more-button" data-type="scroll">
			<?php echo $loading_icon; ?>
			<span class="label"><?php echo apply_filters('zoo_infinitescroll_button_text', esc_html__('Load More', 'cvca')) ?></span>
		</button><?php
	}

	/**
	 * Load more pagination
	 */
	protected function theLoadMorePagination(WP_Query $query)
	{
		$loading_icon = apply_filters('zoo_pagination_loading_icon', '<span class="spinner" aria-hidden="true" style="display:none"><i class="fa fa-spinner fa-pulse fa-2x fa-fw"></i></span>');

		?><button class="button load-more-button" data-type="click">
			<?php echo $loading_icon; ?>
			<span class="label"><?php echo apply_filters('zoo_loadmore_button_text', esc_html__('Load More', 'cvca')) ?></span>
		</button><?php
	}
}
