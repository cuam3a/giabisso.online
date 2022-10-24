//== Class definition
var Carts = function() {
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
                    field: "fullname",
                    title: "Nombre",
                    width: 150
                },
                {
                    field: "email",
                    title: "Correo electrónico",
                    width: 220
                },
                {
                    field: "total",
                    textAlign: "center",
                    title: "Total",
                    width: 100
                },
                {
                    field: "date_updated",
                    textAlign: "center",
                    title: "Fecha",
                    width: 100
                },
                {
                    field: "products",
                    textAlign: "left",
                    title: "Productos",
                    sortable: false,
                    overflow: "visible",
                    width: 280,
                    template: function(row) {
                        var dropup = row.getDatatable().getPageSize() - row.getIndex() <= 4 ? "dropup" : "";
                        var products = '<ul style="width:100%">';
                        for (var key in row.products) {
                            products += '<li>' + row.products[key] + '</li>';
                        }
                        products += '</ul>';
                        return products;
                    }
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
    };

    return {
        // public functions
        init: function() {
            demo();
        },
    };
}();

jQuery(document).ready(function() {
    Carts.init();
});