<?php
class BBlocksInfoBox extends BBlocks{
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

		wp_enqueue_style( 'b-blocks-info-box-style' );
		// wp_enqueue_script( 'b-blocks-info-box-script', B_BLOCKS_DIST . 'info-box.js', [], B_BLOCKS_VERSION, true ); // Not needed
		// wp_set_script_translations( 'b-blocks-info-box-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-info-box $className $planClass align$align";

		// Generate Styles
		$mainSl = "#bBlocksInfoBox-$cId";
		$infoBoxSl = "$mainSl .bBlocksInfoBox";

		$infoBoxStyle = new BBlocksStyleGenerator();
		$infoBoxStyle::addStyle( "$mainSl", [
			'text-align' => $infoBoxAlign
		] );
		$infoBoxStyle::addStyle( "$infoBoxSl", [
			'text-align' => $textAlign,
			'width' => '0px' === $width || '0%' === $width || '0em' === $width ? '100%' : $width,
			GetCSS::getBackgroundCSS( $background ) => '',
			'padding' => GetCSS::getSpaceCSS( $padding ),
			GetCSS::getBorderCSS( $border ) => '',
			'box-shadow' => GetCSS::getShadowCSS( $shadow )
		] );
		$infoBoxStyle::addStyle( "$infoBoxSl .infoBoxIcon", [
			'margin' => GetCSS::getSpaceCSS( $iconMargin )
		] );
		$infoBoxStyle::addStyle( "$infoBoxSl .infoBoxIcon i", [
			GetCSS::getIconCSS( $icon ) => ''
		] );
		$infoBoxStyle::addStyle( "$infoBoxSl .infoBoxTitle", [
			'color' => $titleColor,
			'margin' => GetCSS::getSpaceCSS( $titleMargin )
		] );
		$infoBoxStyle::addStyle( "$infoBoxSl .infoBoxDescription", [
			'color' => $descColor,
			'margin' => GetCSS::getSpaceCSS( $descMargin )
		] );
		$infoBoxStyle::addStyle( "$infoBoxSl .infoBoxButton", [
			GetCSS::getColorsCSS( $btnColors ) => '',
			'padding' => GetCSS::getSpaceCSS( $btnPadding ),
			GetCSS::getBorderCSS( $btnBorder ) => ''
		] );
		$infoBoxStyle::addStyle( "$infoBoxSl .infoBoxButton:hover", [
			GetCSS::getColorsCSS( $btnHovColors ) => ''
		] );

		$btnTab = $isLinkNewTab ? '_blank' : '';

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksInfoBox-<?php echo esc_attr( $cId ); ?>'>
			<style>
				<?php
					echo esc_html( GetCSS::getTypoCSS( '', $titleTypo )['googleFontLink'] );
					echo esc_html( GetCSS::getTypoCSS( '', $descTypo )['googleFontLink'] );
					echo esc_html( GetCSS::getTypoCSS( '', $btnTypo )['googleFontLink'] );
					echo esc_html( GetCSS::getTypoCSS( "$infoBoxSl .infoBoxTitle", $titleTypo )['styles'] );
					echo esc_html( GetCSS::getTypoCSS( "$infoBoxSl .infoBoxDescription", $descTypo )['styles'] );
					echo esc_html( GetCSS::getTypoCSS( "$infoBoxSl .infoBoxButton", $btnTypo )['styles'] );
					echo wp_kses( $infoBoxStyle::renderStyle(), [] );
				?>
			</style>

			<div class='bBlocksInfoBox'>
				<?php echo $isIcon && $icon['class'] ? "<div class='infoBoxIcon'><i class='". esc_html( $icon['class'] ) ."'></i></div>" : ''; ?>
				<?php echo $title ? "<h3 class='infoBoxTitle'>". wp_kses_post( $title ) ."</h3>" : ''; ?>
				<?php echo $isDesc && $desc ? "<p class='infoBoxDescription'>". wp_kses_post( $desc ) ."</p>" : ''; ?>
				<?php echo $isBtn && $btn ? "<a href='". esc_url( $btnLink ) ."' class='infoBoxButton' target='". esc_attr( $btnTab ) ."' rel='noreferrer noopener'>". wp_kses_post( $btn ) ."</a>" : ''; ?>
			</div>
		</div>
		<?php $infoBoxStyle::$styles = []; // Empty styles
		return ob_get_clean();
	}
}
new BBlocksInfoBox();