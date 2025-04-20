document.addEventListener('DOMContentLoaded', function () {
    const campoValor = document.getElementById('valor');

    if (campoValor) {
        campoValor.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');

            value = (value / 100).toFixed(2);
            value = value.replace('.', ',');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

            e.target.value = 'R$ ' + value;
        });
    }
});