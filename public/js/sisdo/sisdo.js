/**
 * Created by albov on 10/09/2015.
 */


function sisdoAjax(url, type, callback, element, data){
    return $.ajax({
        type: type || 'GET',
        url: url,
        data: data,
        success: function (dataRet) {
            if (typeof callbackSuccess == 'function') {
                callbackSuccess(dataRet);
            }
            if (targetId != undefined) {
                jQuery(targetId).html(dataRet);
            }
        }
    });
}