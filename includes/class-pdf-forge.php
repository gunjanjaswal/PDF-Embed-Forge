<?php
/**
 * Core plugin class: registers the block, the shortcode, and the shared renderer.
 *
 * @package PDF_Forge
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PDF_Forge {

	/**
	 * Singleton instance.
	 *
	 * @var PDF_Forge|null
	 */
	private static $instance = null;

	/**
	 * Boot the plugin (singleton).
	 *
	 * @return PDF_Forge
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Hook everything up.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'register' ) );
		add_shortcode( 'pdf-forge', array( $this, 'render' ) );
		add_filter( 'plugin_row_meta', array( $this, 'row_meta' ), 10, 2 );
	}

	/**
	 * Add Ko-fi and contact links to the plugin's row on the Plugins screen.
	 *
	 * @param string[] $links Existing row meta links.
	 * @param string   $file  Plugin file the row is for.
	 * @return string[]
	 */
	public function row_meta( $links, $file ) {
		if ( plugin_basename( PDF_FORGE_FILE ) !== $file ) {
			return $links;
		}

		$links[] = '<a href="' . esc_url( 'https://ko-fi.com/gunjanjaswal' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support on Ko-fi', 'pdf-forge' ) . '</a>';
		$links[] = '<a href="' . esc_url( 'mailto:hello@gunjanjaswal.me' ) . '">' . esc_html__( 'Contact developer', 'pdf-forge' ) . '</a>';

		return $links;
	}

	/**
	 * Register the editor assets and the block.
	 */
	public function register() {
		wp_register_style(
			'pdf-forge',
			PDF_FORGE_URL . 'assets/css/pdf-forge.css',
			array(),
			PDF_FORGE_VERSION
		);

		wp_register_script(
			'pdf-forge-editor',
			PDF_FORGE_URL . 'assets/js/block.js',
			array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-components', 'wp-i18n' ),
			PDF_FORGE_VERSION,
			true
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'pdf-forge-editor', 'pdf-forge', PDF_FORGE_DIR . 'languages' );
		}

		if ( function_exists( 'register_block_type' ) ) {
			register_block_type(
				PDF_FORGE_DIR . 'block',
				array( 'render_callback' => array( $this, 'render_block' ) )
			);
		}
	}

	/**
	 * Bridge the block attributes to the shared renderer.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public function render_block( $attributes ) {
		return $this->render(
			array(
				'id'       => isset( $attributes['id'] ) ? $attributes['id'] : 0,
				'url'      => isset( $attributes['url'] ) ? $attributes['url'] : '',
				'height'   => isset( $attributes['height'] ) ? $attributes['height'] : 600,
				'toolbar'  => ( ! isset( $attributes['toolbar'] ) || $attributes['toolbar'] ) ? '1' : '0',
				'title'    => isset( $attributes['title'] ) ? $attributes['title'] : '',
			)
		);
	}

	/**
	 * Render the PDF viewer. Shared by the block and the [pdf-forge] shortcode.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public function render( $atts ) {
		$a = shortcode_atts(
			array(
				'id'       => 0,
				'url'      => '',
				'height'   => 600,
				'toolbar'  => '1',
				'title'    => '',
			),
			$atts,
			'pdf-forge'
		);

		$url = $a['url'] ? $a['url'] : ( $a['id'] ? wp_get_attachment_url( (int) $a['id'] ) : '' );
		if ( ! $url ) {
			return '';
		}

		// Only embed things that are actually PDFs.
		$is_pdf = ( $a['id'] && 'application/pdf' === get_post_mime_type( (int) $a['id'] ) )
			|| preg_match( '/\.pdf($|[?#])/i', $url );
		if ( ! $is_pdf ) {
			return '';
		}

		wp_enqueue_style( 'pdf-forge' );

		$height = is_numeric( $a['height'] ) ? (int) $a['height'] . 'px' : preg_replace( '/[^0-9a-z%.]/i', '', (string) $a['height'] );
		if ( '' === $height ) {
			$height = '600px';
		}

		$toolbar = $this->is_on( $a['toolbar'] );

		// Open parameters for the built-in viewer: hide the thumbnail/pages sidebar and
		// fit the page to the viewer width. Chromium honours these; other browsers ignore them.
		$params = array( 'navpanes=0', 'view=FitH' );
		if ( ! $toolbar ) {
			$params[] = 'toolbar=0';
		}
		$src = $url . '#' . implode( '&', $params );

		$title = $a['title'];
		if ( ! $title && $a['id'] ) {
			$title = get_the_title( (int) $a['id'] );
		}
		if ( ! $title ) {
			$title = __( 'Embedded PDF', 'pdf-forge' );
		}

		ob_start();
		?>
		<div class="pdf-forge">
			<div class="pdf-forge__frame-wrap" style="height:<?php echo esc_attr( $height ); ?>">
				<iframe class="pdf-forge__frame" src="<?php echo esc_url( $src ); ?>" title="<?php echo esc_attr( $title ); ?>" loading="lazy"></iframe>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Interpret a truthy attribute from a shortcode string or a block boolean.
	 *
	 * @param mixed $value Raw value.
	 * @return bool
	 */
	private function is_on( $value ) {
		if ( is_bool( $value ) ) {
			return $value;
		}
		$value = strtolower( trim( (string) $value ) );
		return ! in_array( $value, array( '0', 'false', 'no', 'off', '' ), true );
	}
}
