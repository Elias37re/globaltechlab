<?php
/**
 * Painel admin: gerenciador de anúncios e links de afiliados.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registra o submenu de Anúncios no WordPress.
 */
function tema_votacao_anuncios_admin_menu(): void {
    add_submenu_page(
        'tema-votacao-resultados', // Menu pai
        __('Gerenciar Anúncios', 'tema-personalizado'),
        __('Anúncios', 'tema-personalizado'),
        'manage_options',
        'tema-votacao-anuncios',
        'tema_votacao_render_anuncios_page'
    );
}
add_action('admin_menu', 'tema_votacao_anuncios_admin_menu');

/**
 * Retorna as configurações salvas de anúncios fundidas com as padrões.
 *
 * @return array<string, string>
 */
function tema_votacao_obter_configuracoes_anuncios(): array {
    $defaults = [
        'top_type' => 'html', // 'html' ou 'link'
        'top_link_url' => '',
        'top_link_text' => __('Ver Ofertas', 'tema-personalizado'),
        'top_title' => __('Confira as melhores ofertas do dia na Amazon!', 'tema-personalizado'),
        'top_subtitle' => __('Descontos exclusivos em milhares de produtos. Aproveite frete grátis!', 'tema-personalizado'),
        'top_badge' => __('Oferta Especial', 'tema-personalizado'),
        'top_html' => '',

        'meio_type' => 'html',
        'meio_link_url' => '',
        'meio_link_text' => __('Ir para a Amazon', 'tema-personalizado'),
        'meio_title' => __('Tudo em um só lugar', 'tema-personalizado'),
        'meio_subtitle' => __('Explore eletrônicos, livros, moda e mais.', 'tema-personalizado'),
        'meio_badge' => __('Mais Vendidos', 'tema-personalizado'),
        'meio_html' => '',

        'rodape_type' => 'html',
        'rodape_link_url' => '',
        'rodape_link_text' => __('Aproveitar Descontos', 'tema-personalizado'),
        'rodape_title' => __('Não perca os descontos de hoje!', 'tema-personalizado'),
        'rodape_subtitle' => __('Encontre promoções imperdíveis com a garantia e segurança da Amazon.', 'tema-personalizado'),
        'rodape_badge' => __('Ofertas do Dia', 'tema-personalizado'),
        'rodape_html' => '',
    ];

    $salvo = get_option('tema_votacao_anuncios', []);
    if (!is_array($salvo)) {
        $salvo = [];
    }

    return array_merge($defaults, $salvo);
}

/**
 * Renderiza a página administrativa de gerenciamento de anúncios.
 */
