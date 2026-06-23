/**
 * GamerMania Amazon PAAPI 5.0 Admin JS
 *
 * Implements AJAX actions for the Settings page (connectivity check)
 * and CPT Promocao Editor (automatic import and field population).
 * Compatible with both Gutenberg (Block Editor) and Classic Editor.
 */

(function($) {
	'use strict';

	// Helper to clean price format from PAAPI (e.g. "R$ 349,90" -> "349,90")
	function cleanPrice(priceStr) {
		if (!priceStr) return '';
		// Extract only numbers, comma and dot
		var match = priceStr.match(/[\d.,]+/);
		return match ? match[0] : priceStr;
	}

	$(document).ready(function() {

		/* ==========================================
		   1. Settings Page: Test Connectivity
		   ========================================== */
		$('#gamermania_btn_test_conn').on('click', function(e) {
			e.preventDefault();

			var $btn = $(this);
			var $results = $('#gamermania_test_results');
			var asin = $('#gamermania_test_asin').val().trim();

			if (!asin) {
				alert('Por favor, insira um código ASIN para testar.');
				return;
			}

			// Loading state
			$btn.prop('disabled', true).text(gamermaniaAmazon.txt_loading);
			$results.html(
				'<div class="amazon-admin-loading">' +
				'<span class="spinner is-active" style="float:none; margin:0 10px 0 0;"></span>' +
				'Conectando com a Amazon PAAPI 5.0...' +
				'</div>'
			);

			$.ajax({
				url: gamermaniaAmazon.ajax_url,
				type: 'POST',
				data: {
					action: 'gamermania_amazon_test_connection',
					asin: asin,
					nonce: gamermaniaAmazon.test_nonce
				},
				success: function(response) {
					if (response.success) {
						var item = response.data;
						var discountBadge = item.discount_pct > 0 ? '<span class="item-discount">-' + item.discount_pct + '%</span>' : '';
						
						var html = '<div class="amazon-test-success-card animate-fade-in">';
						html += '  <div class="test-header"><i class="dashicons dashicons-yes-alt" style="color:#10b981;"></i> <strong>Conexão efetuada com sucesso!</strong></div>';
						html += '  <div class="test-item-body">';
						if (item.image_url) {
							html += '    <div class="test-item-thumb"><img src="' + item.image_url + '" alt="" />' + discountBadge + '</div>';
						}
						html += '    <div class="test-item-details">';
						html += '      <h4>' + item.title + '</h4>';
						html += '      <p class="test-item-asin"><strong>ASIN:</strong> ' + item.asin + '</p>';
						if (item.old_price) {
							html += '      <p class="test-item-price-old"><strong>Preço Antigo:</strong> <del>' + item.old_price + '</del></p>';
						}
						html += '      <p class="test-item-price-new"><strong>Preço Novo:</strong> <span>' + (item.price ? item.price : 'N/A') + '</span></p>';
						html += '      <p class="test-item-link"><strong>URL Afiliado:</strong> <a href="' + item.url + '" target="_blank" class="truncate">' + item.url + '</a></p>';
						html += '    </div>';
						html += '  </div>';
						html += '</div>';
						
						$results.html(html);
					} else {
						var errMsg = response.data && response.data.message ? response.data.message : gamermaniaAmazon.txt_error;
						$results.html(
							'<div class="amazon-test-error-card animate-fade-in">' +
							'  <div class="test-header"><i class="dashicons dashicons-warning" style="color:#ef4444;"></i> <strong>Falha na Conexão</strong></div>' +
							'  <p class="error-msg">' + errMsg + '</p>' +
							'  <p class="error-tip">Dica: Verifique se as credenciais da AWS (Access Key e Secret Key) e a Tag de Afiliado estão corretas, e se o Marketplace selecionado corresponde ao país do ASIN buscado.</p>' +
							'</div>'
						);
					}
				},
				error: function() {
					$results.html(
						'<div class="amazon-test-error-card animate-fade-in">' +
						'  <div class="test-header"><i class="dashicons dashicons-warning" style="color:#ef4444;"></i> <strong>Erro Inesperado</strong></div>' +
						'  <p class="error-msg">Não foi possível completar a requisição AJAX no servidor.</p>' +
						'</div>'
					);
				},
				complete: function() {
					$btn.prop('disabled', false).text('Testar Conexão');
				}
			});
		});

		/* ==========================================
		   2. Editor CPT: Import Product from ASIN
		   ========================================== */
		$('#gamermania_btn_import_asin').on('click', function(e) {
			e.preventDefault();

			var $btn = $(this);
			var asin = $('#gamermania_import_asin_input').val().trim();
			var $status = $('#gamermania_import_status');
			
			// Get post ID from WordPress editor
			var postId = $('#post_ID').val() || 0;

			if (!asin) {
				alert('Por favor, insira um código ASIN válido.');
				return;
			}

			// Get checkbox options
			var optTitle = $('#import_opt_title').is(':checked');
			var optPriceOld = $('#import_opt_price_old').is(':checked');
			var optPriceNew = $('#import_opt_price_new').is(':checked');
			var optLink = $('#import_opt_link').is(':checked');
			var optImage = $('#import_opt_image').is(':checked');

			// Loading state
			$btn.prop('disabled', true);
			$status.show()
				.removeClass('success error')
				.addClass('info')
				.html('<span class="spinner is-active" style="float:none; margin:0 10px 0 0; vertical-align:middle;"></span> ' + gamermaniaAmazon.txt_loading);

			$.ajax({
				url: gamermaniaAmazon.ajax_url,
				type: 'POST',
				data: {
					action: 'gamermania_amazon_import_asin',
					asin: asin,
					post_id: postId,
					sideload_image: optImage,
					nonce: gamermaniaAmazon.import_nonce
				},
				success: function(response) {
					if (response.success) {
						var item = response.data;

						// 1. Populate Title
						if (optTitle && item.title) {
							// Check Classic Editor Title
							var $classicTitle = $('#title');
							if ($classicTitle.length) {
								$classicTitle.val(item.title).trigger('change');
							}
							
							// Check Gutenberg Title
							if (window.wp && window.wp.data && window.wp.data.dispatch) {
								window.wp.data.dispatch('core/editor').editPost({ title: item.title });
							}
						}

						// 2. Populate Old Price (Preço Antigo)
						if (optPriceOld && item.old_price) {
							var priceOldClean = cleanPrice(item.old_price);
							var $precoAntigo = $('#preco_antigo');
							if ($precoAntigo.length) {
								$precoAntigo.val(priceOldClean).trigger('change');
							}
							
							// Gutenberg custom fields meta update fallback
							if (window.wp && window.wp.data && window.wp.data.dispatch) {
								window.wp.data.dispatch('core/editor').editPost({
									meta: { _preco_antigo: priceOldClean }
								});
							}
						}

						// 3. Populate New Price (Preço Novo)
						if (optPriceNew && item.price) {
							var priceNewClean = cleanPrice(item.price);
							var $precoNovo = $('#preco_novo');
							if ($precoNovo.length) {
								$precoNovo.val(priceNewClean).trigger('change');
							}

							// Gutenberg custom fields meta update fallback
							if (window.wp && window.wp.data && window.wp.data.dispatch) {
								window.wp.data.dispatch('core/editor').editPost({
									meta: { _preco_novo: priceNewClean }
								});
							}
						}

						// 4. Populate Affiliate Link (Link do Produto)
						if (optLink && item.url) {
							var $linkAfiliado = $('#link_afiliado');
							if ($linkAfiliado.length) {
								$linkAfiliado.val(item.url).trigger('change');
							}

							// Gutenberg custom fields meta update fallback
							if (window.wp && window.wp.data && window.wp.data.dispatch) {
								window.wp.data.dispatch('core/editor').editPost({
									meta: { _link_afiliado: item.url }
								});
							}
						}

						// 5. Update Featured Image in UI
						if (optImage) {
							if (item.thumbnail_html) {
								// Classic Editor update
								var $postImageDiv = $('#postimagediv .inside');
								if ($postImageDiv.length) {
									$postImageDiv.html(item.thumbnail_html);
									// Re-add remove link if missing or standard
									if ($('#remove-post-thumbnail').length === 0) {
										$postImageDiv.append('<p class="hide-if-no-js"><a href="#" id="remove-post-thumbnail">Remover imagem de destaque</a></p>');
									}
								}
							}

							// Gutenberg Editor update
							if (window.wp && window.wp.data && window.wp.data.dispatch && item.attachment_id) {
								window.wp.data.dispatch('core/editor').editPost({
									featured_media: item.attachment_id
								});
							}

							if (item.image_sideload_error) {
								console.error('Erro no download da imagem:', item.image_sideload_error);
							}
						}

						$status.removeClass('info error')
							.addClass('success')
							.html('<i class="dashicons dashicons-yes" style="vertical-align:middle;"></i> ' + gamermaniaAmazon.txt_import_complete);
					} else {
						var errMsg = response.data && response.data.message ? response.data.message : gamermaniaAmazon.txt_error;
						$status.removeClass('info success')
							.addClass('error')
							.html('<i class="dashicons dashicons-warning" style="vertical-align:middle;"></i> ' + errMsg);
					}
				},
				error: function() {
					$status.removeClass('info success')
						.addClass('error')
						.html('<i class="dashicons dashicons-warning" style="vertical-align:middle;"></i> Erro na comunicação de rede com o servidor.');
				},
				complete: function() {
					$btn.prop('disabled', false);
				}
			});
		});

	});

})(jQuery);
