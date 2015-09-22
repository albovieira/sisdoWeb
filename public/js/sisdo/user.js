var user = {

    init: function () {

        this.buscaCep();
        //this.bindChangeUf();
    },

    buscaCep: function () {

        $('#btn-busca-cep').click(function (e) {
            e.preventDefault();

            var url = 'http://cep.correiocontrol.com.br/' + $('input[name="zipCode"]').val().replace('.', '').replace('-', '') + '.json';
            sisdoAjax(url,'GET','',
                function (data) {

                    console.log(data);
                    $('input[name="street"]').val(data.logradouro);
                    $('input[name="neighborhood"]').val(data.bairro);
                    $('input[name="uf"]').val(data.uf);
                    $('input[name="city"]').val(data.localidade);
                }
            );
        });
    },
    
    bindChangeUf: function () {
        $('select[name="uf"]').change(function () {

            sisdoAjax('');

        });
    }
    
};

$(document).ready(function () {
    user.buscaCep();
});