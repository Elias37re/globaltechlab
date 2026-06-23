<?php
/**
 * GamerMania functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package GamerMania
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/* ----------------------------------------------------
   1. THEME SUPPORT & SETUP
---------------------------------------------------- */
if ( ! function_exists( 'gamermania_setup' ) ) :
	function gamermania_setup() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Let WordPress manage the document title.
		add_theme_support( 'title-tag' );

		// Enable support for Post Thumbnails on posts and pages.
		add_theme_support( 'post-thumbnails' );

		// Register Navigation Menus
		register_nav_menus(
			array(
				'menu-primary' => esc_html__( 'Menu Principal', 'gamermania' ),
				'menu-footer'  => esc_html__( 'Menu Rodapé', 'gamermania' ),
			)
		);

		// Switch default core markup for search form, comment form, etc., to output valid HTML5.
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'style',
				'script',
			)
		);
	}
endif;
add_action( 'after_setup_theme', 'gamermania_setup' );

/* ----------------------------------------------------
   2. SCRIPTS & STYLES ENQUEUE
---------------------------------------------------- */
function gamermania_scripts() {
	// Google Fonts: Outfit and Inter
	wp_enqueue_style(
		'gamermania-google-fonts',
		'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@500;700;800&display=swap',
		array(),
		null
	);

	// FontAwesome for Icons
	wp_enqueue_style(
		'gamermania-font-awesome',
		'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
		array(),
		'6.4.0'
	);

	// Main Theme Stylesheet
	wp_enqueue_style( 'gamermania-style', get_stylesheet_uri(), array( 'gamermania-google-fonts' ), '1.0.0' );

	// Theme custom JS
	wp_enqueue_script(
		'gamermania-navigation',
		get_template_directory_uri() . '/js/theme.js',
		array(),
		'1.0.0',
		true
	);
}
add_action( 'wp_enqueue_scripts', 'gamermania_scripts' );

/* ----------------------------------------------------
   3. SIDEBAR REGISTER
---------------------------------------------------- */
function gamermania_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar Lateral', 'gamermania' ),
			'id'            => 'sidebar-primary',
			'description'   => esc_html__( 'Adicione widgets aqui para aparecerem na barra lateral à direita.', 'gamermania' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'gamermania_widgets_init' );

