<?php
/**
 * GamerMania Amazon PAAPI 5.0 Admin Interface
 *
 * Implements settings page, metabox in Custom Post Type edit screen,
 * and AJAX endpoints for connectivity testing and live product import.
 *
 * @package GamerMania
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class GamerMania_Amazon_Admin {

	/**
	 * Initialize admin actions and hooks.
	 */
	public static function init() {
		// Add admin menu page
		add_action( 'admin_menu', array( __CLASS__, 'add_admin_menu' ) );

		// Register setting options
		add_action( 'admin_init', array( __CLASS__, 'register_settings' ) );

		// Add custom metabox to "promocao" Custom Post Type
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_amazon_metabox' ) );

		// Enqueue scripts and styles in Admin
		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_admin_assets' ) );

		// AJAX endpoints
		add_action( 'wp_ajax_gamermania_amazon_test_connection', array( __CLASS__, 'ajax_test_connection' ) );
		add_action( 'wp_ajax_gamermania_amazon_import_asin', array( __CLASS__, 'ajax_import_asin' ) );
	}

	/**
	 * Add administration menu item.
	 */
	public static function add_admin_menu() {
		add_menu_page(
			__( 'Amazon PAAPI 5.0', 'gamermania' ),
			__( 'Amazon PAAPI', 'gamermania' ),
			'manage_options',
			'gamermania-amazon',
			array( __CLASS__, 'render_settings_page' ),
			'dashicons-amazon',
			80
		);
	}

	/**
	 * Register settings groups and fields.
	 */
	public static function register_settings() {
		register_setting( 'gamermania_amazon_settings_group', 'gamermania_amazon_settings' );
	}

	/**
	 * Load admin-specific JavaScript and CSS.
	 *
	 * @param string $hook The current admin page hook.
	 */
	public static function enqueue_admin_assets( $hook ) {
		// Enqueue only on relevant screens: our settings page or the edit page of promotions/posts
		$screen = get_current_screen();
		
		$is_settings_page = 'toplevel_page_gamermania-amazon' === $hook;
		$is_editing_post  = $screen && ( 'promocao' === $screen->post_type || 'post' === $screen->post_type );

		if ( ! $is_settings_page && ! $is_editing_post ) {
			return;
		}

		// Enqueue CSS
		wp_enqueue_style(
			'gamermania-amazon-admin-css',
			get_template_directory_uri() . '/css/amazon-admin.css',
			array(),
			'1.0.0'
		);

		// Enqueue JS
		wp_enqueue_script(
			'gamermania-amazon-admin-js',
			get_template_directory_uri() . '/js/amazon-admin.js',
			array( 'jquery' ),
			'1.0.0',
			true
		);

		// Localize JS with AJAX URL and Nonces
		wp_localize_script(
			'gamermania-amazon-admin-js',
			'gamermaniaAmazon',
			array(
				'ajax_url'            => admin_url( 'admin-ajax.php' ),
				'test_nonce'          => wp_create_nonce( 'gamermania_amazon_test_nonce' ),
				'import_nonce'        => wp_create_nonce( 'gamermania_amazon_import_nonce' ),
				'txt_loading'         => __( 'Carregando...', 'gamermania' ),
				'txt_success'         => __( 'Sucesso!', 'gamermania' ),
				'txt_error'           => __( 'Erro ao buscar dados.', 'gamermania' ),
				'txt_image_sideload'  => __( 'Baixando e definindo imagem...', 'gamermania' ),
				'txt_import_complete' => __( 'Importação concluída com sucesso!', 'gamermania' ),
			)
		);
	}

	/**
	 * Render the settings page template.
	 */
	public static function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = GamerMania_Amazon_API::get_settings();
		$marketplaces = GamerMania_Amazon_API::get_marketplaces();
		?>
		<div class="wrap gamermania-amazon-wrap">
			<h1><i class="dashicons dashicons-amazon"></i> <?php esc_html_e( 'Configurações do Amazon PAAPI 5.0', 'gamermania' ); ?></h1>
			<p class="description">
				<?php esc_html_e( 'Configure suas credenciais da API de Publicidade de Produtos da Amazon para automatizar a criação de ofertas em seu site.', 'gamermania' ); ?>
			</p>

			<div class="gamermania-amazon-container">
				<!-- Settings Form -->
				<div class="gamermania-amazon-card settings-card">
					<h2><i class="dashicons dashicons-admin-generic"></i> <?php esc_html_e( 'Credenciais e API', 'gamermania' ); ?></h2>
					<form method="post" action="options.php">
						<?php
						settings_fields( 'gamermania_amazon_settings_group' );
						?>
						<table class="form-table">
							<tr>
								<th scope="row"><label for="amazon_access_key"><?php esc_html_e( 'AWS Access Key ID', 'gamermania' ); ?></label></th>
								<td>
									<input type="text" id="amazon_access_key" name="gamermania_amazon_settings[access_key]" value="<?php echo esc_attr( $settings['access_key'] ); ?>" class="regular-text" placeholder="Ex: AKIAIOSFODNN7EXAMPLE" autocomplete="off" />
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="amazon_secret_key"><?php esc_html_e( 'AWS Secret Access Key', 'gamermania' ); ?></label></th>
								<td>
									<input type="password" id="amazon_secret_key" name="gamermania_amazon_settings[secret_key]" value="<?php echo esc_attr( $settings['secret_key'] ); ?>" class="regular-text" placeholder="Ex: wJalrXUtnFEMI/K7MDENG/bPxRfiCYEXAMPLEKEY" autocomplete="off" />
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="amazon_associate_tag"><?php esc_html_e( 'Associate Tag (Tag de Afiliado)', 'gamermania' ); ?></label></th>
								<td>
									<input type="text" id="amazon_associate_tag" name="gamermania_amazon_settings[associate_tag]" value="<?php echo esc_attr( $settings['associate_tag'] ); ?>" class="regular-text" placeholder="Ex: gamermania-20" />
									<p class="description"><?php esc_html_e( 'Sua tag de afiliado da Amazon (ex: tag-20). Os links de redirecionamento serão marcados com ela.', 'gamermania' ); ?></p>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="amazon_marketplace"><?php esc_html_e( 'Marketplace / Região', 'gamermania' ); ?></label></th>
								<td>
									<select id="amazon_marketplace" name="gamermania_amazon_settings[marketplace]">
										<?php foreach ( $marketplaces as $key => $mp ) : ?>
											<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $settings['marketplace'], $key ); ?>>
												<?php echo esc_html( $mp['name'] . ' (' . $mp['host'] . ')' ); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</td>
							</tr>
							<tr>
								<th scope="row"><label for="amazon_cache_expiry"><?php esc_html_e( 'Tempo de Cache', 'gamermania' ); ?></label></th>
								<td>
									<select id="amazon_cache_expiry" name="gamermania_amazon_settings[cache_expiry]">
										<option value="0" <?php selected( $settings['cache_expiry'], 0 ); ?>><?php esc_html_e( 'Desativado (Não recomendado)', 'gamermania' ); ?></option>
										<option value="3600" <?php selected( $settings['cache_expiry'], 3600 ); ?>><?php esc_html_e( '1 Hora', 'gamermania' ); ?></option>
										<option value="21600" <?php selected( $settings['cache_expiry'], 21600 ); ?>><?php esc_html_e( '6 Horas', 'gamermania' ); ?></option>
										<option value="43200" <?php selected( $settings['cache_expiry'], 43200 ); ?>><?php esc_html_e( '12 Horas', 'gamermania' ); ?></option>
										<option value="86400" <?php selected( $settings['cache_expiry'], 86400 ); ?>><?php esc_html_e( '24 Horas', 'gamermania' ); ?></option>
										<option value="172800" <?php selected( $settings['cache_expiry'], 172800 ); ?>><?php esc_html_e( '48 Horas', 'gamermania' ); ?></option>
									</select>
									<p class="description"><?php esc_html_e( 'Evita ultrapassar limites de requisições armazenando as respostas localmente.', 'gamermania' ); ?></p>
								</td>
							</tr>
						</table>
						<?php submit_button(); ?>
					</form>
				</div>

				<!-- Test Connection Panel -->
				<div class="gamermania-amazon-card test-card">
					<h2><i class="dashicons dashicons-admin-links"></i> <?php esc_html_e( 'Testar Conectividade', 'gamermania' ); ?></h2>
					<p><?php esc_html_e( 'Insira um código ASIN válido da Amazon para testar suas chaves de API.', 'gamermania' ); ?></p>
					
					<div class="test-input-group">
						<input type="text" id="gamermania_test_asin" class="regular-text" placeholder="Ex: B0CN9M7QDG (Cyberpunk 2077 PS5)" style="text-transform: uppercase;" />
						<button type="button" id="gamermania_btn_test_conn" class="button button-secondary"><?php esc_html_e( 'Testar Conexão', 'gamermania' ); ?></button>
					</div>

					<div id="gamermania_test_results" class="test-results-container">
						<!-- Result cards or errors will inject here -->
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	/**
	 * Register metabox for posts and promotions.
	 */
	public static function add_amazon_metabox() {
		// Add to promocao post type
		add_meta_box(
			'gamermania_amazon_import_box',
			__( 'Amazon PAAPI 5.0 - Importação Rápida', 'gamermania' ),
			array( __CLASS__, 'render_import_metabox' ),
			'promocao',
			'normal',
			'high'
		);
	}

	/**
	 * Render HTML in the metabox.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public static function render_import_metabox( $post ) {
		?>
		<div class="gamermania-amazon-metabox">
			<p class="intro-desc"><?php esc_html_e( 'Importe dados do produto da Amazon diretamente digitando o código ASIN (Ex: B0CN9M7QDG).', 'gamermania' ); ?></p>
			
			<div class="import-fields-row">
				<div class="asin-field">
					<label for="gamermania_import_asin_input" style="font-weight:600; display:block; margin-bottom:5px;"><?php esc_html_e( 'ASIN do Produto:', 'gamermania' ); ?></label>
					<input type="text" id="gamermania_import_asin_input" class="large-text" placeholder="Ex: B0CN9M7QDG" style="text-transform: uppercase; max-width:280px;" />
				</div>
				<div class="action-buttons">
					<button type="button" id="gamermania_btn_import_asin" class="button button-primary" style="margin-top:22px;">
						<i class="dashicons dashicons-download" style="vertical-align: middle; margin-top:-3px;"></i> <?php esc_html_e( 'Buscar e Preencher', 'gamermania' ); ?>
					</button>
				</div>
			</div>

			<div class="options-row" style="margin-top:15px; border-top:1px solid #eee; padding-top:15px;">
				<span style="font-weight:600; display:block; margin-bottom:8px;"><?php esc_html_e( 'Opções de Preenchimento:', 'gamermania' ); ?></span>
				<label style="margin-right:15px; display:inline-block;">
					<input type="checkbox" id="import_opt_title" checked /> <?php esc_html_e( 'Título do Jogo', 'gamermania' ); ?>
				</label>
				<label style="margin-right:15px; display:inline-block;">
					<input type="checkbox" id="import_opt_price_old" checked /> <?php esc_html_e( 'Preço Antigo', 'gamermania' ); ?>
				</label>
				<label style="margin-right:15px; display:inline-block;">
					<input type="checkbox" id="import_opt_price_new" checked /> <?php esc_html_e( 'Preço Novo', 'gamermania' ); ?>
				</label>
				<label style="margin-right:15px; display:inline-block;">
					<input type="checkbox" id="import_opt_link" checked /> <?php esc_html_e( 'Link de Afiliado', 'gamermania' ); ?>
				</label>
				<label style="display:inline-block;">
					<input type="checkbox" id="import_opt_image" checked /> <?php esc_html_e( 'Definir Imagem de Destaque', 'gamermania' ); ?>
				</label>
			</div>

			<div id="gamermania_import_status" class="import-status-box" style="display:none; margin-top:15px; padding:10px; border-radius:4px;">
				<!-- Status feedback messages -->
			</div>
		</div>
		<?php
	}

	/**
	 * AJAX endpoint to test connectivity from settings page.
	 */
	public static function ajax_test_connection() {
		check_ajax_referer( 'gamermania_amazon_test_nonce', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( array( 'message' => __( 'Sem permissão para esta ação.', 'gamermania' ) ) );
		}

		$asin = isset( $_POST['asin'] ) ? sanitize_text_field( $_POST['asin'] ) : '';
		if ( empty( $asin ) ) {
			wp_send_json_error( array( 'message' => __( 'ASIN inválido fornecido.', 'gamermania' ) ) );
		}

		// Test connection bypassing cache to ensure keys are working
		$result = GamerMania_Amazon_API::get_item( $asin, true );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		wp_send_json_success( $result );
	}

	/**
	 * AJAX endpoint to import and return Amazon product data.
	 * Optionally handles sideloading product image to local Media Library.
	 */
	public static function ajax_import_asin() {
		check_ajax_referer( 'gamermania_amazon_import_nonce', 'nonce' );

		if ( ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( array( 'message' => __( 'Sem permissão para criar posts.', 'gamermania' ) ) );
		}

		$asin    = isset( $_POST['asin'] ) ? sanitize_text_field( $_POST['asin'] ) : '';
		$post_id = isset( $_POST['post_id'] ) ? intval( $_POST['post_id'] ) : 0;
		$sideload_image = isset( $_POST['sideload_image'] ) && 'true' === $_POST['sideload_image'];

		if ( empty( $asin ) ) {
			wp_send_json_error( array( 'message' => __( 'ASIN inválido.', 'gamermania' ) ) );
		}

		// Fetch item details
		$result = GamerMania_Amazon_API::get_item( $asin );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( array( 'message' => $result->get_error_message() ) );
		}

		// If user selected to sideload the image and we have a valid image and post_id
		if ( $sideload_image && ! empty( $result['image_url'] ) && $post_id > 0 ) {
			require_once ABSPATH . 'wp-admin/includes/image.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/media.php';

			// Sideload the image
			$attachment_id = media_sideload_image( $result['image_url'], $post_id, $result['title'], 'id' );

			if ( ! is_wp_error( $attachment_id ) ) {
				// Set as featured image
				set_post_thumbnail( $post_id, $attachment_id );
				
				// Get HTML of the thumbnail to update the editor UI immediately
				$thumbnail_html = get_the_post_thumbnail( $post_id, 'thumbnail' );
				$result['thumbnail_html'] = $thumbnail_html;
				$result['attachment_id']  = $attachment_id;
			} else {
				$result['image_sideload_error'] = $attachment_id->get_error_message();
			}
		}

		wp_send_json_success( $result );
	}
}
