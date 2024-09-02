<?php
class BBlocksImageGallery extends BBlocks{
	public function __construct(){
		add_action( 'init', [$this, 'onInit'] );
	}

	function onInit(){
		register_block_type( __DIR__, [
			'render_callback' => [$this, 'render']
		] ); // Register Block
	}

	function render( $attributes ){
		extract( $attributes );

		wp_enqueue_style( 'b-blocks-image-gallery-style' );
		// wp_enqueue_script( 'b-blocks-image-gallery-script', B_BLOCKS_DIST . 'image-gallery.js', [], B_BLOCKS_VERSION, true ); // Not Needed
		// wp_set_script_translations( 'b-blocks-image-gallery-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		// Enqueue assets where has block
		wp_enqueue_script( 'fancybox', B_BLOCKS_ASSETS . 'js/fancybox.min.js', [], '4.0', true );
		wp_enqueue_style( 'fancybox', B_BLOCKS_ASSETS . 'css/fancybox.min.css', '', '4.0', 'all' );

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-image-gallery $className $planClass align$align";

		// Generate Styles
		$imgShadow = GetCSS::getShadowCSS( $imgShadow );
		$imgPaddingH = $imgPadding['horizontal'] ?? '0px';
		$imgPaddingV = $imgPadding['vertical'] ?? '0px';

		$imgLinkSl = "#bBlocksImageGallery-$cId .imgGalleryWrapper .imgGalleryImgLink";

		$imageGalleryStyle = new BBlocksStyleGenerator();
		$imageGalleryStyle::addStyle( "$imgLinkSl .imgGalleryImg", [
			'padding' => GetCSS::getSpaceCSS( $imgPadding ),
			GetCSS::getBorderCSS( $imgBorder ) => '',
			'filter' => "drop-shadow( $imgShadow )"
		] );
		$imageGalleryStyle::addStyle( "$imgLinkSl .imgGalleryImgCaption", [
			'text-align' => $capAlign,
			'color' => $capColor,
			'margin' => GetCSS::getSpaceCSS( $imgPadding ),
			'width' => "calc( ( 100% - 10px ) - ( $imgPaddingH * 2 ) )",
			'height' => "calc( ( 100% - 10px ) - ( $imgPaddingV * 2 ) )",
			'border-radius' => $imgBorder['radius'] ?? '0px'
		] );

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksImageGallery-<?php echo esc_attr( $cId ); ?>'>
			<style>
				<?php
					echo esc_html( GetCSS::getTypoCSS( '', $capTitleTypo )['googleFontLink'] );
					echo esc_html( GetCSS::getTypoCSS( '', $capDescTypo )['googleFontLink'] );
					echo esc_html( GetCSS::getTypoCSS( "$imgLinkSl .imgGalleryImgCaption h4", $capTitleTypo )['styles'] );
					echo esc_html( GetCSS::getTypoCSS( "$imgLinkSl .imgGalleryImgCaption p", $capDescTypo )['styles'] );
					echo wp_kses( $imageGalleryStyle::renderStyle(), [] );
				?>
			</style>

			<div class='imgGalleryWrapper columns-<?php echo esc_attr( $columns['desktop'] ); ?> columns-tablet-<?php echo esc_attr( $columns['tablet'] ); ?> columns-mobile-<?php echo esc_attr( $columns['mobile'] ); ?>'>
				<?php foreach ( $images as $image ) {
					$imgUrl = $image['url'];
					$imgUrlLarge = !empty( $image['sizes']['large']['url'] ) ? $image['sizes']['large']['url'] : $imgUrl;
					$imgAlt = $image['alt'] ? $image['alt'] : $image['title'];
					$imgCap = $image['caption'];
					$imgDesc = $image['description']; ?>
					
					<a class='imgGalleryImgLink' 
						<?php if( $isCap ){}else{ ?>
							href='#'
							data-fancybox='imgGalleryPreviewBtn'
							data-src='<?php echo esc_url( $imgUrl ); ?>'
							data-srcset='<?php echo esc_url( $imgUrlLarge ); ?> 576w, <?php echo esc_url( $imgUrl ); ?> 992w'
							data-sizes='( max-width: 576px ) 576px, 992px'
							data-caption='<?php echo wp_kses_post( $imgCap ); ?>'
						<?php } ?>>

						<img src='<?php echo esc_url( $imgUrl ); ?>' alt='<?php echo esc_html( $imgAlt ); ?>' class='imgGalleryImg' />

						<?php if( $isCap && ( !empty( $imgAlt ) || $isPreview ) ){ ?>
							<div class='imgGalleryImgCaption'>
								<div class='captionContent'>
									<?php echo !empty( $imgAlt ) ? "<h4>". wp_kses_post( $imgCap ) ."</h4>" : '' ; ?>
									<?php echo !empty( $imgDesc ) ? "<p>". wp_kses_post( $imgDesc ) ."</p>" : '' ; ?>

									<?php if( $isPreview ){ ?>
										<button class='imgGalleryPreviewBtn'
											data-fancybox='imgGalleryPreviewBtn'
											data-src='<?php echo esc_url( $imgUrl ); ?>'
											data-srcset='<?php echo esc_url( $imgUrlLarge ); ?> 576w, <?php echo esc_url( $imgUrl ); ?> 992w'
											data-sizes='( max-width: 576px ) 576px, 992px'
											data-caption='<?php echo wp_kses_post( $imgCap ); ?>'>
											<i class='fa fa-eye'></i><?php echo esc_html__( 'Preview', 'b-blocks' ); ?>
										</button>
									<?php } ?>
								</div>
							</div>
						<?php } ?> <!-- Image Gallery Caption -->
					</a> <!-- Image Gallery Link -->
				<?php } ?> <!-- Image Gallery Loop -->
			</div> <!-- Image Gallery Wrapper -->
		</div>

		<?php $imageGalleryStyle::$styles = []; // Empty styles
		return ob_get_clean();
	}
}
new BBlocksImageGallery();