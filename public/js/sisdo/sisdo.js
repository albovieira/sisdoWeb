/**
 * Created by albov on 10/09/2015.
 */

function sisdoAjax(url, type, data, callbackSuccess, targetId, extraParam) {

    extraParam = extraParam || {};

    return $.ajax($.extend(extraParam, {
        type: type,
        url: url,
        data: data,
        success: function (dataRet) {
            if (typeof callbackSuccess == 'function') {
                callbackSuccess(dataRet);
            }
            if (targetId != undefined) {
                jQuery(targetId).html(dataRet);
            }
        },

        error: function (jqXHR, timeout, message) {
            var contentType = jqXHR.getResponseHeader("Content-Type");
            if (jqXHR.status === 200 && contentType.toLowerCase().indexOf("text/html") >= 0) {
                window.location.reload();
            } else {
                //var msg = Mensagens.MERRO + '<br>' + Mensagens.MERROINSTRUCAO;
                var msg = 'Ocorreu um erro ao tentar realizar a opera��o <br> <pre>' + jqXHR.responseText + '</pre>';
                showMessages(msg, 'danger');
            }
        }
    }));
}

$(document).ajaxStart(function () {
    $('#preloading').show();
});
//
$(document).ajaxStop(function () {
    $('#preloading').hide();
});

$(document).ready(function(){/* off-canvas sidebar toggle */

    $('[data-toggle=offcanvas]').click(function() {
        $(this).toggleClass('visible-xs text-center');
        $(this).find('i').toggleClass('glyphicon-chevron-right glyphicon-chevron-left');
        $('.row-offcanvas').toggleClass('active');
        $('#lg-menu').toggleClass('hidden-xs').toggleClass('visible-xs');
        $('#xs-menu').toggleClass('visible-xs').toggleClass('hidden-xs');
        $('#btnShow').toggle();
    });

    $('#lg-menu li').removeClass('active');

    $('#lg-menu li a').each(function (index, value) {
        //console.log($(this).attr('href'));
        var linkItemMenu = $(this).attr('href');

        if (linkItemMenu == window.location.pathname) {
            $(this).parent().addClass('active');

        }

    });

});

$(window).on("resize", function () {
    var newWidth = $("#jqGrid").closest(".ui-jqgrid").parent().width();
    $("#jqGrid").jqGrid("setGridWidth", newWidth, true);

});