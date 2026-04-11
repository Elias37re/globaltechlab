(function () {
    'use strict';

    var LS_SESS = 'temaVotacaoSess';
    var LS_DONE = 'temaVotacaoFeito';

    var ajaxUrl = typeof temaVotacao !== 'undefined' ? temaVotacao.ajaxUrl : '';
    var nonce = typeof temaVotacao !== 'undefined' ? temaVotacao.nonce : '';

    var msgEl = document.querySelector('.js-votacao-msg');
    var botoes = document.querySelectorAll('.js-votar');
    var elCadastro = document.querySelector('.js-votacao-cadastro');
    var elCadastroForm = document.querySelector('.js-votacao-cadastro-form');
    var elCadastroMsg = document.querySelector('.js-votacao-cadastro-msg');
    var elCadastroSubmit = document.querySelector('.js-votacao-cadastro-submit');
    var elUrna = document.querySelector('.js-votacao-urna');
    var elFim = document.querySelector('.js-votacao-fim');

    function formatNum(n) {
        try {
            return new Intl.NumberFormat(document.documentElement.lang || 'pt-BR').format(n);
        } catch (e) {
            return String(n);
        }
    }

    function setMsg(text, isError) {
        if (!msgEl) return;
        msgEl.textContent = text || '';
        msgEl.classList.toggle('is-error', !!isError);
    }

    function setCadastroMsg(text, isError) {
        if (!elCadastroMsg) return;
        elCadastroMsg.textContent = text || '';
        elCadastroMsg.classList.toggle('is-error', !!isError);
    }

    function parseParticipanteId(v) {
        var n = typeof v === 'number' ? v : parseInt(String(v), 10);
        return Number.isFinite(n) && n > 0 ? n : null;
    }

    function getSess() {
        try {
            var raw = localStorage.getItem(LS_SESS);
            if (!raw) return null;
            var o = JSON.parse(raw);
            if (!o || typeof o.votoToken !== 'string') {
                return null;
            }
            var pid = parseParticipanteId(o.participanteId);
            if (pid === null) {
                return null;
            }
            if (!/^[a-f0-9]{64}$/i.test(o.votoToken)) {
                return null;
            }
            return { participanteId: pid, votoToken: o.votoToken.toLowerCase() };
        } catch (e) {
            return null;
        }
    }

    function setSess(participanteId, votoToken) {
        var pid = parseParticipanteId(participanteId);
        var tok = typeof votoToken === 'string' ? votoToken.toLowerCase().trim() : '';
        if (pid === null || !/^[a-f0-9]{64}$/i.test(tok)) {
            return;
        }
        localStorage.setItem(
            LS_SESS,
            JSON.stringify({ participanteId: pid, votoToken: tok })
        );
    }

    function clearSess() {
        localStorage.removeItem(LS_SESS);
    }

    function isFeito() {
        try {
            return localStorage.getItem(LS_DONE) === '1';
        } catch (e) {
            return false;
        }
    }

    function setFeito() {
        try {
            localStorage.setItem(LS_DONE, '1');
        } catch (e) {
            /* ignore */
        }
    }

    function atualizarContagens(data) {
        if (typeof data.lula === 'number') {
            var elL = document.querySelector('[data-contagem="lula"]');
            if (elL) elL.textContent = formatNum(data.lula) + ' votos';
        }
        if (typeof data.bolsonaro === 'number') {
            var elB = document.querySelector('[data-contagem="bolsonaro"]');
            if (elB) elB.textContent = formatNum(data.bolsonaro) + ' votos';
        }
    }

    function setHidden(el, hide) {
        if (!el) return;
        if (hide) {
            el.setAttribute('hidden', '');
            el.hidden = true;
        } else {
            el.removeAttribute('hidden');
            el.hidden = false;
        }
    }

    function applyView() {
        if (isFeito()) {
            setHidden(elCadastro, true);
            setHidden(elUrna, true);
            setHidden(elFim, false);
            return;
        }
        var sess = getSess();
        if (sess) {
            setHidden(elCadastro, true);
            setHidden(elUrna, false);
            setHidden(elFim, true);
        } else {
            setHidden(elCadastro, false);
            setHidden(elUrna, true);
            setHidden(elFim, true);
        }
    }

    function mostrarUrnaAposCadastro() {
        applyView();
        if (!elUrna || elUrna.hasAttribute('hidden')) {
            return;
        }
        requestAnimationFrame(function () {
            elUrna.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });
    }

    function enviarVoto(candidato) {
        var sess = getSess();
        if (!sess) {
            setMsg('Faça o cadastro antes de votar.', true);
            return;
        }
        if (!ajaxUrl || !nonce) {
            setMsg('Erro de configuração (AJAX).', true);
            return;
        }
        setMsg('Enviando…', false);
        botoes.forEach(function (b) {
            b.disabled = true;
        });

        var body = new URLSearchParams();
        body.set('action', 'tema_registrar_voto');
        body.set('nonce', nonce);
        body.set('candidato', candidato);
        body.set('participante_id', String(sess.participanteId));
        body.set('voto_token', sess.votoToken);

        fetch(ajaxUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
            },
            body: body.toString(),
        })
            .then(function (r) {
                return r.json();
            })
            .then(function (json) {
                if (json.success && json.data) {
                    setMsg(json.data.message || 'Voto registrado.', false);
                    atualizarContagens(json.data);
                    clearSess();
                    setFeito();
                    botoes.forEach(function (b) {
                        b.disabled = true;
                    });
                    setHidden(elUrna, true);
                    setHidden(elCadastro, true);
                    setHidden(elFim, false);
                } else {
                    var err = (json.data && json.data.message) || 'Não foi possível registrar o voto.';
                    setMsg(err, true);
                }
            })
            .catch(function () {
                setMsg('Falha de rede. Tente novamente.', true);
            })
            .finally(function () {
                botoes.forEach(function (b) {
                    if (!isFeito()) b.disabled = false;
                });
            });
    }

    if (elCadastroForm) {
        elCadastroForm.addEventListener('submit', function (ev) {
            ev.preventDefault();
            if (!ajaxUrl || !nonce) {
                setCadastroMsg('Erro de configuração (AJAX).', true);
                return;
            }
            var fd = new FormData(elCadastroForm);
            var nome = (fd.get('nome_completo') || '').toString().trim();
            var cidade = (fd.get('cidade') || '').toString().trim();
            var estado = (fd.get('estado') || '').toString().trim();
            var email = (fd.get('email') || '').toString().trim();
            if (!nome || !cidade || !estado || !email) {
                setCadastroMsg('Preencha todos os campos.', true);
                return;
            }
            setCadastroMsg('Enviando cadastro…', false);
            if (elCadastroSubmit) elCadastroSubmit.disabled = true;

            var body = new URLSearchParams();
            body.set('action', 'tema_cadastrar_participante');
            body.set('nonce', nonce);
            body.set('nome_completo', nome);
            body.set('cidade', cidade);
            body.set('estado', estado);
            body.set('email', email);

            fetch(ajaxUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                },
                body: body.toString(),
            })
                .then(function (r) {
                    return r.text().then(function (text) {
                        var parsed;
                        try {
                            parsed = JSON.parse(text);
                        } catch (e) {
                            throw new Error(
                                'Resposta inválida do servidor (não é JSON). Verifique erros PHP ou se o cadastro chegou ao WordPress.'
                            );
                        }
                        if (!r.ok) {
                            throw new Error((parsed.data && parsed.data.message) || 'Erro HTTP ' + r.status);
                        }
                        return parsed;
                    });
                })
                .then(function (json) {
                    var d = json && json.data;
                    var tok = d && d.voto_token;
                    var pid = d && parseParticipanteId(d.participante_id);
                    if (
                        json.success &&
                        d &&
                        pid !== null &&
                        typeof tok === 'string' &&
                        /^[a-f0-9]{64}$/i.test(tok.trim())
                    ) {
                        setCadastroMsg(d.message || 'Cadastro concluído.', false);
                        setSess(pid, tok);
                        mostrarUrnaAposCadastro();
                    } else {
                        var err = (json.data && json.data.message) || 'Não foi possível concluir o cadastro.';
                        setCadastroMsg(err, true);
                    }
                })
                .catch(function (err) {
                    setCadastroMsg(err.message || 'Falha de rede. Tente novamente.', true);
                })
                .finally(function () {
                    if (elCadastroSubmit) elCadastroSubmit.disabled = false;
                });
        });
    }

    botoes.forEach(function (btn) {
        btn.addEventListener('click', function () {
            var c = btn.getAttribute('data-candidato');
            if (c) enviarVoto(c);
        });
    });

    applyView();
})();
