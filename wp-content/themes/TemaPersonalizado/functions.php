<?php
/**
 * Funções do tema — votação e banco de dados.
 */

if (!defined('ABSPATH')) {
    exit;
}

require_once get_template_directory() . '/inc/admin-votacao.php';

define('TEMA_VOTACAO_DB_VERSION', '3');

/**
 * Prefixo usado nas tabelas da votação.
 * Se em wp-config.php o $table_prefix não terminar com "_" (ex.: "wpgtl"), o WordPress concatenaria
 * "wpgtl" + "votacao_..." = wpgtlvotacao_... enquanto o SQL manual usa wpgtl_votacao_...
 * Aqui garantimos um underscore antes do sufixo, alinhado ao padrão wp_ e aos scripts SQL do tema.
 */
function tema_votacao_prefix_para_tabelas(): string {
    global $wpdb;
    $p = $wpdb->prefix;
    if ($p === '') {
        return '';
    }
    return substr($p, -1) === '_' ? $p : $p . '_';
}

/**
 * Comprimento de string seguro sem extensão mbstring.
 */
function tema_votacao_strlen(string $texto): int {
    if (function_exists('mb_strlen')) {
        return (int) mb_strlen($texto, 'UTF-8');
    }
    return strlen($texto);
}

function tema_votacao_nome_tabela(): string {
    return tema_votacao_prefix_para_tabelas() . 'votacao_presidencial';
}

function tema_votacao_nome_tabela_participantes(): string {
    return tema_votacao_prefix_para_tabelas() . 'votacao_presidencial_participantes';
}

/**
 * Renomeia tabelas criadas com o prefixo "colado" (ex.: wpgtlvotacao_*) para o nome com underscore (wpgtl_votacao_*).
 */
function tema_votacao_renomear_tabelas_legacy_sem_underscore(): void {
    global $wpdb;
    $p = $wpdb->prefix;
    if ($p === '' || substr($p, -1) === '_') {
        return;
    }
    $pares = [
        $p . 'votacao_presidencial' => tema_votacao_prefix_para_tabelas() . 'votacao_presidencial',
        $p . 'votacao_presidencial_participantes' => tema_votacao_prefix_para_tabelas() . 'votacao_presidencial_participantes',
    ];
    foreach ($pares as $velho => $novo) {
        if ($velho === $novo) {
            continue;
        }
        $existe_velho = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $velho)) === $velho;
        $existe_novo = $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $novo)) === $novo;
        if (!$existe_velho || $existe_novo) {
            continue;
        }
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- nomes gerados pelo tema, sem input do utilizador.
        $wpdb->query("RENAME TABLE `{$velho}` TO `{$novo}`");
    }
}

/**
 * UFs válidas para o cadastro (sigla => nome).
 *
 * @return array<string, string>
 */
function tema_votacao_lista_ufs(): array {
    return [
        'AC' => 'Acre', 'AL' => 'Alagoas', 'AP' => 'Amapá', 'AM' => 'Amazonas',
        'BA' => 'Bahia', 'CE' => 'Ceará', 'DF' => 'Distrito Federal', 'ES' => 'Espírito Santo',
        'GO' => 'Goiás', 'MA' => 'Maranhão', 'MT' => 'Mato Grosso', 'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais', 'PA' => 'Pará', 'PB' => 'Paraíba', 'PR' => 'Paraná',
        'PE' => 'Pernambuco', 'PI' => 'Piauí', 'RJ' => 'Rio de Janeiro', 'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul', 'RO' => 'Rondônia', 'RR' => 'Roraima', 'SC' => 'Santa Catarina',
        'SP' => 'São Paulo', 'SE' => 'Sergipe', 'TO' => 'Tocantins',
    ];
}

/**
 * Cria / atualiza a tabela de votos (dbDelta adiciona colunas em instalações antigas).
 */
function tema_votacao_criar_tabela(): void {
    global $wpdb;
    $table = tema_votacao_nome_tabela();
    $charset_collate = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $sql = "CREATE TABLE {$table} (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        participante_id bigint(20) unsigned DEFAULT NULL,
        candidato varchar(50) NOT NULL,
        criado_em datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY idx_candidato (candidato),
        KEY idx_participante_id (participante_id)
    ) {$charset_collate};";
    dbDelta($sql);
}

/**
 * Cria / atualiza a tabela de participantes (cadastro antes de votar).
 */
