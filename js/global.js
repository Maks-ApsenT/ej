function createNoty(message, type) {
    var html = '<div class="alert alert-popup alert-' + type + ' alert-dismissible page-alert text-center">';
    html += '<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>';
    html += '<span class="message"><span class="glyphicon glyphicon-alert"></span> &nbsp;&nbsp;&nbsp;'+ message +'</span>';
    html += '</div>';
    $(html).prependTo('#noty-holder').slideDown();
    setTimeout(function(){
        $('#noty-holder div.alert').slideUp()
    }, 2000);
    return true;
}

function createToasts(message, type) {
    var html = '<div class="toast" id="toast" data-delay="200000" role="alert" data-animation="true" aria-live="polite" aria-atomic="true">';
    html += '<div class="toast-header">';
    html += '<strong class="mr-auto">Сервер</strong>';
    html += '<small>Только что</small>';
    html += '<button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">';
    html += '<span aria-hidden="true">&times;</span>';
    html += '</button>';
    html += '</div><div class="toast-body">'+ message +'</div></div></div>';
    $(html).prependTo('#toasts').slideDown();
    $('#toast').toast('show');
    return true;
}