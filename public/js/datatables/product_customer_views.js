//== Class definition
var ProductCustomerViews = function() {
    //== Private functions
    // basic demo
    var demo = function() {
        var datatable = $(".m_datatable").mDatatable({
            // datasource definition
            data: {
                type: "remote",
                source: {
                    read: {
                        method: "GET",
                        url: Config["datatable"],
                        map: function(raw) {
                            // sample data mapping
                            var dataSet = raw;
                            if (typeof raw.data !== "undefined") {
                                dataSet = raw.data;
                            }
                            return dataSet;
                        }
                    }
                },
                aaSorting: [],
                pageSize: 10,
                saveState: {
                    cookie: false,
                    webstorage: false
                },
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true
            },

            // layout definition
            layout: {
                theme: "default", // datatable theme
                class: "", // custom wrapper class
                scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
                footer: false // display/hide footer
            },

            // column sorting
            sortable: true,

            pagination: true,

            toolbar: {
                // toolbar items
                items: {
                    // pagination
                    pagination: {
                        // page size select
                        pageSizeSelect: [10, 20, 30, 50, 100]
                    }
                }
            },

            search: {
                input: $("#generalSearch")
            },

            // columns definition
            columns: [{
                    field: "views",
                    textAlign: "center",
                    title: "Vistas",
                    width: 120
                }, {
                    field: 'image',
                    title: 'Producto',
                    width: 250,
                    template: function(row) {
                        var image = row.image; //obteniendo imagen
                        var product_number = '';

                        if (row.image_name == null) image = Config['placeholder']; //si no tengo imagen se pone placeholder

                        // Si product_number existe se agrega el div 
                        if (row.product_number != null) product_number = '<div class="m-card-user__email m-link">' + row.product_number + '</div>'

                        // var src = '/uploads/' + image; //asumiendo que es local
                        var src = image; //fullpath from table record
                        if (row.image_type == Config['imageTypeText']['url']) src = image; // se pone url
                        return '<div class="m-card-user m-card-user--sm">\
                                  <div class="m-card-user__pic">\
                                    <a href="' + Config['edit'].replace("id", row.id) + '">\
                                      <img src="' + src + '" class="m--img-rounded m--marginless" alt="photo">\
                                    </a>\
                                  </div>\
                                  <div class="m-card-user__details">\
                                    ' + product_number + '\
                                    <a href="' + Config['edit'].replace("id", row.id) + '">\
                                      <span class="m-card-user__name">' + row.product + '</span>\
                                    </a>\
                                      <div class="m-card-user__email m-link">' + row.sku + '</div>\
                                    </div>\
                                </div>';
                    }
                },
                {
                    field: "category",
                    textAlign: "center",
                    title: "Categoría",
                    width: 120
                },

                {
                    field: "fullname",
                    title: "Nombre",
                    width: 150
                },
                {
                    field: "email",
                    title: "Correo electrónico",
                    width: 200
                }
            ],
            translate: {
                records: {
                    processing: "Por favor espere...",
                    noRecords: "No se encontraron registros"
                },
                toolbar: {
                    pagination: {
                        items: {
                            default: {
                                first: "Primero",
                                prev: "Anterior",
                                next: "Siguiente",
                                last: "Ultimo",
                                more: "Más páginas",
                                input: "Número de página",
                                select: "Elija tamaño de página"
                            },
                            info: "Mostrando {{start}} - {{end}} de {{total}} registros"
                        }
                    }
                }
            }
        });

        var query = datatable.getDataSourceQuery();

        $('#category').on('change', function() {
            // shortcode to datatable.getDataSourceParam('query');
            var query = datatable.getDataSourceQuery();
            query.Category = $(this).val().toLowerCase();
            // shortcode to datatable.setDataSourceParam('query', query);
            datatable.setDataSourceQuery(query);
            datatable.load();
        }).val(typeof query.Category !== 'undefined' ? query.Category : '');

        $('#daterange').on('change', function() {
            // shortcode to datatable.getDataSourceParam('query');
            var query = datatable.getDataSourceQuery();
            query.daterange = $(this).val();
            // shortcode to datatable.setDataSourceParam('query', query);
            datatable.setDataSourceQuery(query);
            datatable.load();
        }).val(typeof query.daterange !== 'undefined' ? query.daterange : '');

        $("#btn-toggle-product-search").on("click", function() {
            var query = datatable.getDataSourceQuery();

            if (!Config.searchByProductNumber) {
                $(this).addClass('btn-success').removeClass('btn-default')
                $("#generalSearch").attr('placeholder', 'Búsqueda por número de producto');
                $('#btn-toggle-product-search').data('type', true);
            } else {
                $(this).addClass('btn-default').removeClass('btn-success')
                $("#generalSearch").attr('placeholder', 'Búsqueda por SKU, nombre, descripción...');
                $('#btn-toggle-product-search').data('type', false);
            }

            Config.searchByProductNumber = !Config.searchByProductNumber;

            query.searchByProductNumber = Config.searchByProductNumber
            datatable.setDataSourceQuery(query);
            datatable.load();
        })

        $('#category').selectpicker();

    };

    return {
        // public functions
        init: function() {
            demo();
        },
    };
}();

jQuery(document).ready(function() {
    ProductCustomerViews.init();
});