function tema_votacao_criar_tabela_participantes(): void {
    global $wpdb;
    $table = tema_votacao_nome_tabela_participantes();
    $charset_collate = $wpdb->get_charset_collate();
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    $sql = "CREATE TABLE {$table} (
        id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
        nome_completo varchar(255) NOT NULL,
        cidade varchar(120) NOT NULL,
        estado char(2) NOT NULL,
        email varchar(255) NOT NULL,
        voto_token varchar(64) DEFAULT NULL,
        votou_em datetime DEFAULT NULL,
        criado_em datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY uk_email (email),
        KEY idx_estado (estado)
    ) {$charset_collate};";
    dbDelta($sql);
}

function tema_votacao_migrar_tabelas(): void {
    tema_votacao_criar_tabela();
    tema_votacao_criar_tabela_participantes();
}

add_action('after_switch_theme', 'tema_votacao_migrar_tabelas');

add_action('init', function (): void {
    if (get_option('tema_votacao_db_version') !== TEMA_VOTACAO_DB_VERSION) {
        tema_votacao_renomear_tabelas_legacy_sem_underscore();
        tema_votacao_migrar_tabelas();
        update_option('tema_votacao_db_version', TEMA_VOTACAO_DB_VERSION);
    }
});

/**
 * Verifica se a tabela existe.
 */
function tema_votacao_tabela_existe(): bool {
    global $wpdb;
    $table = tema_votacao_nome_tabela();
    return $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table)) === $table;
}

function tema_votacao_tabela_participantes_existe(): bool {
    global $wpdb;
    $table = tema_votacao_nome_tabela_participantes();
    return $wpdb->get_var($wpdb->prepare('SHOW TABLES LIKE %s', $table)) === $table;
}

/**
 * Retorna contagens [ 'lula' => n, 'bolsonaro' => n ].
 */
function tema_votacao_contagens(): array {
    global $wpdb;
    if (!tema_votacao_tabela_existe()) {
        return ['lula' => 0, 'bolsonaro' => 0];
    }
    $table = tema_votacao_nome_tabela();
    $lula = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$table}` WHERE candidato = 'lula'");
    $bolsonaro = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$table}` WHERE candidato = 'bolsonaro'");
    return ['lula' => $lula, 'bolsonaro' => $bolsonaro];
}

/**
 * Percentuais inteiros para a UI (somam 100 quando há votos; não altera armazenamento no BD).
 *
 * @return array{lula: int, bolsonaro: int}
 */
function tema_votacao_percentuais_exibicao(int $lula, int $bolsonaro): array {
    $lula = max(0, $lula);
    $bolsonaro = max(0, $bolsonaro);
    $total = $lula + $bolsonaro;
    if ($total <= 0) {
        return ['lula' => 0, 'bolsonaro' => 0];
    }
    $pct_lula = (int) round(($lula / $total) * 100);

    return ['lula' => $pct_lula, 'bolsonaro' => 100 - $pct_lula];
}

/**
 * Devolve um nonce novo para o formulário (sem exigir nonce válido).
 * Evita 403 quando a página foi servida de cache com HTML antigo ou após expiração do tick do nonce.
 */
function tema_votacao_fresh_nonce_ajax(): void {
    nocache_headers();
    wp_send_json_success([
        'nonce' => wp_create_nonce('tema_votacao_nonce'),
    ]);
}

add_action('wp_ajax_tema_votacao_fresh_nonce', 'tema_votacao_fresh_nonce_ajax');
add_action('wp_ajax_nopriv_tema_votacao_fresh_nonce', 'tema_votacao_fresh_nonce_ajax');

