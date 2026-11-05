/**
 * Visor de la OC AICO con marcas (anotaciones) sobre el PDF.
 *
 * - Renderiza cada pagina del PDF con PDF.js en un canvas.
 * - Encima coloca otro canvas transparente donde se dibujan las marcas.
 * - Las marcas se guardan como JSON (coordenadas normalizadas 0..1) en el
 *   campo oculto Revision_Marcas, que el formulario envia al finalizar.
 * - Al reabrir la revision se restauran las marcas guardadas.
 */
(function () {
    'use strict';

    function init() {
        var cfg = window.OCRevisionMarcas || {};
        var container = document.getElementById('pdfMarcasContenedor');
        var hidden = document.getElementById('Revision_Marcas');
        var form = document.getElementById('OC');

        if (!container || !cfg.pdfUrl || !window.pdfjsLib) {
            if (container) {
                container.innerHTML = '<div id="pdfMarcasMensaje">No se pudo cargar el visor del PDF.</div>';
            }
            return;
        }

        if (hidden) {
            hidden.value = JSON.stringify(Array.isArray(cfg.marcas) ? cfg.marcas : []);
        }

        pdfjsLib.GlobalWorkerOptions.workerSrc = cfg.workerSrc;

        var loaded = false;

        var state = {
            tool: 'free',
            color: '#e00000',
            width: 2.4,
            pages: [],
            current: null,
            drawing: false,
            activePage: null
        };

        var message = document.getElementById('pdfMarcasMensaje');

        function clamp01(value) {
            return Math.min(1, Math.max(0, value));
        }

        function position(event, page) {
            var rect = page.overlay.getBoundingClientRect();
            return [
                clamp01((event.clientX - rect.left) / rect.width),
                clamp01((event.clientY - rect.top) / rect.height)
            ];
        }

        function lineWidth(stroke, page) {
            return Math.max(1, (stroke.width || 0.003) * page.width);
        }

        function drawStroke(ctx, stroke, page) {
            var width = page.width;
            var height = page.height;

            ctx.strokeStyle = stroke.color || '#e00000';
            ctx.lineWidth = lineWidth(stroke, page);
            ctx.lineCap = 'round';
            ctx.lineJoin = 'round';

            if (stroke.type === 'free') {
                var points = Array.isArray(stroke.points) ? stroke.points : [];

                if (!points.length) {
                    return;
                }

                if (points.length === 1) {
                    ctx.beginPath();
                    ctx.arc(points[0][0] * width, points[0][1] * height, ctx.lineWidth / 2, 0, Math.PI * 2);
                    ctx.fillStyle = ctx.strokeStyle;
                    ctx.fill();
                    return;
                }

                ctx.beginPath();
                ctx.moveTo(points[0][0] * width, points[0][1] * height);
                for (var i = 1; i < points.length; i++) {
                    ctx.lineTo(points[i][0] * width, points[i][1] * height);
                }
                ctx.stroke();
                return;
            }

            var x1 = (stroke.x1 || 0) * width;
            var y1 = (stroke.y1 || 0) * height;
            var x2 = (stroke.x2 || 0) * width;
            var y2 = (stroke.y2 || 0) * height;

            if (stroke.type === 'rect') {
                ctx.strokeRect(Math.min(x1, x2), Math.min(y1, y2), Math.abs(x2 - x1), Math.abs(y2 - y1));
            } else if (stroke.type === 'ellipse') {
                ctx.beginPath();
                ctx.ellipse(
                    (x1 + x2) / 2,
                    (y1 + y2) / 2,
                    Math.abs(x2 - x1) / 2,
                    Math.abs(y2 - y1) / 2,
                    0,
                    0,
                    Math.PI * 2
                );
                ctx.stroke();
            }
        }

        function redraw(page, preview) {
            var ctx = page.overlay.getContext('2d');
            ctx.setTransform(1, 0, 0, 1, 0, 0);
            ctx.clearRect(0, 0, page.overlay.width, page.overlay.height);
            ctx.scale(page.dpr, page.dpr);

            page.strokes.forEach(function (stroke) {
                drawStroke(ctx, stroke, page);
            });

            if (preview) {
                drawStroke(ctx, preview, page);
            }
        }

        function round(value) {
            return Math.round(value * 10000) / 10000;
        }

        function serialize() {
            var data = [];

            state.pages.forEach(function (page) {
                if (!page.strokes.length) {
                    return;
                }

                data.push({
                    page: page.number,
                    strokes: page.strokes.map(function (stroke) {
                        if (stroke.type === 'free') {
                            return {
                                type: 'free',
                                color: stroke.color,
                                width: stroke.width,
                                points: stroke.points.map(function (point) {
                                    return [round(point[0]), round(point[1])];
                                })
                            };
                        }

                        return {
                            type: stroke.type,
                            color: stroke.color,
                            width: stroke.width,
                            x1: round(stroke.x1),
                            y1: round(stroke.y1),
                            x2: round(stroke.x2),
                            y2: round(stroke.y2)
                        };
                    })
                });
            });

            return JSON.stringify(data);
        }

        function updateHidden() {
            if (hidden) {
                hidden.value = serialize();
            }

            var count = state.pages.reduce(function (total, page) {
                return total + page.strokes.length;
            }, 0);

            var label = document.getElementById('marcaEstado');
            if (label) {
                label.textContent = count + (count === 1 ? ' marca' : ' marcas');
            }
        }

        function attach(page) {
            var overlay = page.overlay;

            overlay.addEventListener('pointerdown', function (event) {
                if (event.button !== undefined && event.button !== 0) {
                    return;
                }

                event.preventDefault();

                try {
                    overlay.setPointerCapture(event.pointerId);
                } catch (error) {
                    // Navegadores sin soporte de captura: se sigue dibujando igual.
                }

                state.drawing = true;
                state.activePage = page;

                var point = position(event, page);

                if (state.tool === 'free') {
                    state.current = {
                        type: 'free',
                        color: state.color,
                        width: state.width / page.width,
                        points: [point]
                    };
                } else {
                    state.current = {
                        type: state.tool,
                        color: state.color,
                        width: state.width / page.width,
                        x1: point[0],
                        y1: point[1],
                        x2: point[0],
                        y2: point[1]
                    };
                }

                redraw(page, state.current);
            });

            overlay.addEventListener('pointermove', function (event) {
                if (!state.drawing || state.activePage !== page || !state.current) {
                    return;
                }

                event.preventDefault();

                var point = position(event, page);

                if (state.current.type === 'free') {
                    state.current.points.push(point);
                } else {
                    state.current.x2 = point[0];
                    state.current.y2 = point[1];
                }

                redraw(page, state.current);
            });

            function finish() {
                if (!state.drawing || state.activePage !== page || !state.current) {
                    return;
                }

                state.drawing = false;

                var stroke = state.current;
                state.current = null;

                var keep = stroke.type === 'free'
                    ? stroke.points.length > 1
                    : Math.abs(stroke.x2 - stroke.x1) > 0.003 || Math.abs(stroke.y2 - stroke.y1) > 0.003;

                if (keep) {
                    page.strokes.push(stroke);
                }

                redraw(page);
                updateHidden();
            }

            overlay.addEventListener('pointerup', finish);
            overlay.addEventListener('pointercancel', finish);
        }

        function loadMarcas(data) {
            if (!Array.isArray(data)) {
                return;
            }

            data.forEach(function (item) {
                if (!item || !Array.isArray(item.strokes)) {
                    return;
                }

                var page = state.pages.filter(function (candidate) {
                    return candidate.number === item.page;
                })[0];

                if (!page) {
                    return;
                }

                page.strokes = item.strokes;
                redraw(page);
            });

            updateHidden();
        }

        function bindToolbar() {
            var toolButtons = document.querySelectorAll('[data-marca-tool]');

            Array.prototype.forEach.call(toolButtons, function (button) {
                button.addEventListener('click', function () {
                    state.tool = button.getAttribute('data-marca-tool');

                    Array.prototype.forEach.call(toolButtons, function (candidate) {
                        candidate.classList.remove('active');
                    });

                    button.classList.add('active');
                });
            });

            var colorInput = document.getElementById('marcaColor');
            if (colorInput) {
                colorInput.addEventListener('input', function () {
                    state.color = colorInput.value;
                });
            }

            var undo = document.getElementById('marcaDeshacer');
            if (undo) {
                undo.addEventListener('click', function () {
                    for (var i = state.pages.length - 1; i >= 0; i--) {
                        if (state.pages[i].strokes.length) {
                            state.pages[i].strokes.pop();
                            redraw(state.pages[i]);
                            updateHidden();
                            return;
                        }
                    }
                });
            }

            var clear = document.getElementById('marcaLimpiar');
            if (clear) {
                clear.addEventListener('click', function () {
                    var doClear = function () {
                        state.pages.forEach(function (page) {
                            page.strokes = [];
                            redraw(page);
                        });
                        updateHidden();
                    };

                    if (window.Swal) {
                        Swal.fire({
                            title: '¿Limpiar todas las marcas?',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Sí, limpiar',
                            cancelButtonText: 'Cancelar'
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                doClear();
                            }
                        });
                    } else {
                        doClear();
                    }
                });
            }
        }

        function renderPdf() {
            pdfjsLib.getDocument({
                url: cfg.pdfUrl,
                standardFontDataUrl: cfg.standardFontDataUrl
            }).promise.then(function (pdf) {
                if (message && message.parentNode) {
                    message.parentNode.removeChild(message);
                }

                var chain = Promise.resolve();

                for (let number = 1; number <= pdf.numPages; number++) {
                    chain = chain.then(function () {
                        return pdf.getPage(number).then(function (page) {
                            var unscaled = page.getViewport({ scale: 1 });
                            var available = Math.max(320, container.clientWidth - 20);
                            var scale = available / unscaled.width;
                            var viewport = page.getViewport({ scale: scale });
                            var dpr = window.devicePixelRatio || 1;

                            var wrapper = document.createElement('div');
                            wrapper.className = 'pdf-marca-pagina';
                            wrapper.style.width = viewport.width + 'px';
                            wrapper.style.height = viewport.height + 'px';

                            var base = document.createElement('canvas');
                            base.width = Math.floor(viewport.width * dpr);
                            base.height = Math.floor(viewport.height * dpr);
                            base.style.width = viewport.width + 'px';
                            base.style.height = viewport.height + 'px';

                            var overlay = document.createElement('canvas');
                            overlay.className = 'pdf-marca-dibujo';
                            overlay.width = base.width;
                            overlay.height = base.height;
                            overlay.style.width = viewport.width + 'px';
                            overlay.style.height = viewport.height + 'px';

                            wrapper.appendChild(base);
                            wrapper.appendChild(overlay);
                            container.appendChild(wrapper);

                            var entry = {
                                number: number,
                                wrapper: wrapper,
                                base: base,
                                overlay: overlay,
                                width: viewport.width,
                                height: viewport.height,
                                dpr: dpr,
                                strokes: []
                            };

                            state.pages.push(entry);
                            attach(entry);

                            var ctx = base.getContext('2d');
                            ctx.scale(dpr, dpr);

                            return page.render({ canvasContext: ctx, viewport: viewport }).promise.then(function () {
                                redraw(entry);
                            });
                        });
                    });
                }

                return chain;
            }).then(function () {
                loaded = true;
                loadMarcas(cfg.marcas || []);
                updateHidden();
            }).catch(function (error) {
                console.error('Error al cargar el PDF:', error);
                container.innerHTML = '<div id="pdfMarcasMensaje">No se pudo cargar el PDF de la orden.</div>';
            });
        }

        bindToolbar();

        if (form) {
            form.addEventListener('submit', function () {
                if (loaded) {
                    updateHidden();
                }
            });
        }

        renderPdf();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
