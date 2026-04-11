/* global Chart, html2pdf, temaVotacaoRelatorio */
(function () {
    'use strict';

    var cfg = typeof temaVotacaoRelatorio !== 'undefined' ? temaVotacaoRelatorio : {};
    var byUf = cfg.byUf || [];
    var byCity = cfg.byCity || [];
    var i18n = cfg.i18n || {};
    var chartUf = null;
    var chartCidade = null;

    function el(id) {
        return document.getElementById(id);
    }

    function buildCityDataForUf(uf) {
        var rows = byCity.filter(function (r) {
            return String(r.uf) === String(uf);
        });
        var labels = rows.map(function (r) {
            return r.cidade;
        });
        var lula = rows.map(function (r) {
            return r.lula;
        });
        var bolsonaro = rows.map(function (r) {
            return r.bolsonaro;
        });
        return { labels: labels, lula: lula, bolsonaro: bolsonaro };
    }

    function initChartUf() {
        var canvas = el('tema-votacao-chart-uf');
        if (!canvas || typeof Chart === 'undefined') {
            return;
        }
        var labels = byUf.map(function (r) {
            return r.uf;
        });
        var dataLula = byUf.map(function (r) {
            return r.lula;
        });
        var dataBol = byUf.map(function (r) {
            return r.bolsonaro;
        });
        chartUf = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: i18n.lula || 'Lula',
                        data: dataLula,
                        backgroundColor: 'rgba(220, 38, 38, 0.75)',
                        borderColor: 'rgb(185, 28, 28)',
                        borderWidth: 1,
                    },
                    {
                        label: i18n.bolsonaro || 'Bolsonaro',
                        data: dataBol,
                        backgroundColor: 'rgba(37, 99, 235, 0.75)',
                        borderColor: 'rgb(29, 78, 216)',
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    title: {
                        display: true,
                        text: i18n.titleUf || '',
                    },
                    legend: { position: 'top' },
                },
                scales: {
                    x: { stacked: false, ticks: { maxRotation: 45, minRotation: 0 } },
                    y: {
                        beginAtZero: true,
                        ticks: { stepSize: 1 },
                    },
                },
            },
        });
    }

    function updateChartCidade(uf) {
        var canvas = el('tema-votacao-chart-cidade');
        if (!canvas || typeof Chart === 'undefined') {
            return;
        }
        var d = buildCityDataForUf(uf);
        if (chartCidade) {
            chartCidade.data.labels = d.labels;
            chartCidade.data.datasets[0].data = d.lula;
            chartCidade.data.datasets[1].data = d.bolsonaro;
            chartCidade.options.plugins.title.text =
                (i18n.titleCidade || '') + (uf ? ' — ' + uf : '');
            chartCidade.update();
            return;
        }
        chartCidade = new Chart(canvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: d.labels,
                datasets: [
                    {
                        label: i18n.lula || 'Lula',
                        data: d.lula,
                        backgroundColor: 'rgba(220, 38, 38, 0.75)',
                        borderColor: 'rgb(185, 28, 28)',
                        borderWidth: 1,
                    },
                    {
                        label: i18n.bolsonaro || 'Bolsonaro',
                        data: d.bolsonaro,
                        backgroundColor: 'rgba(37, 99, 235, 0.75)',
                        borderColor: 'rgb(29, 78, 216)',
                        borderWidth: 1,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    title: {
                        display: true,
                        text: (i18n.titleCidade || '') + (uf ? ' — ' + uf : ''),
                    },
                    legend: { position: 'top' },
                },
                scales: {
                    x: { ticks: { maxRotation: 60, minRotation: 30, autoSkip: true, maxTicksLimit: 24 } },
                    y: { beginAtZero: true, ticks: { stepSize: 1 } },
                },
            },
        });
    }

    function firstUfWithCities() {
        if (!byUf.length) {
            return '';
        }
        for (var i = 0; i < byUf.length; i++) {
            var uf = byUf[i].uf;
            if (buildCityDataForUf(uf).labels.length) {
                return uf;
            }
        }
        return byUf[0].uf;
    }

    function onUfFilterChange() {
        var sel = el('tema-votacao-filtro-uf');
        if (!sel) {
            return;
        }
        updateChartCidade(sel.value);
    }

    function snapshotChartsForPdf(source) {
        var backups = [];
        source.querySelectorAll('canvas').forEach(function (canvas) {
            var chart = typeof Chart !== 'undefined' ? Chart.getChart(canvas) : null;
            var dataUrl = chart && typeof chart.toBase64Image === 'function'
                ? chart.toBase64Image()
                : canvas.toDataURL('image/png');
            var img = document.createElement('img');
            img.src = dataUrl;
            img.alt = '';
            img.className = 'tema-votacao-chart-snapshot';
            img.style.maxWidth = '100%';
            img.style.height = 'auto';
            canvas.parentNode.insertBefore(img, canvas);
            canvas.style.display = 'none';
            backups.push({ canvas: canvas, img: img });
        });
        return backups;
    }

    function restoreChartsAfterPdf(backups) {
        backups.forEach(function (b) {
            if (b.img && b.img.parentNode) {
                b.img.remove();
            }
            if (b.canvas) {
                b.canvas.style.display = '';
            }
        });
        if (chartUf) {
            chartUf.resize();
        }
        if (chartCidade) {
            chartCidade.resize();
        }
    }

    function doPrint() {
        window.print();
    }

    function doPdf() {
        if (typeof html2pdf === 'undefined') {
            window.alert(i18n.pdfUnavailable || 'PDF indisponível.');
            return;
        }
        var source = el('tema-votacao-relatorio');
        if (!source) {
            return;
        }
        source.querySelectorAll('.tema-votacao-no-pdf').forEach(function (n) {
            n.setAttribute('data-was-hidden', n.style.display);
            n.style.display = 'none';
        });

        var backups = snapshotChartsForPdf(source);

        var opt = {
            margin: 10,
            filename: (cfg.pdfFilename || 'relatorio-votacao') + '.pdf',
            image: { type: 'jpeg', quality: 0.95 },
            html2canvas: { scale: 2, useCORS: true, logging: false },
            jsPDF: { unit: 'mm', format: 'a4', orientation: 'portrait' },
            pagebreak: { mode: ['avoid-all', 'css', 'legacy'] },
        };

        function finish() {
            restoreChartsAfterPdf(backups);
            source.querySelectorAll('.tema-votacao-no-pdf').forEach(function (n) {
                var prev = n.getAttribute('data-was-hidden');
                n.style.display = prev || '';
                n.removeAttribute('data-was-hidden');
            });
        }

        html2pdf()
            .set(opt)
            .from(source)
            .save()
            .then(finish)
            .catch(finish);
    }

    function bind() {
        var btnPrint = el('tema-votacao-btn-print');
        var btnPdf = el('tema-votacao-btn-pdf');
        var sel = el('tema-votacao-filtro-uf');
        if (btnPrint) {
            btnPrint.addEventListener('click', doPrint);
        }
        if (btnPdf) {
            btnPdf.addEventListener('click', doPdf);
        }
        if (sel) {
            sel.addEventListener('change', onUfFilterChange);
        }
    }

    function init() {
        if (!byUf.length && !byCity.length) {
            return;
        }
        initChartUf();
        var sel = el('tema-votacao-filtro-uf');
        var defaultUf = sel && sel.value ? sel.value : firstUfWithCities();
        if (sel && defaultUf) {
            sel.value = defaultUf;
        }
        updateChartCidade(defaultUf || (sel ? sel.value : ''));
        bind();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
