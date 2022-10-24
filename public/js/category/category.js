$('#frmCategory').on('keyup keypress', function(e) {
  var keyCode = e.keyCode || e.which;
  if (keyCode === 13) { 
    e.preventDefault();
    return false;
  }
});
var Categories = {
    categories: Config.categories,
    focusCategory: null,
    btnSave: $("#saveCategory"),
    mdlCategory: $("#modalCategory"),
    mdlTitle: $("#modalCategory .modal-title"),
    mdlIptName: $("#modalCategory input[name='nombre']"),
    mdlHideId: $("#modalCategory input[name='id']"),
    InptIcono: $('#modalCategory input[name="icon"]'),
    mdlHideParentId: $("#modalCategory input[name='parent_id']"),
    portlets: $("#m_sortable_portlets .col-lg-12"),
    portlet_tools: $(".m-portlet__head-tools"),
    collapseBtn: `<li class="m-portlet__nav-item">
                    <a href="#" data-portlet-tool="toggle" class="void m-portlet__nav-link m-portlet__nav-link--icon">
                    <i class="la la-angle-down" />
                    </a>
                </li>`,
    saveCategory: function(id = 0, name, icon = null, parent_id = null) {
        var _s = this;
        $.ajax({
            url: Config.new_category,
            type: "GET",
            async: false,
            data: {
                id: id,
                name: name,
                icon: parent_id == "" ? icon : null,
                parent_id: parent_id
            },
            success: function(data) {
                _s.categories = data.categories; //se actualizan categorias
                switch (data.type) {
                    case "newCategory":
                        _s.portlets.append(data.html);
                        setTimeout(function() {
                            toastr.success(
                                "Nueva categoría. Se añadio correctamente <b>" +
                                _s.categories[data.id].name +
                                ".</b>"
                            );
                        }, 100);
                        $("html, body").animate({
                                scrollTop: $(".m-portlet-category")
                                    .last()
                                    .offset().top
                            },
                            2000
                        );
                        break;
                    case "newSubcategory":
                        var parent = _s.categories[data.id].parent_id;
                        _s.portlets.find("[data-id=" + parent + "] .children").append(data.html);
                        if (_s.portlets.find("[data-id=" + parent + "] .children .m-portlet-category").length == 1) { //si el que se acaba de agregar es el unico
                            $(_s.collapseBtn).appendTo($(_s.portlets.find("[data-id=" + parent + "] .category .m-portlet__head-tools .m-portlet__nav")));
                            _s.portlets.find("[data-id=" + parent + "]").mPortlet(); //activando collapse de portlet
                        }
                        break;
                    case "editCategory":
                        _s.portlets
                            .find("[data-id=" + data.id + "]")
                            .children("div.category")
                            .find(".m-portlet__head-text")
                            .html(_s.categories[data.id].name);
                        _s.portlets
                            .find("[data-id=" + data.id + "]")
                            .children("div.category")
                            .find(".m-portlet__head-icon i")
                            .attr("class", _s.categories[data.id].icon);
                        break;
                    case "editSubcategory":
                        _s.portlets
                            .find("[data-id=" + data.id + "]")
                            .children("div.subcategory")
                            .find(".m-portlet__head-text")
                            .html(_s.categories[data.id].name);
                        break;
                    default:
                }
                _s.mdlCategory.modal("hide");
                _s.clearModal();
            },
            error: function(error) {}
        });
    },
    deleteCategory: function() {
        var _s = Categories
        $.ajax({
            url: Config.delete_category,
            type: "GET",
            async: false,
            data: {
                id: _s.focusCategory
            },
            success: function(data) {
                _s.categories = data.categories; //se actualizan categorias
                var itemDeleted = _s.portlets.find("[data-id=" + data.id + "]").parent();
                if ($(itemDeleted).find(".m-portlet-category").length - 1 == 0) { //si no tiene subcategorias, 
                    $(itemDeleted).parent().find(".m-portlet__head-tools [data-portlet-tool='toggle']").remove(); //se quita boton collapse
                }
                _s.portlets.find("[data-id=" + data.id + "]").remove();
                modal.kill();
            },
            error: function(error) {}
        });
    },
    addCategory: function(element) {
        var _s = this;
        var title = "Nueva Categoría";
        _s.clearModal();
        $(_s.mdlTitle).html(title);
        $(_s.mdlCategory).modal("show");
    },
    editCategory: function(element) {
        var _s = this;
        var id = $(element).closest(".m-portlet-category").data("id");
        var title = "Editar categoría";
        _s.clearModal();
        $(_s.mdlTitle).html(title);
        $(_s.mdlHideId).val(id);
        $(_s.mdlIptName).val(_s.categories[id].name);
        $('button[role="iconpicker"]').iconpicker("setIcon", _s.categories[id].icon);
        $(_s.mdlCategory).modal("show");
    },
    addSubcategory: function(element) {
        var _s = this;
        var id = $(element)
            .closest(".m-portlet-category")
            .data("id");
        var title = "Nueva subcategoría";
        _s.clearModal();
        _s.hideIconPicker();
        $(_s.mdlTitle).html(title);
        $(_s.mdlHideParentId).val(id);
        $(_s.mdlCategory).modal("show");
    },
    editSubcategory: function(element) {
        var _s = this;
        var parent_id = $(element).closest(".m-portlet-category").data("parent");
        var id = $(element).closest(".m-portlet-category").data("id");
        var title = "Editar subcategoría";
        _s.clearModal();
        _s.hideIconPicker();
        $(_s.mdlTitle).html(title);
        $(_s.mdlHideId).val(id);
        $(_s.mdlHideParentId).val(parent_id);
        $(_s.mdlIptName).val(_s.categories[id].name);
        $(_s.mdlCategory).modal("show");
    },
    clearModalDelete: function() {
        var _s = this;
        $(_s.InptDeleteId).val("");
    },
    clearModal: function() {
        var _s = this;
        $(_s.mdlTitle).val("");
        $(_s.mdlIptName).val("");
        $(_s.mdlHideId).val("");
        $(_s.mdlHideParentId).val("");
        $(_s.mdlCategory).find(".form-group .col-lg-12").addClass("col-lg-8").removeClass("col-lg-12");
        $(_s.mdlCategory).find(".iconpicker").css("display", "block");
        $('button[role="iconpicker"]').iconpicker("setIcon", "la la-bars");
    },
    hideIconPicker: function() {
        var _s = this;
        $(_s.mdlCategory).find(".form-group .col-lg-8").addClass("col-lg-12").removeClass("col-lg-8");
        $(_s.mdlCategory).find(".iconpicker").css("display", "none");
    },
    init: function() {
        var _s = this;
        _s.clearModal();

        $("#m_sortable_portlets").sortable({
            connectWith: ".m-portlet__head.category",
            items: ".m-portlet",
            opacity: 0.8,
            handle: ".m-portlet__head.category",
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
            update: function(e, t) {
                t.item.prev().hasClass("m-portlet--sortable-empty") &&
                    t.item.prev().before(t.item);
            },
            stop: function(event, ui) {
                var orderby = $(".parent-category[data-id]")
                    .map(function(i, id) {
                        return {
                            id: $(id).data("id"),
                            order: i + 1
                        };
                    })
                    .get();
                $.ajax({
                    url: Config.orderby_category,
                    type: "POST",
                    data: {
                        _token: Config._token,
                        categories: orderby
                    },
                    success: function(data) {},
                    error: function(error) {}
                });
            }
        });
        _s.btnSave.click(function() {
            $("#frmCategory").validate();
            if ($("#frmCategory").valid()) {
                var cat = [];
                var id = $("#modalCategory input[name='id']").val();
                var name = $(_s.mdlIptName).val();
                var icon = $(_s.InptIcono).val();
                var parent_id = $(_s.mdlHideParentId).val();
                _s.saveCategory(id, name, icon, parent_id);
            }
        });
        _s.portlet_tools.on("click", ".addCategory", function() {
            _s.addCategory(this);
        });
        _s.portlets.on("click", ".editCategory", function() {
            _s.editCategory(this);
        });
        _s.portlets.on("click", ".addSubcategory", function() {
            _s.addSubcategory(this);
        });
        _s.portlets.on("click", ".editSubcategory", function() {
            _s.editSubcategory(this);
        });
        _s.portlets.on("click", ".deleteCategory", function() {
            _s.focusCategory = $(this).closest(".m-portlet-category").data("id");
            var title = 'categoría';
            if($(this).hasClass('childCategory')){
                title = 'subcategoría';
            }
            modal.launch({
                'title': "Eliminar "+title,
                'body': "Se eliminará " + title +" <b>" + $(this).closest(".m-portlet-category").data("name") +"</b>.<br> ¿Desea continuar?",
            }, _s.deleteCategory);
        });
    }
};