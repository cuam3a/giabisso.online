var modal = {
    mdl: $('#modalRUsure'),
    title: $('#modalRUsure .modal-title'),
    body: $('#modalRUsure .modal-body'),
    btnYes: $('#modalRUsure .btn-yes'),
    btnNo: true,
    launch: function(options, callbackYes, callbackNo) {
        var _s = this;
        $(_s.title).html(options.title);
        $(_s.body).html(options.body);
        $(_s.btnYes).click(function(e) {
            callbackYes();
        });
        _s.mdl.modal('show');
    },
    kill: function() {
        var _s = this;
        _s.mdl.modal('hide');
    }
}