function tema_votacao_render_anuncios_page(): void {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Sem permissão para acessar esta página.', 'tema-personalizado'));
    }

    // Processa o salvamento do formulário
    $mensagem = '';
    if (isset($_POST['tema_votacao_salvar_anuncios']) && check_admin_referer('tema_votacao_anuncios_action', 'tema_votacao_anuncios_nonce')) {
        $opcoes = tema_votacao_obter_configuracoes_anuncios();
        
        $campos = [
            'top_type', 'top_link_url', 'top_link_text', 'top_title', 'top_subtitle', 'top_badge', 'top_html',
            'meio_type', 'meio_link_url', 'meio_link_text', 'meio_title', 'meio_subtitle', 'meio_badge', 'meio_html',
            'rodape_type', 'rodape_link_url', 'rodape_link_text', 'rodape_title', 'rodape_subtitle', 'rodape_badge', 'rodape_html'
        ];

        foreach ($campos as $campo) {
            if (isset($_POST[$campo])) {
                if (strpos($campo, '_html') !== false) {
                    // Para HTML, mantemos as tags, mas sanitizamos contra scripts maliciosos se não for admin de confiança.
                    // Como a página exige 'manage_options' (apenas administradores), salvamos diretamente.
                    $opcoes[$campo] = wp_unslash($_POST[$campo]);
                } elseif (strpos($campo, '_url') !== false) {
                    $opcoes[$campo] = esc_url_raw(trim(wp_unslash($_POST[$campo])));
                } else {
                    $opcoes[$campo] = sanitize_text_field(trim(wp_unslash($_POST[$campo])));
                }
            }
        }

        update_option('tema_votacao_anuncios', $opcoes);
        $mensagem = '<div class="notice notice-success is-dismissible"><p><strong>' . esc_html__('Configurações salvas com sucesso!', 'tema-personalizado') . '</strong></p></div>';
    }

    $opcoes = tema_votacao_obter_configuracoes_anuncios();
    $active_tab = isset($_GET['tab']) ? sanitize_key($_GET['tab']) : 'top';

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__('Gerenciamento de Anúncios e Afiliados', 'tema-personalizado') . '</h1>';
    echo '<p>' . esc_html__('Configure os links de afiliados ou códigos HTML dos espaços publicitários do site.', 'tema-personalizado') . '</p>';

    echo $mensagem; // Exibe feedback de sucesso se houver

    // Navegação por abas
    echo '<h2 class="nav-tab-wrapper">';
    echo '<a href="?page=tema-votacao-anuncios&tab=top" class="nav-tab ' . ($active_tab === 'top' ? 'nav-tab-active' : '') . '">' . esc_html__('Anúncio do Topo', 'tema-personalizado') . '</a>';
    echo '<a href="?page=tema-votacao-anuncios&tab=meio" class="nav-tab ' . ($active_tab === 'meio' ? 'nav-tab-active' : '') . '">' . esc_html__('Anúncio Lateral (Meio)', 'tema-personalizado') . '</a>';
    echo '<a href="?page=tema-votacao-anuncios&tab=rodape" class="nav-tab ' . ($active_tab === 'rodape' ? 'nav-tab-active' : '') . '">' . esc_html__('Anúncio do Rodapé', 'tema-personalizado') . '</a>';
    echo '</h2>';

    echo '<form method="post" action="" style="margin-top: 20px; background: #fff; border: 1px solid #c3c4c7; padding: 20px; border-radius: 4px; max-width: 800px; box-shadow: 0 1px 1px rgba(0,0,0,.04);">';
    wp_nonce_field('tema_votacao_anuncios_action', 'tema_votacao_anuncios_nonce');

    if ($active_tab === 'top') {
        tema_votacao_render_fields_section('top', $opcoes);
    } elseif ($active_tab === 'meio') {
        tema_votacao_render_fields_section('meio', $opcoes);
    } elseif ($active_tab === 'rodape') {
        tema_votacao_render_fields_section('rodape', $opcoes);
    }

    echo '<p class="submit" style="margin-top: 20px; padding: 0;">';
    submit_button(__('Salvar Configurações', 'tema-personalizado'), 'primary', 'tema_votacao_salvar_anuncios', false);
    echo '</p>';
    echo '</form>';
    echo '</div>';

    // Script inline simples para alternar visibilidade com base no tipo selecionado
    ?>
    <script>
    jQuery(document).ready(function($) {
        function toggleAdFields() {
            var selectedType = $('.js-ad-type-select').val();
            if (selectedType === 'html') {
                $('.js-ad-html-group').show();
                $('.js-ad-link-group').hide();
            } else {
                $('.js-ad-html-group').hide();
                $('.js-ad-link-group').show();
            }
        }
        
        $('.js-ad-type-select').on('change', toggleAdFields);
        toggleAdFields();
    });
    </script>
    <?php
}

/**
 * Renderiza os campos de formulário para uma seção específica.
 */