/* ----------------------------------------------------
   4. CUSTOM POST TYPE: PROMOÇÃO (DEAL)
---------------------------------------------------- */
function gamermania_register_cpt_promocao() {
	$labels = array(
		'name'                  => _x( 'Promoções', 'Post Type General Name', 'gamermania' ),
		'singular_name'         => _x( 'Promoção', 'Post Type Singular Name', 'gamermania' ),
		'menu_name'             => __( 'Promoções', 'gamermania' ),
		'name_admin_bar'        => __( 'Promoção', 'gamermania' ),
		'archives'              => __( 'Arquivos de Promoções', 'gamermania' ),
		'attributes'            => __( 'Atributos da Promoção', 'gamermania' ),
		'parent_item_colon'     => __( 'Promoção Pai:', 'gamermania' ),
		'all_items'             => __( 'Todas as Promoções', 'gamermania' ),
		'add_new_item'          => __( 'Adicionar Nova Promoção', 'gamermania' ),
		'add_new'               => __( 'Adicionar Nova', 'gamermania' ),
		'new_item'              => __( 'Nova Promoção', 'gamermania' ),
		'edit_item'             => __( 'Editar Promoção', 'gamermania' ),
		'update_item'           => __( 'Atualizar Promoção', 'gamermania' ),
		'view_item'             => __( 'Ver Promoção', 'gamermania' ),
		'view_items'            => __( 'Ver Promoções', 'gamermania' ),
		'search_items'          => __( 'Buscar Promoção', 'gamermania' ),
		'not_found'             => __( 'Nenhuma promoção encontrada', 'gamermania' ),
		'not_found_in_trash'    => __( 'Nenhuma promoção encontrada na Lixeira', 'gamermania' ),
		'featured_image'        => __( 'Capa do Jogo', 'gamermania' ),
		'set_featured_image'    => __( 'Definir Capa do Jogo', 'gamermania' ),
		'remove_featured_image' => __( 'Remover Capa do Jogo', 'gamermania' ),
		'use_featured_image'    => __( 'Usar como Capa', 'gamermania' ),
		'insert_into_item'      => __( 'Inserir na promoção', 'gamermania' ),
		'uploaded_to_this_item' => __( 'Enviado para esta promoção', 'gamermania' ),
		'items_list'            => __( 'Lista de promoções', 'gamermania' ),
		'items_list_navigation' => __( 'Navegação da lista de promoções', 'gamermania' ),
		'filter_items_list'     => __( 'Filtrar lista de promoções', 'gamermania' ),
	);

	$args = array(
		'label'               => __( 'Promoção', 'gamermania' ),
		'description'         => __( 'Post para promoções de jogos de PS5', 'gamermania' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'taxonomies'          => array( 'category' ),
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'menu_position'       => 5,
		'menu_icon'           => 'dashicons-games',
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
		'show_in_rest'        => true, // Enable Block Editor (Gutenberg) support
	);

	register_post_type( 'promocao', $args );
}
add_action( 'init', 'gamermania_register_cpt_promocao', 0 );

/* ----------------------------------------------------
   5. METABOXES FOR PROMOÇÃO FIELDS
---------------------------------------------------- */
function gamermania_add_promocao_metaboxes() {
	add_meta_box(
		'gamermania_promocao_details',
		__( 'Detalhes do Preço e Oferta', 'gamermania' ),
		'gamermania_promocao_metabox_callback',
		'promocao',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'gamermania_add_promocao_metaboxes' );

function gamermania_promocao_metabox_callback( $post ) {
	// Add nonce for security
	wp_nonce_field( 'gamermania_save_promocao_meta', 'gamermania_promocao_nonce' );

	// Retrieve current values
	$preco_antigo  = get_post_meta( $post->ID, '_preco_antigo', true );
	$preco_novo    = get_post_meta( $post->ID, '_preco_novo', true );
	$link_afiliado = get_post_meta( $post->ID, '_link_afiliado', true );

	// Output fields HTML
	?>
	<table class="form-table">
		<tr>
			<th scope="row"><label for="preco_antigo"><?php _e( 'Preço Antigo (Sem desconto)', 'gamermania' ); ?></label></th>
			<td>
				<input type="text" id="preco_antigo" name="preco_antigo" value="<?php echo esc_attr( $preco_antigo ); ?>" class="regular-text" placeholder="Ex: 349,90" />
				<p class="description"><?php _e( 'Digite o preço original do jogo (apenas números, vírgula opcional). Ex: 349,90', 'gamermania' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="preco_novo"><?php _e( 'Preço Novo (Com desconto)', 'gamermania' ); ?></label></th>
			<td>
				<input type="text" id="preco_novo" name="preco_novo" value="<?php echo esc_attr( $preco_novo ); ?>" class="regular-text" placeholder="Ex: 227,43" />
				<p class="description"><?php _e( 'Digite o preço com a promoção ativa (apenas números, vírgula opcional). Ex: 227,43', 'gamermania' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="link_afiliado"><?php _e( 'Link de Afiliado (Oferta)', 'gamermania' ); ?></label></th>
			<td>
				<input type="url" id="link_afiliado" name="link_afiliado" value="<?php echo esc_url( $link_afiliado ); ?>" class="large-text" placeholder="https://..." />
				<p class="description"><?php _e( 'Link de afiliado para redirecionar o usuário (Amazon, Magalu, PS Store, etc.).', 'gamermania' ); ?></p>
			</td>
		</tr>
	</table>
	<?php
}

function gamermania_save_promocao_meta( $post_id ) {
	// Safety checks
	if ( ! isset( $_POST['gamermania_promocao_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( $_POST['gamermania_promocao_nonce'], 'gamermania_save_promocao_meta' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Sanitize and save fields
	if ( isset( $_POST['preco_antigo'] ) ) {
		$preco_antigo_clean = sanitize_text_field( $_POST['preco_antigo'] );
		update_post_meta( $post_id, '_preco_antigo', $preco_antigo_clean );
	}

	if ( isset( $_POST['preco_novo'] ) ) {
		$preco_novo_clean = sanitize_text_field( $_POST['preco_novo'] );
		update_post_meta( $post_id, '_preco_novo', $preco_novo_clean );
	}

	if ( isset( $_POST['link_afiliado'] ) ) {
		$link_afiliado_clean = esc_url_raw( $_POST['link_afiliado'] );
		update_post_meta( $post_id, '_link_afiliado', $link_afiliado_clean );
	}
}
add_action( 'save_post', 'gamermania_save_promocao_meta' );

/* ----------------------------------------------------
   6. HELPER FUNCTIONS
---------------------------------------------------- */
/**
 * Calculates discount percentage automatically from old and new prices.
 *
 * @param string $preco_antigo Original price.
 * @param string $preco_novo Promo price.
 * @return int Calculated discount percentage.
 */
function gamermania_calculate_discount( $preco_antigo, $preco_novo ) {
	// Standardize strings to float
	$old = (float) str_replace( ',', '.', preg_replace( '/[^\d,.]/', '', $preco_antigo ) );
	$new = (float) str_replace( ',', '.', preg_replace( '/[^\d,.]/', '', $preco_novo ) );

	if ( $old > 0 && $new > 0 && $old > $new ) {
		$percentage = ( ( $old - $new ) / $old ) * 100;
		return round( $percentage );
	}

	return 0;
}

/* ----------------------------------------------------
   7. DEMO DATA IMPORTER (TEMPORARY)
---------------------------------------------------- */
function gamermania_import_demo_data() {
	if ( isset( $_GET['import_gamermania_demo'] ) && current_user_can( 'manage_options' ) ) {
		// Import demo promotions
		$promocoes = array(
			array(
				'title' => 'Cyberpunk 2077: Ultimate Edition - PS5',
				'preco_antigo' => '349,90',
				'preco_novo' => '227,43',
				'link' => 'https://www.amazon.com.br/dp/B0CN9M7QDG',
				'content' => 'Aproveite a experiência definitiva de Cyberpunk 2077. Esta edição contém o jogo base com todas as atualizações gratuitas e a aclamada expansão de espionagem e suspense Phantom Liberty.',
			),
			array(
				'title' => 'Elden Ring: Shadow of the Erdtree Edition - PS5',
				'preco_antigo' => '399,90',
				'preco_novo' => '279,93',
				'link' => 'https://www.amazon.com.br/dp/B0CX24C65M',
				'content' => 'Vencedor de centenas de prêmios de Jogo do Ano, Elden Ring recebe sua primeira grande expansão. Explore a Terra das Sombras e enfrente novos desafios com armas e magias inéditas.',
			),
			array(
				'title' => 'Starfield Chronicles: Stellar Edition - PS5',
				'preco_antigo' => '329,90',
				'preco_novo' => '197,94',
				'link' => 'https://www.playstation.com',
				'content' => 'Explore o espaço sideral nesta jornada épica de RPG de ficção científica dos criadores de Skyrim e Fallout. Crie qualquer personagem que desejar e explore com liberdade sem precedentes.',
			),
			array(
				'title' => 'Horizon Forbidden West - Complete Edition - PS5',
				'preco_antigo' => '299,90',
				'preco_novo' => '149,95',
				'link' => 'https://www.playstation.com',
				'content' => 'Junte-se a Aloy enquanto ela enfrenta a nova e misteriosa ameaça do Oeste Proibido. Esta edição completa inclui a expansão Burning Shores e diversos bônus de conteúdo digital.',
			),
			array(
				'title' => "Marvel's Spider-Man 2 - Edição Padrão PS5",
				'preco_antigo' => '349,90',
				'preco_novo' => '262,42',
				'link' => 'https://www.playstation.com',
				'content' => 'Os Spiders Peter Parker e Miles Morales retornam para uma nova e incrível aventura na aclamada franquia da Insomniac Games. Balance, salte e utilize as novas asas de teia.',
			),
			array(
				'title' => 'God of War Ragnarök - Versão Digital PS5',
				'preco_antigo' => '349,90',
				'preco_novo' => '174,95',
				'link' => 'https://www.playstation.com',
				'content' => 'Kratos e Atreus devem viajar por cada um dos Nove Reinos em busca de respostas enquanto as forças asgardianas se preparam para uma batalha profetizada que acabará com o mundo.',
			),
			array(
				'title' => 'Resident Evil 4 Remake - Mídia Física PS5',
				'preco_antigo' => '299,90',
				'preco_novo' => '179,94',
				'link' => 'https://www.amazon.com.br',
				'content' => 'Sobrevivência é apenas o começo. Seis anos se passaram desde o desastre biológico em Raccoon City. Leon S. Kennedy é enviado para resgatar a filha sequestrada do presidente.',
			),
			array(
				'title' => 'Final Fantasy VII Rebirth - PlayStation 5',
				'preco_antigo' => '349,90',
				'preco_novo' => '244,93',
				'link' => 'https://www.amazon.com.br',
				'content' => 'A jornada rumo ao desconhecido continua. Após escaparem da cidade distópica de Midgar, Cloud e seus amigos partem em uma viagem pelo planeta inteiro em busca de Sephiroth.',
			),
			array(
				'title' => "Demon's Souls Remake - Exclusivo PS5",
				'preco_antigo' => '349,90',
				'preco_novo' => '139,96',
				'link' => 'https://www.playstation.com',
				'content' => 'Totalmente reconstruído do zero, este remake convida você a enfrentar os desafios do reino sombrio e nebuloso de Boletaria nesta clássica experiência de RPG de ação extrema.',
			),
			array(
				'title' => 'Gran Turismo 7 - Edição Standard PS5',
				'preco_antigo' => '349,90',
				'preco_novo' => '209,94',
				'link' => 'https://www.playstation.com',
				'content' => 'Não importa se você é um piloto competitivo ou casual, colecionador, tunador, designer de visuais ou fotógrafo. Encontre sua linha de corrida com a maior garagem de carros do mundo.',
			),
		);

		foreach ( $promocoes as $promo ) {
			// Check if already exists
			$existing = get_page_by_title( $promo['title'], OBJECT, 'promocao' );
			if ( ! $existing ) {
				$post_id = wp_insert_post( array(
					'post_title'   => $promo['title'],
					'post_content' => $promo['content'],
					'post_status'  => 'publish',
					'post_type'    => 'promocao',
				) );
				if ( $post_id ) {
					update_post_meta( $post_id, '_preco_antigo', $promo['preco_antigo'] );
					update_post_meta( $post_id, '_preco_novo', $promo['preco_novo'] );
					update_post_meta( $post_id, '_link_afiliado', $promo['link'] );
				}
			}
		}

		// Import news posts
		$noticias = array(
			array(
				'title' => 'PlayStation 5 Pro é anunciado oficialmente pela Sony com GPU aprimorada',
				'content' => 'A Sony revelou os detalhes técnicos do PS5 Pro, prometendo taxas de quadro mais estáveis e Ray Tracing aprimorado por IA em títulos compatíveis. Com uma GPU com 67% mais unidades de computação e memória 28% mais rápida, o console promete renderização até 45% mais ágil em comparação com o modelo padrão.',
			),
			array(
				'title' => 'GTA 6 receberá otimizações exclusivas para o hardware do PlayStation 5',
				'content' => 'De acordo com novas informações de desenvolvedores parceiros, o PlayStation 5 contará com tempos de carregamento instantâneos e suporte total ao feedback tátil do DualSense. A Rockstar está trabalhando em conjunto com a Sony para garantir que a experiência em Los Santos seja a mais imersiva e responsiva possível.',
			),
			array(
				'title' => 'Os 10 jogos de PS5 mais jogados no primeiro semestre de 2026',
				'content' => 'A Sony divulgou a lista dos jogos que mais acumularam horas de gameplay nos consoles PlayStation 5 nos primeiros 6 meses deste ano. Títulos competitivos gratuitos continuam dominando, mas grandes RPGs lançados recentemente também garantiram posições de destaque no ranking global.',
			),
		);

		foreach ( $noticias as $noticia ) {
			$existing = get_page_by_title( $noticia['title'], OBJECT, 'post' );
			if ( ! $existing ) {
				wp_insert_post( array(
					'post_title'   => $noticia['title'],
					'post_content' => $noticia['content'],
					'post_status'  => 'publish',
					'post_type'    => 'post',
				) );
			}
		}

		// Flush permalinks to ensure the new custom post type URLs work immediately
		flush_rewrite_rules();

		wp_safe_redirect( admin_url() . '?import_success=1' );
		exit;
	}
}
add_action( 'admin_init', 'gamermania_import_demo_data' );

// Add Admin Notice for Import option
function gamermania_admin_import_notice() {
	if ( isset( $_GET['import_success'] ) ) {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php _e( 'Dados de demonstração do GamerMania importados com sucesso!', 'gamermania' ); ?></p>
		</div>
		<?php
	} elseif ( current_user_can( 'manage_options' ) ) {
		// Check if we already have posts
		$deals = get_posts( array( 'post_type' => 'promocao', 'posts_per_page' => 1 ) );
		if ( empty( $deals ) ) {
			?>
			<div class="notice notice-info">
				<p>
					<?php _e( 'Deseja preencher seu site GamerMania com os jogos e notícias de demonstração (Cyberpunk, Elden Ring, etc.)?', 'gamermania' ); ?>
					<a href="<?php echo esc_url( admin_url( '?import_gamermania_demo=1' ) ); ?>" class="button button-primary" style="margin-left: 10px;">
						<?php _e( 'Importar Conteúdo de Demonstração', 'gamermania' ); ?>
					</a>
				</p>
			</div>
			<?php
		}
	}
}
add_action( 'admin_notices', 'gamermania_admin_import_notice' );

/* ----------------------------------------------------
   8. FALLBACK MENU IMPLEMENTATION
---------------------------------------------------- */
if ( ! function_exists( 'gamermania_default_menu' ) ) {
	function gamermania_default_menu() {
		$home_class = ( is_front_page() || is_home() ) ? 'current-menu-item' : '';
		$single_class = ( is_single() && get_post_type() === 'post' ) ? 'current-menu-item' : '';
		$promocoes_class = ( is_category( 'promocoes' ) || is_singular( 'promocao' ) ) ? 'current-menu-item' : '';
		$noticias_class = ( is_category( 'noticias' ) ) ? 'current-menu-item' : '';
		
		// Get Links
		$home_url = home_url( '/' );
		
		$single_post_url = '';
		$demo_post = get_page_by_title( 'PlayStation 5 Pro é anunciado oficialmente pela Sony com GPU aprimorada', OBJECT, 'post' );
		if ( $demo_post ) {
			$single_post_url = get_permalink( $demo_post->ID );
		} else {
			$recent_posts = get_posts( array(
				'numberposts' => 1,
				'post_type'   => 'post',
				'post_status' => 'publish'
			) );
			if ( ! empty( $recent_posts ) ) {
				$single_post_url = get_permalink( $recent_posts[0]->ID );
			} else {
				$single_post_url = home_url( '/' );
			}
		}
		
		$promocoes_category = get_category_by_slug( 'promocoes' );
		$promocoes_url = $promocoes_category ? get_category_link( $promocoes_category->term_id ) : home_url( '/category/promocoes/' );
		
		$noticias_category = get_category_by_slug( 'noticias' );
		$noticias_url = $noticias_category ? get_category_link( $noticias_category->term_id ) : home_url( '/category/noticias/' );
		
		echo '<ul>';
		echo '<li class="' . esc_attr( $home_class ) . '"><a href="' . esc_url( $home_url ) . '">' . esc_html__( 'Home', 'gamermania' ) . '</a></li>';
		echo '<li class="' . esc_attr( $single_class ) . '"><a href="' . esc_url( $single_post_url ) . '">' . esc_html__( 'Artigo Exemplo (Single)', 'gamermania' ) . '</a></li>';
		echo '<li class="' . esc_attr( $promocoes_class ) . '"><a href="' . esc_url( $promocoes_url ) . '">' . esc_html__( 'Promoções Categoria', 'gamermania' ) . '</a></li>';
		echo '<li class="' . esc_attr( $noticias_class ) . '"><a href="' . esc_url( $noticias_url ) . '">' . esc_html__( 'Notícias Categoria', 'gamermania' ) . '</a></li>';
		echo '</ul>';
	}
}

add_action( 'init', 'gamermania_setup_categories' );
function gamermania_setup_categories() {
	// Create "Promoções" category if it doesn't exist
	$promo_cat = get_term_by( 'slug', 'promocoes', 'category' );
	if ( ! $promo_cat ) {
		$promo_cat_id = wp_insert_term( 'Promoções', 'category', array( 'slug' => 'promocoes' ) );
		if ( ! is_wp_error( $promo_cat_id ) && isset( $promo_cat_id['term_id'] ) ) {
			$promo_term_id = $promo_cat_id['term_id'];
		}
	} else {
		$promo_term_id = $promo_cat->term_id;
	}

	// Create "Notícias" category if it doesn't exist
	$noticias_cat = get_term_by( 'slug', 'noticias', 'category' );
	if ( ! $noticias_cat ) {
		$noticias_cat_id = wp_insert_term( 'Notícias', 'category', array( 'slug' => 'noticias' ) );
		if ( ! is_wp_error( $noticias_cat_id ) && isset( $noticias_cat_id['term_id'] ) ) {
			$noticias_term_id = $noticias_cat_id['term_id'];
		}
	} else {
		$noticias_term_id = $noticias_cat->term_id;
	}

	// Assign demo posts to categories if they are in "Sem categoria" (default category, usually term_id 1)
	if ( isset( $noticias_term_id ) ) {
		$news_posts = get_posts( array(
			'post_type' => 'post',
			'numberposts' => -1,
			'category' => 1, // Sem categoria / Uncategorized
		) );
		foreach ( $news_posts as $p ) {
			wp_set_post_categories( $p->ID, array( $noticias_term_id ) );
		}
	}

	if ( isset( $promo_term_id ) ) {
		$promo_posts = get_posts( array(
			'post_type' => 'promocao',
			'numberposts' => -1,
		) );
		foreach ( $promo_posts as $p ) {
			// CPT promocao uses category taxonomy, let's assign it
			wp_set_post_categories( $p->ID, array( $promo_term_id ) );
		}
	}
}

/* ----------------------------------------------------
   9. AMAZON PAAPI 5.0 INTEGRATION
---------------------------------------------------- */
require_once get_template_directory() . '/inc/class-gamermania-amazon-api.php';
require_once get_template_directory() . '/inc/class-gamermania-amazon-admin.php';
require_once get_template_directory() . '/inc/class-gamermania-amazon-shortcode.php';

// Initialize the Amazon integration
GamerMania_Amazon_Admin::init();
GamerMania_Amazon_Shortcode::init();
