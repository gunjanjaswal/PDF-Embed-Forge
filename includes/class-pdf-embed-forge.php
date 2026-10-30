<?php
/**
 * Core plugin class: registers the block, the shortcode, and the shared renderer.
 *
 * @package PDF_Embed_Forge
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class PDF_Embed_Forge {

	/**
	 * Singleton instance.
	 *
	 * @var PDF_Embed_Forge|null
	 */
	private static $instance = null;

	/**
	 * Boot the plugin (singleton).
	 *
	 * @return PDF_Embed_Forge
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
		add_shortcode( 'pdf-embed-forge', array( $this, 'render' ) );
		add_shortcode( 'pdf-forge', array( $this, 'render' ) ); // Back-compat alias.
		add_filter( 'plugin_row_meta', array( $this, 'row_meta' ), 10, 2 );
	}

	/**
	 * Register the editor assets and the block.
	 */
	public function register() {
		wp_register_style(
			'pdf-embed-forge',
			PDF_EMBED_FORGE_URL . 'assets/css/pdf-embed-forge.css',
			array(),
			PDF_EMBED_FORGE_VERSION
		);

		wp_register_script(
			'pdf-embed-forge-editor',
			PDF_EMBED_FORGE_URL . 'assets/js/block.js',
			array( 'wp-blocks', 'wp-block-editor', 'wp-element', 'wp-components', 'wp-i18n' ),
			PDF_EMBED_FORGE_VERSION,
			true
		);

		if ( function_exists( 'wp_set_script_translations' ) ) {
			wp_set_script_translations( 'pdf-embed-forge-editor', 'pdf-embed-forge', PDF_EMBED_FORGE_DIR . 'languages' );
		}

		if ( function_exists( 'register_block_type' ) ) {
			register_block_type(
				PDF_EMBED_FORGE_DIR . 'block',
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
		// Carry the block's Wide/Full alignment onto the wrapper so the theme can break it out.
		$extra = '';
		if ( ! empty( $attributes['align'] ) ) {
			$extra = 'align' . sanitize_html_class( $attributes['align'] );
		}

		return $this->render(
			array(
				'id'      => isset( $attributes['id'] ) ? $attributes['id'] : 0,
				'url'     => isset( $attributes['url'] ) ? $attributes['url'] : '',
				'height'  => isset( $attributes['height'] ) ? $attributes['height'] : 600,
				'toolbar' => ( ! isset( $attributes['toolbar'] ) || $attributes['toolbar'] ) ? '1' : '0',
				'title'   => isset( $attributes['title'] ) ? $attributes['title'] : '',
			),
			$extra
		);
	}

	/**
	 * Render the PDF viewer. Shared by the block and the shortcodes.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public function render( $atts, $extra_class = '' ) {
		$a = shortcode_atts(
			array(
				'id'      => 0,
				'url'     => '',
				'height'  => 600,
				'toolbar' => '1',
				'title'   => '',
			),
			$atts,
			'pdf-embed-forge'
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

		wp_enqueue_style( 'pdf-embed-forge' );

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
			$title = __( 'Embedded PDF', 'pdf-embed-forge' );
		}

		$wrap_class = 'pdf-embed-forge';
		if ( $extra_class ) {
			$wrap_class .= ' ' . $extra_class;
		}

		ob_start();
		?>
		<div class="<?php echo esc_attr( $wrap_class ); ?>">
			<div class="pdf-embed-forge__frame-wrap" style="height:<?php echo esc_attr( $height ); ?>">
				<iframe class="pdf-embed-forge__frame" src="<?php echo esc_url( $src ); ?>" title="<?php echo esc_attr( $title ); ?>" loading="lazy"></iframe>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	/**
	 * Add Ko-fi and contact links to the plugin's row on the Plugins screen.
	 *
	 * @param string[] $links Existing row meta links.
	 * @param string   $file  Plugin file the row is for.
	 * @return string[]
	 */
	public function row_meta( $links, $file ) {
		if ( plugin_basename( PDF_EMBED_FORGE_FILE ) !== $file ) {
			return $links;
		}

		$links[] = '<a href="' . esc_url( 'https://ko-fi.com/gunjanjaswal' ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'Support on Ko-fi', 'pdf-embed-forge' ) . '</a>';
		$links[] = '<a href="' . esc_url( 'mailto:hello@gunjanjaswal.me' ) . '">' . esc_html__( 'Contact developer', 'pdf-embed-forge' ) . '</a>';

		return $links;
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