function tema_votacao_render_fields_section(string $prefix, array $opcoes): void {
    $type_val = $opcoes[$prefix . '_type'] ?? 'html';
    $html_val = $opcoes[$prefix . '_html'] ?? '';
    $url_val = $opcoes[$prefix . '_url'] ?? '';
    $text_val = $opcoes[$prefix . '_text'] ?? '';
    // Corrigido para corresponder aos nomes dos campos no banco:
    $link_url = $opcoes[$prefix . '_link_url'] ?? '';
    $link_text = $opcoes[$prefix . '_link_text'] ?? '';
    $title_val = $opcoes[$prefix . '_title'] ?? '';
    $subtitle_val = $opcoes[$prefix . '_subtitle'] ?? '';
    $badge_val = $opcoes[$prefix . '_badge'] ?? '';

    ?>
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row"><label for="<?php echo esc_attr($prefix); ?>_type"><?php esc_html_e('Tipo de Anúncio', 'tema-personalizado'); ?></label></th>
            <td>
                <select name="<?php echo esc_attr($prefix); ?>_type" id="<?php echo esc_attr($prefix); ?>_type" class="js-ad-type-select" style="min-width: 250px;">
                    <option value="html" <?php selected($type_val, 'html'); ?>><?php esc_html_e('Código HTML / Iframe / Script', 'tema-personalizado'); ?></option>
                    <option value="link" <?php selected($type_val, 'link'); ?>><?php esc_html_e('Link Customizado (Estilo Premium)', 'tema-personalizado'); ?></option>
                </select>
                <p class="description"><?php esc_html_e('Selecione se deseja colar um código pronto (como do AdSense ou banners prontos) ou criar um link customizado bonito.', 'tema-personalizado'); ?></p>
            </td>
        </tr>

        <!-- Grupo HTML -->
        <tr class="js-ad-html-group">
            <th scope="row"><label for="<?php echo esc_attr($prefix); ?>_html"><?php esc_html_e('Código HTML / Script', 'tema-personalizado'); ?></label></th>
            <td>
                <textarea name="<?php echo esc_attr($prefix); ?>_html" id="<?php echo esc_attr($prefix); ?>_html" rows="8" class="large-text code" placeholder="<?php esc_attr_e('Cole seu código de afiliado, iframe ou script aqui...', 'tema-personalizado'); ?>"><?php echo esc_textarea($html_val); ?></textarea>
                <p class="description"><?php esc_html_e('Insira o HTML bruto que o seu parceiro/afiliado fornece (ex: iframes da Amazon, banners ou blocos de script do AdSense).', 'tema-personalizado'); ?></p>
            </td>
        </tr>

        <!-- Grupo Link Customizado -->
        <tr class="js-ad-link-group" style="display: none;">
            <th scope="row"><label for="<?php echo esc_attr($prefix); ?>_link_url"><?php esc_html_e('Link de Afiliado (URL)', 'tema-personalizado'); ?></label></th>
            <td>
                <input type="url" name="<?php echo esc_attr($prefix); ?>_link_url" id="<?php echo esc_attr($prefix); ?>_link_url" value="<?php echo esc_url($link_url); ?>" class="regular-text" style="width: 100%; max-width: 500px;" placeholder="https://www.amazon.com/...">
                <p class="description"><?php esc_html_e('Cole o seu link de afiliado pessoal da Amazon ou outro parceiro.', 'tema-personalizado'); ?></p>
            </td>
        </tr>
        <tr class="js-ad-link-group" style="display: none;">
            <th scope="row"><label for="<?php echo esc_attr($prefix); ?>_link_text"><?php esc_html_e('Texto do Botão (CTA)', 'tema-personalizado'); ?></label></th>
            <td>
                <input type="text" name="<?php echo esc_attr($prefix); ?>_link_text" id="<?php echo esc_attr($prefix); ?>_link_text" value="<?php echo esc_attr($link_text); ?>" class="regular-text">
                <p class="description"><?php esc_html_e('Exemplo: Ver Ofertas, Ir para a Amazon, Comprar Agora.', 'tema-personalizado'); ?></p>
            </td>
        </tr>
        <tr class="js-ad-link-group" style="display: none;">
            <th scope="row"><label for="<?php echo esc_attr($prefix); ?>_title"><?php esc_html_e('Título do Banner', 'tema-personalizado'); ?></label></th>
            <td>
                <input type="text" name="<?php echo esc_attr($prefix); ?>_title" id="<?php echo esc_attr($prefix); ?>_title" value="<?php echo esc_attr($title_val); ?>" class="regular-text" style="width: 100%; max-width: 500px;">
            </td>
        </tr>
        <tr class="js-ad-link-group" style="display: none;">
            <th scope="row"><label for="<?php echo esc_attr($prefix); ?>_subtitle"><?php esc_html_e('Subtítulo do Banner', 'tema-personalizado'); ?></label></th>
            <td>
                <input type="text" name="<?php echo esc_attr($prefix); ?>_subtitle" id="<?php echo esc_attr($prefix); ?>_subtitle" value="<?php echo esc_attr($subtitle_val); ?>" class="regular-text" style="width: 100%; max-width: 500px;">
            </td>
        </tr>
        <tr class="js-ad-link-group" style="display: none;">
            <th scope="row"><label for="<?php echo esc_attr($prefix); ?>_badge"><?php esc_html_e('Badge / Rótulo', 'tema-personalizado'); ?></label></th>
            <td>
                <input type="text" name="<?php echo esc_attr($prefix); ?>_badge" id="<?php echo esc_attr($prefix); ?>_badge" value="<?php echo esc_attr($badge_val); ?>" class="regular-text">
            </td>
        </tr>
    </table>
    <?php
}

/**
 * Função auxiliar global para exibir os anúncios nos templates.
 * Renderiza o contêiner aside com o conteúdo correto.
 */
