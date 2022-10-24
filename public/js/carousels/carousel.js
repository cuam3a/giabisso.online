var Carousels = {
    carousels: Config.carousels,
    focusDelete: null,
    btnSave: $("#saveCarousel"),
    mdlCarousel: $("#modalCarousel"),
    mdlTitle: $("#modalCarousel .modal-title"),
    mdlIptName: $("#modalCarousel input[name='nombre']"),
    mdlHideId: $("#modalCarousel input[name='id']"),
    InptIcono: $('#modalCarousel input[name="icon"]'),
    mdlHideParentId: $("#modalCarousel input[name='parent_id']"),
    portlets: $("#m_sortable_portlets .col-lg-12"),
    portlet_tools: $(".m-portlet__head-tools"),
    collapseBtn: `<li class="m-portlet__nav-item">
                    <a href="#" data-portlet-tool="toggle" class="void m-portlet__nav-link m-portlet__nav-link--icon">
                    <i class="la la-angle-down" />
                    </a>
                </li>`,
    saveCarousel: function (id = 0, name) {
        var _s = this;
        $.ajax({
            url: Config.new_carousel,
            type: "GET",
            async: false,
            data: {
                id: id,
                name: name
            },
            success: function (data) {
                _s.carousels = data.carousels; //se actualizan carruseles
                switch (data.type) {
                    case "newCarousel":
                        _s.portlets.append(data.html);
                        setTimeout(function () {
                            toastr.success(
                                "Nuevo destacado. Se añadio correctamente <b>" +
                                _s.carousels[data.id].name +
                                ".</b>"
                            );
                        }, 100);
                        $("html, body").animate({
                            scrollTop: $(".m-portlet-carousel")
                                .last()
                                .offset().top
                        },
                            2000
                        );
                        break;
                    case "editCarousel":
                        _s.portlets
                            .find("[data-id=" + data.id + "]")
                            .children("div.carousel")
                            .find(".m-portlet__head-text")
                            .html(_s.carousels[data.id].name);
                        _s.portlets
                            .find("[data-id=" + data.id + "]")
                            .children("div.carousel")
                            .find(".m-portlet__head-icon i")
                            .attr("class", _s.carousels[data.id].icon);
                        break;
                    default:
                }
                _s.mdlCarousel.modal("hide");
                _s.clearModal();
            },
            error: function (error) { }
        });
    },
    deleteCarousel: function () {
        var _s = Carousels;
        $.ajax({
            url: Config.delete_carousel,
            type: "GET",
            async: false,
            data: {
                id: _s.focusDelete
            },
            success: function (data) {
                _s.focusDelete = null;
                _s.carousels = data.carousels; //se actualizan categorias
                var itemDeleted = _s.portlets.find("[data-id=" + data.id + "]").parent();
                if ($(itemDeleted).find(".m-portlet-carousel").length - 1 == 0) { //si no tiene subcategorias, 
                    $(itemDeleted).parent().find(".m-portlet__head-tools [data-portlet-tool='toggle']").remove(); //se quita boton collapse
                }
                _s.portlets.find("[data-id=" + data.id + "]").remove();
                modal.kill();
            },
            error: function (error){}
        });
    },
    addCarousel: function (element) {
        var _s = this;
        var title = "Nueva lista";
        _s.clearModal();
        $(_s.mdlTitle).html(title);
        $(_s.mdlCarousel).modal("show");
    },
    editCarousel: function (element) {
        var _s = this;
        var id = $(element).closest(".m-portlet-carousel").data("id");
        var title = "Editar lista";
        _s.clearModal();
        $(_s.mdlTitle).html(title);
        $(_s.mdlHideId).val(id);
        $(_s.mdlIptName).val(_s.carousels[id].name);
        $(_s.mdlCarousel).modal("show");
    },
    clearModal: function () {
        var _s = this;
        $(_s.mdlTitle).val("");
        $(_s.mdlIptName).val("");
        $(_s.mdlHideId).val("");
        $(_s.mdlHideParentId).val("");
    },
    init: function () {
        var _s = this;
        _s.clearModal();

        $("#m_sortable_portlets").sortable({
            connectWith: ".m-portlet__head.carousel",
            items: ".m-portlet",
            opacity: 0.8,
            handle: ".m-portlet__head.carousel",
            coneHelperSize: !0,
            placeholder: "m-portlet--sortable-placeholder",
            forcePlaceholderSize: !0,
            tolerance: "pointer",
            helper: "clone",
            tolerance: "pointer",
            forcePlaceholderSize: !0,
            helper: "clone",
            cancel: ".m-portlet--sortable-empty",
            revert: 250,
            update: function (e, t) {
                t.item.prev().hasClass("m-portlet--sortable-empty") &&
                    t.item.prev().before(t.item);
            },
            stop: function (event, ui) {
                var orderby = $(".parent-carousel[data-id]")
                    .map(function (i, id) {
                        return {
                            id: $(id).data("id"),
                            order: i + 1
                        };
                    })
                    .get();
                $.ajax({
                    url: Config.orderby_carousel,
                    type: "POST",
                    data: {
                        _token: Config._token,
                        carousels: orderby
                    },
                    success: function (data) { },
                    error: function (error) { }
                });
            }
        });
        _s.btnSave.click(function () {
            $("#frmCarousel").validate();
            if ($("#frmCarousel").valid()) {
                var cat = [];
                var id = $("#modalCarousel input[name='id']").val();
                var name = $(_s.mdlIptName).val();
                var icon = $(_s.InptIcono).val();
                var parent_id = $(_s.mdlHideParentId).val();
                _s.saveCarousel(id, name, icon, parent_id);
            }
        });
        _s.portlet_tools.on("click", ".addCarousel", function () {
            _s.addCarousel(this);
        });
        _s.portlets.on("click", ".editCarousel", function () {
            _s.editCarousel(this);
        });
        _s.portlets.on("click", ".deleteCarousel", function () {
            _s.focusDelete = $(this).closest(".m-portlet-carousel").data("id");
            modal.launch({
                'title': "Eliminar lista",
                'body': "Se eliminará <b>" + $(this).closest(".m-portlet-carousel").data("name")+"</b>.<br> ¿Desea continuar?",
            }, _s.deleteCarousel);
        });
    }
};