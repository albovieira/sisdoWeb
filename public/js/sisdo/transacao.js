var transacao = {

    init: function () {
        this.bindFinalizarDoacao();
        this.submitMsg();
    },

    bindFinalizarDoacao: function () {
        $('#btn_finalizar_doacao').click(function () {

            bootbox.dialog({
                title: "Finalizar Doacao",
                message: '<label>Observacao</label><textarea id="obs-txt" class="form-control" rows="2" cols="2"></textarea>' +
                '<input id="input-id" type="number" class="rating" min=0 max=5 step=0.5 data-size="xs" >',
                buttons: {
                    success: {
                        label: "Confirmar",
                        className: "btn-primary",
                        callback: function () {

                            var fnSuccess = function (json) {
                                if (json.retorno == 'sucesso') {
                                    showMessages('Transacao finalizaca com sucesso.', 'success');
                                    window.location.assign("/transacao/");
                                } else {
                                    showMessages('Ocorreu um erro ao realizar a operacao.', 'danger');
                                }
                            };

                            var data = {
                                'id': $('input[name="id"]').val(),
                                'rating': $('#input-id').val(),
                                'observacao': $('#obs-txt').val()
                            };
                            sisdoAjax('/transacao/finalizar-transacao', 'POST', data, fnSuccess);
                        }
                    }
                }
            });

            $("#input-id").rating({'size':'lg'});

        });
    },

    submitMsg : function () {
        $('form#messageForm').submit(function (e) {

            e.preventDefault();
            var data = $('form#messageForm').serialize();
            sisdoAjax('/transacao/salvar-msg', 'POST', data, function (json) {
                console.log(json);
                var html =
                "<div class='direct-chat-msg'>"+
                    "<div class='direct-chat-info clearfix'>"+
                    "<span class='direct-chat-name pull-left'>"+ json.idUser +"</span>"+
                "<span class='direct-chat-timestamp pull-right'>"+ json.date +"</span>"+
                    "</div>"+
                    "<img class='direct-chat-img' src='/img/pictures/sem-imagem.jpg' alt='message user image'>"+
                    "<div class='direct-chat-text'>"+ json.message +
                    "</div>" +
                "</div>";

                $('.box-body').append(html);
            });

        });
    },

    /*
     bootbox.confirm("Deseja finalizar a transacao? Fazendo isso voce confirma o recebimento do produto.", function(result) {

     if(result){
     var data = {'id': $('input[name="id"]').val()};
     sisdoAjax('/transacao/finalizar-transacao', 'GET', data, function (json) {
     if (json.retorno == 'sucesso') {
     showMessages('Transacao finalizaca com sucesso.', 'success');
     window.location.assign("/transacao/");
     } else {
     showMessages('Ocorreu um erro ao realizar a operacao.', 'danger');
     }
     });

     }
     });*/

};

$(document).ready(function () {
    transacao.init();
});