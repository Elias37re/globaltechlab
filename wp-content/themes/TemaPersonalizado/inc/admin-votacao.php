<?php
/**
 * Painel admin: resultados e dados da votação presidencial (somente manage_options).
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Agrega votos por UF e por cidade (apenas votos com participante associado).
 *
 * @return array{by_uf: array<int, array{uf: string, lula: int, bolsonaro: int, total: int}>, by_city: array<int, array{uf: string, cidade: string, lula: int, bolsonaro: int, total: int}>}
 */
function tema_votacao_relatorio_agregar_por_local(): array {
    static $cache = null;
    if ($cache !== null) {
        return $cache;
    }
    global $wpdb;
    if (!tema_votacao_tabela_existe() || !tema_votacao_tabela_participantes_existe()) {
        $cache = ['by_uf' => [], 'by_city' => []];

        return $cache;
    }
    $table_v = tema_votacao_nome_tabela();
    $table_p = tema_votacao_nome_tabela_participantes();
    // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- nomes de tabela gerados pelo tema.
    $sql = "SELECT p.estado AS uf, p.cidade AS cidade, v.candidato AS candidato, COUNT(*) AS cnt
        FROM `{$table_v}` v
        INNER JOIN `{$table_p}` p ON p.id = v.participante_id
        GROUP BY p.estado, p.cidade, v.candidato";
    $raw = $wpdb->get_results($sql, ARRAY_A);
    if (!is_array($raw)) {
        $raw = [];
    }
    $by_uf = [];
    $city_bucket = [];
    foreach ($raw as $row) {
        $uf = isset($row['uf']) ? strtoupper(sanitize_text_field((string) $row['uf'])) : '';
        $cidade = isset($row['cidade']) ? trim(sanitize_text_field((string) $row['cidade'])) : '';
        $cand = isset($row['candidato']) ? strtolower((string) $row['candidato']) : '';
        $cnt = isset($row['cnt']) ? (int) $row['cnt'] : 0;
        if ($uf === '' || $cidade === '') {
            continue;
        }
        if (!isset($by_uf[$uf])) {
            $by_uf[$uf] = ['uf' => $uf, 'lula' => 0, 'bolsonaro' => 0];
        }
        if ($cand === 'lula') {
            $by_uf[$uf]['lula'] += $cnt;
        } elseif ($cand === 'bolsonaro') {
            $by_uf[$uf]['bolsonaro'] += $cnt;
        }
        $ck = $uf . "\x1e" . $cidade;
        if (!isset($city_bucket[$ck])) {
            $city_bucket[$ck] = ['uf' => $uf, 'cidade' => $cidade, 'lula' => 0, 'bolsonaro' => 0];
        }
        if ($cand === 'lula') {
            $city_bucket[$ck]['lula'] += $cnt;
        } elseif ($cand === 'bolsonaro') {
            $city_bucket[$ck]['bolsonaro'] += $cnt;
        }
    }
    ksort($by_uf);
    $by_city = array_values($city_bucket);
    usort(
        $by_city,
        static function (array $a, array $b): int {
            $c = strcmp($a['uf'], $b['uf']);
            if ($c !== 0) {
                return $c;
            }
            return strcasecmp($a['cidade'], $b['cidade']);
        }
    );
    foreach ($by_uf as &$u) {
        $u['total'] = $u['lula'] + $u['bolsonaro'];
    }
    unset($u);
    foreach ($by_city as &$c) {
        $c['total'] = $c['lula'] + $c['bolsonaro'];
    }
    unset($c);

    $cache = [
        'by_uf' => array_values($by_uf),
        'by_city' => $by_city,
    ];

    return $cache;
}

/**
 * Registra o menu no admin.
 */
function tema_votacao_admin_menu(): void {
    add_menu_page(
        __('Resultados da votação', 'tema-personalizado'),
        __('Votação', 'tema-personalizado'),
        'manage_options',
        'tema-votacao-resultados',
        'tema_votacao_render_admin_page',
        'dashicons-chart-bar',
        58
    );
}