function tema_cadastrar_participante_ajax(): void {
    check_ajax_referer('tema_votacao_nonce', 'nonce');
    if (!tema_votacao_tabela_participantes_existe()) {
        wp_send_json_error(['message' => 'Tabela de participantes não encontrada. Reative o tema ou execute a migração SQL.']);
    }
    $nome = isset($_POST['nome_completo']) ? sanitize_text_field(wp_unslash($_POST['nome_completo'])) : '';
    $cidade = isset($_POST['cidade']) ? sanitize_text_field(wp_unslash($_POST['cidade'])) : '';
    $estado = isset($_POST['estado']) ? strtoupper(sanitize_text_field(wp_unslash($_POST['estado']))) : '';
    $email_raw = isset($_POST['email']) ? wp_unslash($_POST['email']) : '';
    $email = sanitize_email($email_raw);
    if ($nome === '' || tema_votacao_strlen($nome) < 3) {
        wp_send_json_error(['message' => 'Informe o nome completo.']);
    }
    if ($cidade === '' || tema_votacao_strlen($cidade) < 2) {
        wp_send_json_error(['message' => 'Informe a cidade.']);
    }
    $ufs = tema_votacao_lista_ufs();
    if (strlen($estado) !== 2 || !isset($ufs[$estado])) {
        wp_send_json_error(['message' => 'Selecione um estado (UF) válido.']);
    }
    if ($email === '' || !is_email($email)) {
        wp_send_json_error(['message' => 'Informe um e-mail válido.']);
    }
    $token = bin2hex(random_bytes(32));
    global $wpdb;
    $table = tema_votacao_nome_tabela_participantes();
    $ok = $wpdb->insert(
        $table,
        [
            'nome_completo' => $nome,
            'cidade' => $cidade,
            'estado' => $estado,
            'email' => $email,
            'voto_token' => $token,
            'criado_em' => current_time('mysql'),
        ],
        ['%s', '%s', '%s', '%s', '%s', '%s']
    );
    if ($ok === false) {
        if (strpos((string) $wpdb->last_error, 'Duplicate') !== false) {
            wp_send_json_error(['message' => 'Este e-mail já foi utilizado no cadastro.']);
        }
        $det = (defined('WP_DEBUG') && WP_DEBUG && $wpdb->last_error)
            ? ' (' . $wpdb->last_error . ')'
            : '';
        wp_send_json_error(['message' => 'Erro ao salvar o cadastro.' . $det]);
    }
    $id = (int) $wpdb->insert_id;
    wp_send_json_success([
        'message' => 'Cadastro concluído. Agora você pode votar.',
        'participante_id' => $id,
        'voto_token' => $token,
    ]);
}

add_action('wp_ajax_tema_cadastrar_participante', 'tema_cadastrar_participante_ajax');
add_action('wp_ajax_nopriv_tema_cadastrar_participante', 'tema_cadastrar_participante_ajax');

function tema_registrar_voto_ajax(): void {
    check_ajax_referer('tema_votacao_nonce', 'nonce');
    $candidato = isset($_POST['candidato']) ? sanitize_text_field(wp_unslash($_POST['candidato'])) : '';
    if (!in_array($candidato, ['lula', 'bolsonaro'], true)) {
        wp_send_json_error(['message' => 'Opção inválida.']);
    }
    $participante_id = isset($_POST['participante_id']) ? (int) $_POST['participante_id'] : 0;
    $voto_token = isset($_POST['voto_token']) ? sanitize_text_field(wp_unslash($_POST['voto_token'])) : '';
    if ($participante_id < 1 || !preg_match('/^[a-f0-9]{64}$/', $voto_token)) {
        wp_send_json_error(['message' => 'Faça o cadastro antes de votar.']);
    }
    if (!tema_votacao_tabela_existe() || !tema_votacao_tabela_participantes_existe()) {
        wp_send_json_error(['message' => 'Tabelas não encontradas. Reative o tema ou execute a migração SQL.']);
    }
    global $wpdb;
    $table_votos = tema_votacao_nome_tabela();
    $table_part = tema_votacao_nome_tabela_participantes();

    $wpdb->query('START TRANSACTION');

    $bloqueado = $wpdb->get_row(
        $wpdb->prepare(
            "SELECT id FROM `{$table_part}` WHERE id = %d AND voto_token = %s AND votou_em IS NULL FOR UPDATE",
            $participante_id,
            $voto_token
        ),
        OBJECT
    );
    if (!$bloqueado) {
        $wpdb->query('ROLLBACK');
        wp_send_json_error(['message' => 'Cadastro inválido ou voto já registrado para este participante.']);
    }

    $ins = $wpdb->insert(
        $table_votos,
        [
            'participante_id' => $participante_id,
            'candidato' => $candidato,
            'criado_em' => current_time('mysql'),
        ],
        ['%d', '%s', '%s']
    );
    if ($ins === false) {
        $wpdb->query('ROLLBACK');
        wp_send_json_error(['message' => 'Erro ao salvar o voto.']);
    }
    $voto_row_id = (int) $wpdb->insert_id;

    $wpdb->query(
        $wpdb->prepare(
            "UPDATE `{$table_part}` SET votou_em = %s, voto_token = NULL WHERE id = %d AND voto_token = %s AND votou_em IS NULL",
            current_time('mysql'),
            $participante_id,
            $voto_token
        )
    );
    if ((int) $wpdb->rows_affected !== 1) {
        $wpdb->delete($table_votos, ['id' => $voto_row_id], ['%d']);
        $wpdb->query('ROLLBACK');
        wp_send_json_error(['message' => 'Não foi possível concluir o voto. Tente novamente.']);
    }

    $wpdb->query('COMMIT');

    $totais = tema_votacao_contagens();
    wp_send_json_success([
        'message' => 'Voto registrado. Obrigado pela participação.',
        'lula' => $totais['lula'],
        'bolsonaro' => $totais['bolsonaro'],
    ]);
}

