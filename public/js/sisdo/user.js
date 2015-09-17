var user = {

    init: function () {

        this.buscaCep();
    },

    buscaCep: function () {

        $('#btn-busca-cep').click(function (e) {
            e.preventDefault();
            $.ajax({
                type: 'GET',
                data: '',
                url: 'http://cep.correiocontrol.com.br/' + $('input[name="zipCode"]').val().replace('.', '').replace('-', '') + '.json',
                success: function (data) {

                    $('input[name="street"]').val(data.logradouro);
                    $('input[name="neighborhood"]').val(data.bairro);
                    $('input[name="uf"]').val(data.uf);
                    $('input[name="city"]').val(data.localidade);
                }
            });
        });
    }

}

$(document).ready(function () {
    user.buscaCep();
});