function tema_votacao_exibir_anuncio(string $posicao): void {
    // Mapeamento de posições para classes
    $classes_map = [
        'top' => 'adsense-top',
        'meio' => 'adsense-meio',
        'rodape' => 'adsense-rodape'
    ];

    if (!isset($classes_map[$posicao])) {
        return;
    }

    $classe = $classes_map[$posicao];
    $id = 'adsense-' . $posicao;
    $opcoes = tema_votacao_obter_configuracoes_anuncios();

    $tipo = $opcoes[$posicao . '_type'] ?? 'html';
    $html = $opcoes[$posicao . '_html'] ?? '';
    
    // Links e textos específicos do bloco
    $link_url = $opcoes[$posicao . '_link_url'] ?? '';
    $link_text = $opcoes[$posicao . '_link_text'] ?? '';
    $title = $opcoes[$posicao . '_title'] ?? '';
    $subtitle = $opcoes[$posicao . '_subtitle'] ?? '';
    $badge = $opcoes[$posicao . '_badge'] ?? '';

    // Se o bloco estiver em modo HTML mas estiver totalmente vazio, ou se for link e a URL for vazia,
    // mostramos apenas um placeholder simples ou nada para não quebrar layout de visitantes.
    $tem_conteudo = ($tipo === 'html' && trim($html) !== '') || ($tipo === 'link' && trim($link_url) !== '');

    ?>
    <aside class="adsense-slot <?php echo esc_attr($classe); ?>" id="<?php echo esc_attr($id); ?>" aria-label="<?php esc_attr_e('Patrocinado', 'tema-personalizado'); ?>">
        <?php if ($tem_conteudo) : ?>
            <?php if ($tipo === 'html') : ?>
                <?php // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Output do admin confiável, precisa renderizar HTML bruto/iframe/scripts. ?>
                <?php echo $html; ?>
            <?php elseif ($tipo === 'link') : ?>
                <?php if ($posicao === 'meio') : ?>
                    <!-- Bloco Lateral (Meio) -->
                    <a href="<?php echo esc_url($link_url); ?>" target="_blank" rel="noopener sponsored" class="amazon-banner-link-vertical">
                        <div class="amazon-banner-content-vertical">
                            <div class="amazon-logo-box">
                                <span class="amazon-brand-name">amazon</span>
                            </div>
                            <div class="amazon-icon-box">
                                <svg class="amazon-shopping-svg" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M16 11V7C16 4.79086 14.2091 3 12 3C9.79086 3 8 4.79086 8 7V11M3 9H21L20 21H4L3 9Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </div>
                            <div class="amazon-text-box">
                                <?php if ($badge) : ?>
                                    <span class="amazon-badge"><?php echo esc_html($badge); ?></span>
                                <?php endif; ?>
                                <?php if ($title) : ?>
                                    <span class="amazon-title"><?php echo esc_html($title); ?></span>
                                <?php endif; ?>
                                <?php if ($subtitle) : ?>
                                    <span class="amazon-subtitle"><?php echo esc_html($subtitle); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($link_text) : ?>
                                <span class="amazon-cta-btn"><?php echo esc_html($link_text); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php else : ?>
                    <!-- Blocos Horizontais (Topo e Rodapé) -->
                    <a href="<?php echo esc_url($link_url); ?>" target="_blank" rel="noopener sponsored" class="amazon-banner-link">
                        <div class="amazon-banner-content">
                            <div class="amazon-logo-box">
                                <span class="amazon-brand-name">amazon</span>
                            </div>
                            <div class="amazon-text-box">
                                <?php if ($badge) : ?>
                                    <span class="amazon-badge"><?php echo esc_html($badge); ?></span>
                                <?php endif; ?>
                                <?php if ($title) : ?>
                                    <span class="amazon-title"><?php echo esc_html($title); ?></span>
                                <?php endif; ?>
                                <?php if ($subtitle) : ?>
                                    <span class="amazon-subtitle"><?php echo esc_html($subtitle); ?></span>
                                <?php endif; ?>
                            </div>
                            <?php if ($link_text) : ?>
                                <span class="amazon-cta-btn"><?php echo esc_html($link_text); ?></span>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endif; ?>
            <?php endif; ?>
        <?php else : ?>
            <!-- Placeholder caso esteja vazio e seja administrador logado (para orientação) -->
            <?php if (current_user_can('manage_options')) : ?>
                <span class="adsense-label" style="pointer-events: none; user-select: none;">
                    <?php printf(
                        /* translators: %s: Nome da posição */
                        esc_html__('Espaço AdSense (%s) - Vazio. Configure na página de Anúncios.', 'tema-personalizado'),
                        esc_html($posicao)
                    ); ?>
                </span>
            <?php endif; ?>
        <?php endif; ?>
    </aside>
    <?php
}
