<?php
class BBlocksPriceLists extends BBlocks{
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

		wp_enqueue_style( 'b-blocks-price-lists-style' );
		// wp_enqueue_script( 'b-blocks-price-lists-script', B_BLOCKS_DIST . 'price-lists.js', [], B_BLOCKS_VERSION, true ); // Not Needed
		// wp_set_script_translations( 'b-blocks-price-lists-script', 'b-blocks', B_BLOCKS_DIR_PATH

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-price-lists $className $planClass align$align";

		// Generate Styles
		$priceListsStyle = new BBlocksStyleGenerator();

		$mainSl = "#bBlocksPriceLists-$cId";
		$priceListsSl = "$mainSl .bBlocksPriceLists";
		$gPriceSl = "$priceListsSl .bBlocksPrice";

		$priceListsStyle::addStyle( "$priceListsSl", [
			'grid-gap' => "$rowGap $columnGap"
		] );
		$priceListsStyle::addStyle( "$gPriceSl", [
			'text-align' => $textAlign,
			'padding' => GetCSS::getSpaceCSS( $padding )
		] );
		$priceListsStyle::addStyle( "$gPriceSl .productImage", [
			'margin' => GetCSS::getSpaceCSS( $imgMargin )
		] );
		$priceListsStyle::addStyle( "$gPriceSl .productName", [
			'margin' => GetCSS::getSpaceCSS( $nameMargin )
		] );
		$priceListsStyle::addStyle( "$gPriceSl .productDescription", [
			'margin' => GetCSS::getSpaceCSS( $descMargin )
		] );

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksPriceLists-<?php echo esc_attr( $cId ); ?>'>
			<style>
				<?php	
					echo esc_html( GetCSS::getTypoCSS( '', $nameTypo )['googleFontLink'] );
					echo esc_html( GetCSS::getTypoCSS( '', $descTypo )['googleFontLink'] );
					echo esc_html( GetCSS::getTypoCSS( '', $priceTypo )['googleFontLink'] );
					echo esc_html( GetCSS::getTypoCSS( "$gPriceSl .productName", $nameTypo )['styles'] );
					echo esc_html( GetCSS::getTypoCSS( "$gPriceSl .productDescription", $descTypo )['styles'] );
					echo esc_html( GetCSS::getTypoCSS( "$gPriceSl .productPrice", $priceTypo )['styles'] );
					echo wp_kses( $priceListsStyle::renderStyle(), [] );
				foreach ( $products as $index => $product ) {
					extract( $product );
					$priceSl = "$mainSl #bBlocksPrice-$index";

					$backgroundStyle = GetCSS::getBackgroundCSS( $background );
					$imgBorderStyle = GetCSS::getBorderCSS( $imgBorder );
					$separatorStyle = GetCSS::getSeparatorCSS( $separator );

					$flipBoxStyles = "$priceSl{ $backgroundStyle }
					$priceSl .productImage{ $imgBorderStyle }
					$priceSl .productName{ color: $nameColor; }
					$priceSl .productDescription{ color: $descColor; }
					$priceSl .productPrice{ color: $priceColor; }
					$priceSl .productSeparator{ $separatorStyle }";
					echo esc_html( $flipBoxStyles );
				} ?>
			</style>

			<div class='bBlocksPriceLists <?php echo esc_attr( $layout ?? 'vertical' ); ?> columns-<?php echo esc_attr( $columns['desktop'] ); ?> columns-tablet-<?php echo esc_attr( $columns['tablet'] ); ?> columns-mobile-<?php echo esc_attr( $columns['mobile'] ); ?>'>
				<?php foreach ( $products as $index => $product ) {
					extract( $product ); ?>

					<div class='bBlocksPrice' id='bBlocksPrice-<?php echo esc_attr( $index ); ?>'>
						<?php echo $img['url'] ? "<img class='productImage' src='". esc_url( $img['url'] ) ."' alt='". esc_attr( $img['alt'] ) ."' />" : ''; ?>

						<div class='productDetails'>
							<h4 class='productName'><?php echo wp_kses_post( $name ); ?></h4>

							<?php echo $isDesc ? "<p class='productDescription'>". wp_kses_post( $desc ) ."</p>" : ''; ?>

							<span class='productPrice'><?php echo wp_kses_post( $price ); ?></span>
						</div>

						<?php echo $isSep ? "<span class='productSeparator'></span>" : ''; ?>
					</div>
				<?php } ?>
			</div>
		</div>

		<?php $priceListsStyle::$styles = []; // Empty styles
		return ob_get_clean();
	}
}
new BBlocksPriceLists();