add_action('wp_ajax_tema_registrar_voto', 'tema_registrar_voto_ajax');
add_action('wp_ajax_nopriv_tema_registrar_voto', 'tema_registrar_voto_ajax');

/**
 * Detecta se a página atual usa o modelo "Página de votação".
 * Combina várias fontes porque is_page_template / slug falham com cache, FSE e alguns salvamentos no editor.
 */
function tema_is_pagina_votacao(): bool {
    if (!is_page()) {
        return false;
    }
    $id = (int) get_queried_object_id();
    if ($id < 1) {
        return false;
    }
    if (is_page_template('page-votacao.php')) {
        return true;
    }
    $slug = (string) get_page_template_slug($id);
    if ($slug === 'page-votacao.php') {
        return true;
    }
    $meta = (string) get_post_meta($id, '_wp_page_template', true);
    if ($meta === 'page-votacao.php') {
        return true;
    }
    return false;
}

/**
 * Resolve nome de ficheiro (ex.: lula.jpg) para o primeiro ficheiro legível com o mesmo stem
 * e extensão jpg, jpeg, png ou webp.
 */
function tema_uri_foto_candidato_resolve(string $dir, string $base_uri, string $filename): ?string {
    $filename = basename((string) $filename);
    if ($filename === '') {
        return null;
    }
    $stem = pathinfo($filename, PATHINFO_FILENAME);
    if (!is_string($stem) || $stem === '') {
        $stem = $filename;
    }
    foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
        $f = $stem . '.' . $ext;
        if (is_readable($dir . $f)) {
            return $base_uri . $f;
        }
    }
    return null;
}

/**
 * URL da foto do candidato ou placeholder SVG se o arquivo não existir.
 *
 * @param string $arquivo Nome sugerido (ex.: lula.jpg); aceita png/webp com o mesmo prefixo.
 */
function tema_uri_foto_candidato(string $arquivo, string $placeholder_svg): string {
    $dir = get_template_directory() . '/assets/candidatos/';
    $base = get_template_directory_uri() . '/assets/candidatos/';
    $resolved = tema_uri_foto_candidato_resolve($dir, $base, $arquivo);
    if ($resolved !== null) {
        return $resolved;
    }
    return $base . $placeholder_svg;
}

/**
 * Primeiro candidato resolvido na ordem da lista; senão o placeholder.
 *
 * @param string[] $arquivos Ordem de preferência (stems via nome de ficheiro, ex.: flavio-bolsonaro.jpg).
 */
function tema_uri_foto_candidato_multi(array $arquivos, string $placeholder_svg): string {
    $dir = get_template_directory() . '/assets/candidatos/';
    $base = get_template_directory_uri() . '/assets/candidatos/';
    foreach ($arquivos as $arquivo) {
        $resolved = tema_uri_foto_candidato_resolve($dir, $base, (string) $arquivo);
        if ($resolved !== null) {
            return $resolved;
        }
    }
    return $base . $placeholder_svg;
}

/**
 * Scripts da urna (chamado no hook e no template para não depender só da detecção da página).
 */
function tema_votacao_enqueue_assets(): void {
    if (wp_script_is('tema-votacao-js', 'enqueued')) {
        return;
    }
    $ver = wp_get_theme()->get('Version') ?: '1.0';
    wp_enqueue_script(
        'tema-votacao-js',
        get_template_directory_uri() . '/assets/js/votacao.js',
        [],
        $ver,
        true
    );
    wp_localize_script('tema-votacao-js', 'temaVotacao', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('tema_votacao_nonce'),
    ]);
}

add_action('wp_enqueue_scripts', function (): void {
    if (is_admin()) {
        return;
    }
    $ver = wp_get_theme()->get('Version') ?: '1.0';
    wp_enqueue_style(
        'tema-personalizado',
        get_stylesheet_uri(),
        [],
        $ver
    );
    if (tema_is_pagina_votacao()) {
        tema_votacao_enqueue_assets();
    }
});

add_theme_support('title-tag');
