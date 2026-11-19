/* RentCar UB · comportamiento del front end (JavaScript puro) */
document.addEventListener('DOMContentLoaded', function () {

    // 1. Confirmación antes de enviar formularios marcados con data-confirmar
    document.querySelectorAll('form[data-confirmar]').forEach(function (form) {
        form.addEventListener('submit', function (ev) {
            if (!window.confirm(form.dataset.confirmar)) {
                ev.preventDefault();
            }
        });
    });

    // 2. Buscador en vivo para tablas (input con data-filtro="id-de-la-tabla")
    document.querySelectorAll('input[data-filtro]').forEach(function (campo) {
        var tabla = document.getElementById(campo.dataset.filtro);
        if (!tabla) { return; }
        campo.addEventListener('input', function () {
            var texto = campo.value.trim().toLowerCase();
            tabla.querySelectorAll('tbody tr').forEach(function (fila) {
                fila.style.display = fila.textContent.toLowerCase().indexOf(texto) === -1 ? 'none' : '';
            });
        });
    });

    // 3. Resumen en vivo del alquiler (días y total estimado)
    var form = document.getElementById('form-alquiler');
    if (form) {
        var vehiculo = form.querySelector('#vehiculo_id');
        var inicio   = form.querySelector('#fecha_inicio');
        var fin      = form.querySelector('#fecha_fin');
        var outDias  = document.getElementById('resumen-dias');
        var outTotal = document.getElementById('resumen-total');

        var pesos = function (n) {
            return '$' + Math.round(n).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        };

        var actualizar = function () {
            var opcion = vehiculo.options[vehiculo.selectedIndex];
            var tarifa = opcion ? parseFloat(opcion.dataset.tarifa) : NaN;
            var d1 = new Date(inicio.value), d2 = new Date(fin.value);

            if (isNaN(tarifa) || isNaN(d1) || isNaN(d2) || d2 < d1) {
                outDias.textContent = '—';
                outTotal.textContent = '—';
                return;
            }
            var dias = Math.max(1, Math.round((d2 - d1) / 86400000)); // misma regla que el back end
            outDias.textContent = dias;
            outTotal.textContent = pesos(dias * tarifa);
        };

        // La fecha de devolución nunca puede ser anterior al inicio
        inicio.addEventListener('change', function () {
            fin.min = inicio.value;
            if (fin.value && fin.value < inicio.value) { fin.value = inicio.value; }
            actualizar();
        });
        [vehiculo, fin].forEach(function (el) { el.addEventListener('change', actualizar); });
        actualizar();
    }

    // 4. Las alertas desaparecen solas a los 5 segundos
    document.querySelectorAll('.alert').forEach(function (a) {
        setTimeout(function () { a.style.display = 'none'; }, 5000);
    });
});