add_action('admin_menu', 'tema_votacao_admin_menu');

/**
 * Estilos no admin (inclui impressão do relatório).
 */
function tema_votacao_admin_assets(string $hook_suffix): void {
    if ($hook_suffix !== 'toplevel_page_tema-votacao-resultados') {
        return;
    }
    $print_css = '
@media print {
  #wpadminbar, #adminmenumain, #adminmenuback, #wpfooter, .update-nag, .notice, .tema-votacao-no-print, .tema-votacao-admin > h1, .tema-votacao-admin > .sub { display: none !important; }
  #wpcontent { margin-left: 0 !important; padding-left: 0 !important; }
  #wpbody-content { padding-bottom: 0 !important; }
  .tema-votacao-admin .tema-votacao-cards { break-inside: avoid; }
  .tema-votacao-relatorio-box { box-shadow: none !important; border: none !important; }
  .tema-votacao-chart-wrap { break-inside: avoid; page-break-inside: avoid; }
  table.widefat { break-inside: auto; }
  table.widefat tr { break-inside: avoid; break-after: auto; }
}
';
    wp_add_inline_style(
        'common',
        '.tema-votacao-admin .tema-votacao-cards{display:flex;flex-wrap:wrap;gap:16px;margin:16px 0;}
        .tema-votacao-admin .tema-votacao-card{background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:16px 20px;min-width:160px;box-shadow:0 1px 1px rgba(0,0,0,.04);}
        .tema-votacao-admin .tema-votacao-card strong{display:block;font-size:1.75em;line-height:1.2;margin-top:4px;}
        .tema-votacao-admin .tema-votacao-card span{color:#646970;font-size:12px;text-transform:uppercase;letter-spacing:.5px;}
        .tema-votacao-admin .sub{margin-top:8px;color:#646970;}
        .tema-votacao-relatorio-box{background:#fff;border:1px solid #c3c4c7;border-radius:4px;padding:20px 24px;margin:20px 0;box-shadow:0 1px 1px rgba(0,0,0,.04);}
        .tema-votacao-relatorio-box h2{margin-top:0;}
        .tema-votacao-chart-wrap{position:relative;max-width:100%;margin:20px 0;min-height:260px;}
        .tema-votacao-chart-wrap canvas{max-height:360px;}
        .tema-votacao-filtro-cidade{margin:16px 0;display:flex;align-items:center;gap:12px;flex-wrap:wrap;}
        .tema-votacao-toolbar{margin:16px 0;display:flex;gap:8px;align-items:center;flex-wrap:wrap;}'
        . $print_css
    );
}

add_action('admin_enqueue_scripts', 'tema_votacao_admin_assets');

/**
 * Scripts: gráficos e PDF no admin.
 */
function tema_votacao_admin_scripts(string $hook_suffix): void {
    if ($hook_suffix !== 'toplevel_page_tema-votacao-resultados') {
        return;
    }
    $ver = wp_get_theme()->get('Version') ?: '1.0';
    wp_enqueue_script(
        'chart-js',
        'https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js',
        [],
        '4.4.1',
        true
    );
    wp_enqueue_script(
        'html2pdf-js',
        'https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js',
        [],
        '0.10.1',
        true
    );
    wp_enqueue_script(
        'tema-votacao-admin-relatorio',
        get_template_directory_uri() . '/assets/js/admin-votacao-relatorio.js',
        ['chart-js', 'html2pdf-js'],
        $ver,
        true
    );

    $local = tema_votacao_relatorio_agregar_por_local();
    wp_localize_script(
        'tema-votacao-admin-relatorio',
        'temaVotacaoRelatorio',
        [
            'byUf' => $local['by_uf'],
            'byCity' => $local['by_city'],
            'pdfFilename' => 'relatorio-votacao-' . wp_date('Y-m-d'),
            'i18n' => [
                'lula' => 'Lula',
                'bolsonaro' => 'Flavio Bolsonaro',
                'titleUf' => __('Votos por estado (UF)', 'tema-personalizado'),
                'titleCidade' => __('Votos por cidade', 'tema-personalizado'),
                'pdfUnavailable' => __('Não foi possível gerar o PDF. Recarregue a página e tente de novo.', 'tema-personalizado'),
            ],
        ]
    );
}

add_action('admin_enqueue_scripts', 'tema_votacao_admin_scripts');

/**
 * Renderiza a página de resultados.
 */
function tema_votacao_render_admin_page(): void {
    if (!current_user_can('manage_options')) {
        wp_die(esc_html__('Sem permissão para acessar esta página.', 'tema-personalizado'));
    }

    global $wpdb;
    $table_v = tema_votacao_nome_tabela();
    $table_p = tema_votacao_nome_tabela_participantes();

    $votos_ok = tema_votacao_tabela_existe();
    $part_ok = tema_votacao_tabela_participantes_existe();

    $totais = $votos_ok ? tema_votacao_contagens() : ['lula' => 0, 'bolsonaro' => 0];
    $total_votos = $totais['lula'] + $totais['bolsonaro'];

    $total_part = 0;
    $part_votaram = 0;
    $part_pendentes = 0;
    if ($part_ok) {
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared -- nome de tabela gerado pelo tema.
        $total_part = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$table_p}`");
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $part_votaram = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$table_p}` WHERE votou_em IS NOT NULL");
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $part_pendentes = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$table_p}` WHERE votou_em IS NULL");
    }

    $per_page = 50;
    $paged = max(1, isset($_GET['paged']) ? (int) wp_unslash($_GET['paged']) : 1);
    $offset = ($paged - 1) * $per_page;

    $rows = [];
    $total_rows = 0;
    if ($votos_ok && $part_ok) {
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $total_rows = (int) $wpdb->get_var("SELECT COUNT(*) FROM `{$table_v}`");
        // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
        $rows = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT v.id, v.candidato, v.criado_em, v.participante_id,
                    p.nome_completo, p.cidade, p.estado, p.email
                FROM `{$table_v}` v
                LEFT JOIN `{$table_p}` p ON p.id = v.participante_id
                ORDER BY v.criado_em DESC, v.id DESC
                LIMIT %d OFFSET %d",
                $per_page,
                $offset
            ),
            ARRAY_A
        );
    }

    $total_pages = $per_page > 0 ? (int) ceil($total_rows / $per_page) : 1;

    $rel_local = ($votos_ok && $part_ok) ? tema_votacao_relatorio_agregar_por_local() : ['by_uf' => [], 'by_city' => []];
    $ufs_opts = $rel_local['by_uf'];
    $by_city_rows = $rel_local['by_city'];

    echo '<div class="wrap tema-votacao-admin">';
    echo '<h1>' . esc_html__('Resultados da votação presidencial', 'tema-personalizado') . '</h1>';
    echo '<p class="sub">' . esc_html__('Dados confidenciais: apenas administradores devem consultar esta página.', 'tema-personalizado') . '</p>';

    if (!$votos_ok || !$part_ok) {
        echo '<div class="notice notice-error"><p>';
        echo esc_html__('As tabelas da votação não foram encontradas. Reative o tema ou execute a migração na base de dados.', 'tema-personalizado');
        echo '</p></div></div>';
        return;
    }

    echo '<div class="tema-votacao-no-print">';
    echo '<div class="tema-votacao-cards">';
    echo '<div class="tema-votacao-card"><span>' . esc_html__('Total de votos', 'tema-personalizado') . '</span><strong>' . esc_html(number_format_i18n($total_votos)) . '</strong></div>';
    echo '<div class="tema-votacao-card"><span>Lula</span><strong>' . esc_html(number_format_i18n($totais['lula'])) . '</strong></div>';
    echo '<div class="tema-votacao-card"><span>' . esc_html__('Flavio Bolsonaro', 'tema-personalizado') . '</span><strong>' . esc_html(number_format_i18n($totais['bolsonaro'])) . '</strong></div>';
    echo '<div class="tema-votacao-card"><span>' . esc_html__('Participantes cadastrados', 'tema-personalizado') . '</span><strong>' . esc_html(number_format_i18n($total_part)) . '</strong></div>';
    echo '<div class="tema-votacao-card"><span>' . esc_html__('Já votaram', 'tema-personalizado') . '</span><strong>' . esc_html(number_format_i18n($part_votaram)) . '</strong></div>';
    echo '<div class="tema-votacao-card"><span>' . esc_html__('Cadastro sem voto', 'tema-personalizado') . '</span><strong>' . esc_html(number_format_i18n($part_pendentes)) . '</strong></div>';
    echo '</div>';

    echo '<h2 class="title" style="margin-top:28px;">' . esc_html__('Relatório gráfico por localização', 'tema-personalizado') . '</h2>';
    echo '<p class="description">' . esc_html__('Gráficos por candidato, separados por estado e por cidade. Use o estado para focar o gráfico das cidades.', 'tema-personalizado') . '</p>';

    echo '<div class="tema-votacao-toolbar">';
    echo '<button type="button" class="button button-secondary" id="tema-votacao-btn-print">' . esc_html__('Imprimir relatório', 'tema-personalizado') . '</button> ';
    echo '<button type="button" class="button button-primary" id="tema-votacao-btn-pdf">' . esc_html__('Download PDF', 'tema-personalizado') . '</button>';
    echo '</div>';
    echo '</div>';

    echo '<div id="tema-votacao-relatorio" class="tema-votacao-relatorio-box">';
    echo '<h2>' . esc_html__('Relatório por localização', 'tema-personalizado') . '</h2>';
    echo '<p class="tema-votacao-relatorio-data"><strong>' . esc_html__('Gerado em', 'tema-personalizado') . ':</strong> ';
    echo esc_html(wp_date(get_option('date_format') . ' ' . get_option('time_format')));
    echo '</p>';

    if (empty($rel_local['by_uf']) && empty($rel_local['by_city'])) {
        echo '<p>' . esc_html__('Ainda não há votos com localização associada para exibir no relatório.', 'tema-personalizado') . '</p>';
    } else {
        echo '<div class="tema-votacao-chart-wrap"><canvas id="tema-votacao-chart-uf" aria-label="' . esc_attr__('Gráfico de votos por estado', 'tema-personalizado') . '"></canvas></div>';

        echo '<div class="tema-votacao-filtro-cidade">';
        echo '<label for="tema-votacao-filtro-uf"><strong>' . esc_html__('Estado para o gráfico por cidade', 'tema-personalizado') . '</strong></label>';
        echo '<select id="tema-votacao-filtro-uf" name="tema_votacao_filtro_uf">';
        foreach ($ufs_opts as $u) {
            $uf = isset($u['uf']) ? (string) $u['uf'] : '';
            echo '<option value="' . esc_attr($uf) . '">' . esc_html($uf) . '</option>';
        }
        echo '</select>';
        echo '</div>';

        echo '<div class="tema-votacao-chart-wrap"><canvas id="tema-votacao-chart-cidade" aria-label="' . esc_attr__('Gráfico de votos por cidade', 'tema-personalizado') . '"></canvas></div>';

        echo '<h3>' . esc_html__('Tabela: votos por cidade, estado e candidato', 'tema-personalizado') . '</h3>';
        echo '<table class="widefat striped">';
        echo '<thead><tr>';
        echo '<th>' . esc_html__('Estado (UF)', 'tema-personalizado') . '</th>';
        echo '<th>' . esc_html__('Cidade', 'tema-personalizado') . '</th>';
        echo '<th>Lula</th>';
        echo '<th>' . esc_html__('Flavio Bolsonaro', 'tema-personalizado') . '</th>';
        echo '<th>' . esc_html__('Total', 'tema-personalizado') . '</th>';
        echo '</tr></thead><tbody>';
        foreach ($by_city_rows as $c) {
            echo '<tr>';
            echo '<td>' . esc_html($c['uf']) . '</td>';
            echo '<td>' . esc_html($c['cidade']) . '</td>';
            echo '<td>' . esc_html(number_format_i18n($c['lula'])) . '</td>';
            echo '<td>' . esc_html(number_format_i18n($c['bolsonaro'])) . '</td>';
            echo '<td>' . esc_html(number_format_i18n($c['total'])) . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';
    }

    echo '</div>';

    echo '<div class="tema-votacao-no-print">';
    echo '<h2 class="title" style="margin-top:24px;">' . esc_html__('Últimos votos registados', 'tema-personalizado') . '</h2>';

    if (empty($rows)) {
        echo '<p>' . esc_html__('Ainda não há votos registados.', 'tema-personalizado') . '</p>';
    } else {
        echo '<table class="widefat striped">';
        echo '<thead><tr>';
        echo '<th>' . esc_html__('Data/hora', 'tema-personalizado') . '</th>';
        echo '<th>' . esc_html__('Candidato', 'tema-personalizado') . '</th>';
        echo '<th>' . esc_html__('Participante', 'tema-personalizado') . '</th>';
        echo '<th>' . esc_html__('Cidade / UF', 'tema-personalizado') . '</th>';
        echo '<th>' . esc_html__('E-mail', 'tema-personalizado') . '</th>';
        echo '<th>' . esc_html__('ID', 'tema-personalizado') . '</th>';
        echo '</tr></thead><tbody>';
        foreach ($rows as $r) {
            $nome = isset($r['nome_completo']) ? (string) $r['nome_completo'] : '';
            $cidade = isset($r['cidade']) ? (string) $r['cidade'] : '';
            $uf = isset($r['estado']) ? (string) $r['estado'] : '';
            $email = isset($r['email']) ? (string) $r['email'] : '';
            $cand = isset($r['candidato']) ? (string) $r['candidato'] : '';
            $cand_label = $cand === 'lula' ? 'Lula' : ($cand === 'bolsonaro' ? __('Flavio Bolsonaro', 'tema-personalizado') : $cand);
            $when = isset($r['criado_em']) ? mysql2date(get_option('date_format') . ' ' . get_option('time_format'), $r['criado_em']) : '—';
            $pid = isset($r['participante_id']) ? (int) $r['participante_id'] : 0;
            echo '<tr>';
            echo '<td>' . esc_html($when) . '</td>';
            echo '<td>' . esc_html($cand_label) . '</td>';
            echo '<td>' . esc_html($nome !== '' ? $nome : '—') . '</td>';
            echo '<td>' . esc_html(trim($cidade . ($uf !== '' ? ' / ' . $uf : '')) ?: '—') . '</td>';
            echo '<td>' . ($email !== '' ? '<a href="' . esc_url('mailto:' . $email) . '">' . esc_html($email) . '</a>' : '—') . '</td>';
            echo '<td>' . esc_html($pid > 0 ? (string) $pid : '—') . '</td>';
            echo '</tr>';
        }
        echo '</tbody></table>';

        if ($total_pages > 1) {
            $base = admin_url('admin.php?page=tema-votacao-resultados');
            echo '<div class="tablenav"><div class="tablenav-pages">';
            echo paginate_links(
                [
                    'base' => esc_url_raw(add_query_arg('paged', '%#%', $base)),
                    'format' => '',
                    'prev_text' => '&laquo;',
                    'next_text' => '&raquo;',
                    'total' => $total_pages,
                    'current' => $paged,
                ]
            );
            echo '</div></div>';
        }
    }

    echo '</div>';

    echo '</div>';
}
