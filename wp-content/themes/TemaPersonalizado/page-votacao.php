<?php
/**
 * Template Name: Página de votação
 * Descrição: Voto Lula x Flavio Bolsonaro com áreas para foto e AdSense.
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

tema_votacao_enqueue_assets();

$totais = tema_votacao_contagens();
$pct = tema_votacao_percentuais_exibicao($totais['lula'], $totais['bolsonaro']);
$uri_lula = tema_uri_foto_candidato('lula.jpg', 'placeholder-lula.svg');
$uri_bolsonaro = tema_uri_foto_candidato_multi(
    ['flabio-bolsonaro.jpg', 'flavio-bolsonaro.jpg', 'bolsonaro.jpg'],
    'placeholder-flabio-bolsonaro.svg'
);
$ufs = tema_votacao_lista_ufs();
$votacao_aviso_lei = __(
    '   Este levantamento de dados não possui caráter científico e não se confunde com pesquisa eleitoral oficial (conforme Art. 33 da Lei 9.504/97).',
    'tema-personalizado'
);
?>
<main class="votacao-page">
    <header class="votacao-header">
        <h1 class="votacao-titulo"><?php esc_html_e('Enquente Presidencial 2026', 'tema-personalizado'); ?></h1>
    </header>

    <section class="votacao-cadastro js-votacao-cadastro" aria-labelledby="votacao-cadastro-titulo">
        <h2 id="votacao-cadastro-titulo" class="votacao-cadastro-titulo">Cadastro para votar</h2>
        <p class="votacao-cadastro-intro">Preencha seus dados para liberar a votação. Cada e-mail pode registrar um voto.</p>
        <form class="votacao-cadastro-form js-votacao-cadastro-form" method="post" action="#" novalidate>
            <div class="votacao-cadastro-grid">
                <label class="votacao-field">
                    <span class="votacao-field-label">Nome completo</span>
                    <input type="text" name="nome_completo" class="votacao-input" required autocomplete="name" maxlength="255">
                </label>
                <label class="votacao-field">
                    <span class="votacao-field-label">Cidade</span>
                    <input type="text" name="cidade" class="votacao-input" required autocomplete="address-level2" maxlength="120">
                </label>
                <label class="votacao-field">
                    <span class="votacao-field-label">Estado (UF)</span>
                    <select name="estado" class="votacao-input" required>
                        <option value="" disabled selected>Selecione</option>
                        <?php foreach ($ufs as $sigla => $nome_uf) : ?>
                            <option value="<?php echo esc_attr($sigla); ?>"><?php echo esc_html($sigla . ' — ' . $nome_uf); ?></option>
                        <?php endforeach; ?>
                    </select>
                </label>
                <label class="votacao-field votacao-field-span">
                    <span class="votacao-field-label">E-mail</span>
                    <input type="email" name="email" class="votacao-input" required autocomplete="email" maxlength="255">
                </label>
            </div>
            <button type="submit" class="votacao-btn votacao-btn-cadastro js-votacao-cadastro-submit">Continuar para votação</button>
        </form>
        <p class="votacao-cadastro-msg js-votacao-cadastro-msg" role="status" aria-live="polite"></p>
        <p class="votacao-aviso-lei" role="note"><?php echo esc_html($votacao_aviso_lei); ?></p>
    </section>

    <div class="votacao-fim js-votacao-fim" hidden>
        <p class="votacao-fim-texto">Obrigado — seu voto foi registrado.</p>
        <button type="button" class="votacao-btn votacao-fim-voltar js-votacao-fim-voltar">
            <?php esc_html_e('Voltar ao cadastro', 'tema-personalizado'); ?>
        </button>
        <p class="votacao-aviso-lei" role="note"><?php echo esc_html($votacao_aviso_lei); ?></p>
    </div>

    <div class="votacao-urna js-votacao-urna" hidden>
    <!-- AdSense: topo — substitua o span pelo script do AdSense quando for publicar -->
    <aside class="adsense-slot adsense-top" id="adsense-top" aria-label="Publicidade">
        <span class="adsense-label">Espaço AdSense (topo)</span>
    </aside>

    <div class="votacao-grid">
        <article class="votacao-card" data-candidato="lula">
            <div class="votacao-foto-wrap">
                <!-- Substitua por sua imagem: coloque lula.jpg em assets/candidatos/ ou altere o src -->
                <img
                    class="votacao-foto"
                    src="<?php echo esc_url($uri_lula); ?>"
                    alt="<?php esc_attr_e('Candidato Lula', 'tema-personalizado'); ?>"
                    width="280"
                    height="320"
                    loading="lazy"
                >
            </div>
            <h2 class="votacao-nome">Lula</h2>
            <p class="votacao-contagem" data-contagem="lula"><?php echo esc_html(number_format_i18n($pct['lula'])); ?>%</p>
            <button type="button" class="votacao-btn js-votar" data-candidato="lula">Votar</button>
        </article>

        <!-- AdSense: entre os candidatos -->
        <aside class="adsense-slot adsense-meio" id="adsense-meio" aria-label="Publicidade">
            <span class="adsense-label">Espaço AdSense (lateral)</span>
        </aside>

        <article class="votacao-card" data-candidato="bolsonaro" aria-label="<?php esc_attr_e('Candidato Flavio Bolsonaro', 'tema-personalizado'); ?>">
            <div class="votacao-foto-wrap">
                <img
                    class="votacao-foto"
                    src="<?php echo esc_url($uri_bolsonaro); ?>"
                    alt="<?php esc_attr_e('Candidato Flavio Bolsonaro', 'tema-personalizado'); ?>"
                    width="280"
                    height="320"
                    loading="lazy"
                >
            </div>
            <h2 class="votacao-nome" id="votacao-nome-flavio-bolsonaro"><?php esc_html_e('Flavio Bolsonaro', 'tema-personalizado'); ?></h2>
            <p class="votacao-contagem" data-contagem="bolsonaro"><?php echo esc_html(number_format_i18n($pct['bolsonaro'])); ?>%</p>
            <button type="button" class="votacao-btn js-votar" data-candidato="bolsonaro" aria-labelledby="votacao-nome-flavio-bolsonaro"><?php esc_html_e('Votar', 'tema-personalizado'); ?></button>
        </article>
    </div>

    <p class="votacao-msg js-votacao-msg" role="status" aria-live="polite"></p>
    <p class="votacao-aviso-lei" role="note"><?php echo esc_html($votacao_aviso_lei); ?></p>
    </div>

    <!-- AdSense: rodapé da página -->
    <aside class="adsense-slot adsense-rodape" id="adsense-rodape" aria-label="Publicidade">
        <span class="adsense-label">Espaço AdSense (rodapé)</span>
    </aside>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <?php if (get_the_content()) : ?>
            <div class="votacao-conteudo entry-content">
                <?php the_content(); ?>
            </div>
        <?php endif; ?>
    <?php endwhile; endif; ?>
</main>
<?php
get_footer();
