<?php
if ( !defined( 'ABSPATH' ) ) { exit; }

if( !class_exists('BBlocksEnqueueScripts') ){
	class BBlocksEnqueueScripts{
		function __construct(){
			add_action( 'enqueue_block_assets', [$this, 'enqueueBockAssets'] );
			add_action( 'enqueue_block_editor_assets', [$this, 'enqueueBlockEditorAssets'] );
			add_filter( 'script_loader_tag', [$this, 'scriptLoaderTag'], 10, 3 );
		}

		function enqueueBockAssets(){
			wp_register_script( 'easyTicker', B_BLOCKS_ASSETS . 'js/easy-ticker.min.js', [ 'jquery' ], '3.2.1', true ); // Posts
			wp_register_script( 'swiper', B_BLOCKS_ASSETS . 'js/swiper.min.js', [], '8.0.7', true ); // Slider
			wp_register_script( 'dotLottiePlayer', B_BLOCKS_ASSETS . 'js/dotlottie-player.js', [], '1.5.7', true ); // Lottie Player
			wp_register_script( 'lottieInteractivity', B_BLOCKS_ASSETS . 'js/lottie-interactivity.min.js', [ 'dotLottiePlayer' ], '1.5.2', true ); // Lottie Player
			wp_register_script( 'chartJS', B_BLOCKS_ASSETS . 'js/chart.min.js', [], '3.5.1', true ); // Chart
			wp_register_script( 'plyr', B_BLOCKS_ASSETS . 'js/plyr.js', [], '3.7.2', true ); // Video
			wp_register_script( 'textillate', B_BLOCKS_ASSETS . 'js/jquery.textillate.min.js', [ 'jquery' ], '0.6.1', true ); // Animated Text
			wp_register_script( 'goodShare', B_BLOCKS_ASSETS . 'js/goodshare.min.js', [], B_BLOCKS_VERSION, true ); // Social Share
			wp_register_script( 'modelViewer', B_BLOCKS_ASSETS . 'js/model-viewer.min.js', [], B_BLOCKS_VERSION, true ); // 3D Viewer
			wp_register_script( 'aos', B_BLOCKS_ASSETS . 'js/aos.js', [], '3.0.0', true ); // Button

			wp_register_style( 'fontAwesome', B_BLOCKS_ASSETS . 'css/font-awesome.min.css', [], '6.4.2' ); // Icon
			wp_register_style( 'swiper', B_BLOCKS_ASSETS . 'css/swiper.min.css', [], '8.0.7' ); // Slider
			wp_register_style( 'animate', B_BLOCKS_ASSETS . 'css/animate.min.css', [], '4.1.1' ); // Animated Text
			wp_register_style( 'plyr', B_BLOCKS_ASSETS . 'css/plyr.css', [], '3.7.2' ); // Video
			wp_register_style( 'aos', B_BLOCKS_ASSETS . 'css/aos.css', [], '3.0.0' ); // Button

			wp_enqueue_style( 'bBlocksStyle', B_BLOCKS_DIST . 'style.css', [], B_BLOCKS_VERSION ); // Style

			// Single Block Styles
			wp_register_style( 'b-blocks-td-viewer-style', B_BLOCKS_DIST . '3d-viewer.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-alert-style', B_BLOCKS_DIST . 'alert.css', [ 'fontAwesome' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-animated-text-style', B_BLOCKS_DIST . 'animated-text.css', [ 'animate' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-button-style', B_BLOCKS_DIST . 'button.css', [ 'fontAwesome', 'aos' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-button-group-style', B_BLOCKS_DIST . 'button-group.css', [ 'fontAwesome' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-cards-style', B_BLOCKS_DIST . 'cards.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-chart-style', B_BLOCKS_DIST . 'chart.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-countdown-style', B_BLOCKS_DIST . 'countdown.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-counters-style', B_BLOCKS_DIST . 'counters.css', [ 'fontAwesome' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-feature-boxes-style', B_BLOCKS_DIST . 'feature-boxes.css', [ 'fontAwesome' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-flip-boxes-style', B_BLOCKS_DIST . 'flip-boxes.css', [ 'fontAwesome' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-gif-style', B_BLOCKS_DIST . 'gif.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-image-comparison-style', B_BLOCKS_DIST . 'image-comparison.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-image-gallery-style', B_BLOCKS_DIST . 'image-gallery.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-info-box-style', B_BLOCKS_DIST . 'info-box.css', [ 'fontAwesome' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-lottie-player-style', B_BLOCKS_DIST . 'lottie-player.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-mailto-style', B_BLOCKS_DIST . 'mailto.css', [ 'fontAwesome' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-posts-style', B_BLOCKS_DIST . 'posts.css', [ 'dashicons' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-price-lists-style', B_BLOCKS_DIST . 'price-lists.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-pricing-table-style', B_BLOCKS_DIST . 'pricing-table.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-qr-code-style', B_BLOCKS_DIST . 'qr-code.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-row-style', B_BLOCKS_DIST . 'row.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-section-heading-style', B_BLOCKS_DIST . 'section-heading.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-services-style', B_BLOCKS_DIST . 'services.css', [ 'fontAwesome' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-shape-divider-style', B_BLOCKS_DIST . 'shape-divider.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-star-rating-style', B_BLOCKS_DIST . 'star-rating.css', [], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-slider-style', B_BLOCKS_DIST . 'slider.css', [ 'swiper' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-social-share-style', B_BLOCKS_DIST . 'social-share.css', [ 'fontAwesome' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-team-members-style', B_BLOCKS_DIST . 'team-members.css', [ 'fontAwesome' ], B_BLOCKS_VERSION );
			wp_register_style( 'b-blocks-video-style', B_BLOCKS_DIST . 'video.css', [ 'plyr' ], B_BLOCKS_VERSION );
		}

		function enqueueBlockEditorAssets(){
			wp_enqueue_script( 'bBlocksTemplateLibraryScript', B_BLOCKS_DIST . 'template-library.js', [ 'wp-api', 'wp-block-editor', 'wp-blocks', 'wp-components', 'wp-data', 'wp-dom-ready', 'wp-i18n', 'wp-util', 'react', 'react-dom' ], B_BLOCKS_VERSION, false );

			wp_enqueue_style( 'bBlocksTemplateStyle', B_BLOCKS_DIST . 'template-library.css', [], B_BLOCKS_VERSION );

			wp_register_script( 'jqueryUI', B_BLOCKS_ASSETS . 'js/jquery-ui.min.js', [ 'jquery' ], '1.13.0', true ); // Slider Block

			wp_enqueue_script( 'bBlocksEditorScript', B_BLOCKS_DIST . 'editor.js', [ 'wp-api', 'wp-blob', 'wp-block-editor', 'wp-blocks', 'wp-components', 'wp-compose', 'wp-data', 'wp-date', 'wp-element', 'wp-i18n', 'wp-rich-text', 'wp-util', 'jquery', 'jqueryUI', 'modelViewer', 'easyTicker', 'swiper', 'dotLottiePlayer', 'lottieInteractivity', 'chartJS', 'plyr', 'textillate', 'goodShare' ], B_BLOCKS_VERSION, false );
			wp_set_script_translations( 'bBlocksEditorScript', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

			wp_enqueue_style( 'bBlocksEditorStyle', B_BLOCKS_DIST . 'editor.css', [
				'fontAwesome',
				'bBlocksStyle',
				'b-blocks-td-viewer-style',
				'b-blocks-alert-style',
				'b-blocks-animated-text-style',
				'b-blocks-button-style',
				'b-blocks-button-group-style',
				'b-blocks-cards-style',
				'b-blocks-chart-style',
				'b-blocks-countdown-style',
				'b-blocks-counters-style',
				'b-blocks-feature-boxes-style',
				'b-blocks-flip-boxes-style',
				'b-blocks-gif-style',
				'b-blocks-image-comparison-style',
				'b-blocks-image-gallery-style',
				'b-blocks-info-box-style',
				'b-blocks-lottie-player-style',
				'b-blocks-mailto-style',
				'b-blocks-posts-style',
				'b-blocks-price-lists-style',
				'b-blocks-pricing-table-style',
				'b-blocks-qr-code-style',
				'b-blocks-row-style',
				'b-blocks-section-heading-style',
				'b-blocks-services-style',
				'b-blocks-slider-style',
				'b-blocks-shape-divider-style',
				'b-blocks-star-rating-style',
				'b-blocks-social-share-style',
				'b-blocks-team-members-style',
				'b-blocks-video-style'
			], B_BLOCKS_VERSION );
		}

		function scriptLoaderTag( $tag, $handle, $src ){
			if ( 'modelViewer' !== $handle ) {
				return $tag;
			}
			$tag = '<script type="module" src="' . esc_url( $src ) . '"></script>';
			return $tag;
		}
	}
	new BBlocksEnqueueScripts();
}