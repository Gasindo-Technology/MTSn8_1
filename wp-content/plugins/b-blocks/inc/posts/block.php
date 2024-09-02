<?php
if ( !defined( 'ABSPATH' ) ) { exit; }

require_once B_BLOCKS_DIR_PATH . '/inc/posts/helpers/functions.php';

class BBlocksPosts extends BBlocks{
	public function __construct(){
		add_action( 'init', [$this, 'onInit'] );

		add_filter( 'b_blocks_posts_excerpt_filter', function( $plainText, $htmlContent ){
			return $htmlContent;
		}, 10, 3 );
	}

	function query( $attributes ){
		extract( $attributes );

		$selectedCategories = $selectedCategories ?? [];
		$selectedTaxonomies = $selectedTaxonomies ?? [];

		$termsQuery = ['relation' => 'AND'];
		foreach ( $selectedTaxonomies as $taxonomy => $terms ){
			if( count( $terms ) ){
				$termsQuery[] = [
					'taxonomy'	=> $taxonomy,
					'field'		=> 'term_id',
					'terms'		=> $terms,
				];
			}
		}

		$defaultPostQuery = 'post' === $postType ? [
			'category__in'	=> $selectedCategories,
			'tag__in'		=> $selectedTags ?? []
		] : [];

		$postsInclude = BBlocks\Inc\Posts\Helpers\Functions::filterNaN( $postsInclude ?? [] );
		$post__in = !empty( $postsInclude ) ? [ 'post__in' => $postsInclude ] : [];
		$postsExclude = BBlocks\Inc\Posts\Helpers\Functions::filterNaN( $postsExclude ?? [] );

		$query = array_merge( [
			'post_type'			=> $postType,
			'posts_per_page'	=> $isPostsPerPageAll ? -1 : $postsPerPage,
			'orderby'			=> $postsOrderBy,
			'order'				=> $postsOrder,
			'tax_query'			=> $termsQuery,
			'offset'			=> $isPostsPerPageAll ? 0 : $postsOffset,
			'post__not_in'		=> $isExcludeCurrent ? array_merge( [ get_the_ID() ], $postsExclude ) : $postsExclude,
			'has_password'		=> false,
			'post_status'		=> 'publish'
		], $post__in, $defaultPostQuery );

		if( BBlocks\Inc\BBlocksUtils::isPro() ) {
			$query = apply_filters( 'b_blocks_posts_query', $query );
		}

		return $query;
	}

	function getPosts( $attributes, $pageNumber = 1 ){
		extract( $attributes );

		$attributes['isPostsPerPageAll'] = 'true' === $isPostsPerPageAll;
		$attributes['isExcludeCurrent'] = 'true' === $isExcludeCurrent;

		$newArgs = wp_parse_args( [ 'offset' => ( $postsPerPage * ( $pageNumber - 1 ) ) + $postsOffset ], $this->query( $attributes ) );
		$posts = BBlocks\Inc\Posts\Helpers\Functions::arrangedPosts(
			get_posts( $newArgs ),
			$postType,
			$fImgSize,
			$metaDateFormat
		);

		return $posts;
	}

	function onInit(){
		register_block_type( __DIR__, [
			'render_callback' => [$this, 'render']
		] ); // Register Block
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'b-blocks-posts-style' );
		if( 'ticker' === $layout ){
			wp_enqueue_script( 'easyTicker' );
		}
		wp_enqueue_script( 'b-blocks-posts-script', B_BLOCKS_DIST . 'posts.js', [ 'wp-api', 'wp-util', 'react', 'react-dom' ], B_BLOCKS_VERSION, true );
		wp_set_script_translations( 'b-blocks-posts-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-posts bBlocksPosts $className $planClass align$align";

		$allPosts = get_posts( array_merge( $this->query( $attributes ), [ 'posts_per_page' => -1 ] ) );

		ob_start(); ?>
		<script id='bBlocksFirstPagePostsScript-<?php echo esc_attr( $cId ); ?>'>
			window['bBlocksFirstPagePosts-<?php echo esc_html( $cId ); ?>'] = <?php echo wp_json_encode( $this->getPosts( $attributes ) ); ?>;
		</script>
		<div
			class='<?php echo esc_attr( $blockClassName ); ?>'
			id='bBlocksPosts-<?php echo esc_attr( $cId ); ?>'
			data-nonce='<?php echo esc_attr( wp_json_encode( wp_create_nonce( 'wp_ajax' ) ) ); ?>'
			data-attributes='<?php echo esc_attr( wp_json_encode( $attributes ) ); ?>'
			data-extra='<?php echo esc_attr( wp_json_encode( [ 'totalPosts' => count( $allPosts ) ] ) ); ?>'
		></div>
		<?php return ob_get_clean();
	} // Render
}
new BBlocksPosts();

if( BBlocks\Inc\BBlocksUtils::isPro() ){
	require_once B_BLOCKS_DIR_PATH . '/inc/posts/Ajax.php';
}