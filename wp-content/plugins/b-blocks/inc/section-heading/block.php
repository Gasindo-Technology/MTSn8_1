<?php
class BBlocksSectionHeading extends BBlocks{
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

		wp_enqueue_style( 'b-blocks-section-heading-style' );
		// wp_enqueue_script( 'b-blocks-section-heading-script', B_BLOCKS_DIST . 'section-heading.js', [ 'react', 'react-dom' ], B_BLOCKS_VERSION, true ); // Not Needed
		// wp_set_script_translations( 'b-blocks-section-heading-script', 'b-blocks', B_BLOCKS_DIR_PATH . 'languages' );

		$className = $className ?? '';
		$planClass = BBlocks\Inc\BBlocksUtils::isPro() ? 'pro' : 'free';
		$blockClassName = "wp-block-b-blocks-section-heading $className $planClass align$align";

		// Generate Styles
		$sectionHeadingStyle = new BBlocksStyleGenerator();

		$sectionHeadingSl = "#bBlocksSectionHeading-$cId .bBlocksSectionHeading";

		$sectionHeadingStyle::addStyle( "$sectionHeadingSl", [
			'text-align' => $textAlign
		] );
		$sectionHeadingStyle::addStyle( "$sectionHeadingSl .sectionHeadingTitle", [
			'color' => $titleColor,
			'margin' => GetCSS::getSpaceCSS( $titleMargin )
		] );
		$sectionHeadingStyle::addStyle( "$sectionHeadingSl .sectionHeadingSeparator", [
			GetCSS::getSeparatorCSS( $separator ) => '',
			'margin' => GetCSS::getSpaceCSS( $sepMargin )
		] );
		$sectionHeadingStyle::addStyle( "$sectionHeadingSl .sectionHeadingDescription", [
			'color' => $descColor,
			'margin' => GetCSS::getSpaceCSS( $descMargin )
		] );

		ob_start(); ?>
		<div class='<?php echo esc_attr( $blockClassName ); ?>' id='bBlocksSectionHeading-<?php echo esc_attr( $cId ); ?>'>
			<style>
				<?php
					echo esc_html( GetCSS::getTypoCSS( '', $titleTypo )['googleFontLink'] );
					echo esc_html( GetCSS::getTypoCSS( '', $descTypo )['googleFontLink'] );
					echo esc_html( GetCSS::getTypoCSS( "$sectionHeadingSl .sectionHeadingTitle", $titleTypo )['styles'] );
					echo esc_html( GetCSS::getTypoCSS( "$sectionHeadingSl .sectionHeadingDescription", $descTypo )['styles'] );
					echo wp_kses( $sectionHeadingStyle->renderStyle(), [] );
				?>
			</style>

			<div class='bBlocksSectionHeading'>
				<h2 class='sectionHeadingTitle'><?php echo wp_kses_post( $title ); ?></h2>
				<?php echo $isSep ? "<span class='sectionHeadingSeparator'></span>" : ''; ?>
				<?php echo $isDesc ? "<p class='sectionHeadingDescription'>". wp_kses_post( $desc ) ."</p>" : ''; ?>
			</div>
		</div>
		<?php $sectionHeadingStyle::$styles = []; // Empty styles
		return ob_get_clean();
	}
}
new BBlocksSectionHeading();