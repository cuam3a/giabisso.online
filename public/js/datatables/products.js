//== Class definition
var Products = function() {
    //== Private functions
    // basic demo
    var demo = function() {
        let priceList = null;
        var datatable = $('.m_datatable').mDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        method: 'GET',
                        url: Config['datatable'],
                        map: function(raw) {
                            // sample data mapping
                            $('#actives').html(raw.actives);
                            $('#inactives').html(raw.inactives);
                            var dataSet = raw;
                            if (typeof raw.data !== 'undefined') {
                                dataSet = raw.data;
                                priceList = raw.priceLists;
                            }
                            return dataSet;
                        },
                    },
                },
                pageSize: 10,
                saveState: {
                    cookie: true,
                    webstorage: true,
                },
                serverPaging: true,
                serverFiltering: true,
                serverSorting: true,
            },

            // layout definition
            layout: {
                theme: 'default', // datatable theme
                class: '', // custom wrapper class
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
                        pageSizeSelect: [10, 20, 30, 50, 100],
                    },
                },
            },

            search: {
                input: $('#generalSearch'),
            },

            // columns definition
            columns: [{
                    field: 'image',
                    title: 'Producto',
                    width: 300,
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
                                      <span class="m-card-user__name">' + row.name + '</span>\
                                    </a>\
                                      <div class="m-card-user__email m-link">' + row.sku + '</div>\
                                    </div>\
                                </div>';
                    }
                },
                {
                    field: 'category',
                    textAlign: 'center',
                    title: 'Categoría',
                    width: 100,
                },
                {
                    field: 'stock',
                    textAlign: 'center',
                    title: 'Inventario',
                    width: 100,
                },
                {
                    field: 'brand',
                    textAlign: 'center',
                    title: 'Marca',
                    width: 100,
                },
                {
                    field: 'status',
                    textAlign: 'center',
                    title: 'Estatus',
                    width: 80,
                    template: function(row) {
                        var statusLbl = ['danger', 'success'];
                        return '<span class="m-badge m-badge--' + statusLbl[row.status] + ' m-badge--wide">' + Config['statuses'][row.status] + '</span>';
                    }
                },
                {
                    field: 'final_price',
                    title: 'Precio',
                    textAlign: 'center',
                    type: 'number',
                    width: 80,
                    // callback function support for column rendering
                    template: function(row) {
                        if (row.offer_price_s > 0) {
                            return '$' + number_format(row.offer_price_s, 2);
                        } else {
                            return '$' + number_format(row.regular_price_s, 2);
                        }
                    }
                },
                {
                    field: "ListaPrecios",
                    title: "Lista Precios",
                    textAlign: 'center',
                    sortable: false,
                    overflow: 'visible',
                    width: 200,
                    template: function(row) {
                        if(priceList != null){
                            let lists = priceList.filter( item => item.product_id == row.id);
                            let result = '<div>';
                            lists.forEach(item =>{
                                result += "<div class='form-inline'><div style='width:150px'><span style='font-size:12px'>" + item.name + "</span></div><div style='width:50px'><input class='text-right inputListPrice' data-id='"+item.id+"' style='font-size:12px; width:70px;' value='"+item.price+"' type='number'/></div></div></br>";
                            })
                            result += '</div>';
                            return result;
                        }
                        //return row.id ;
                    }
                },
                {
                    field: "Liquidacion",
                    title: "Liquidacion",
                    textAlign: 'center',
                    sortable: false,
                    overflow: 'visible',
                    width: 150,
                    template: function(row) {
                        let status = "";
                        if(row.liquidado == 1){ status = "checked" }
                        let result = '<div>';
                        result += "<div class='form-inline'><div style=''><input class='text-right mt-2 checkLiquidado' style='width:50px' data-id='"+row.id+"' type='checkbox' " + status +"/></div><div style='width:80px'><input class='text-right inputLiquidado' data-id='"+row.id+"' style='font-size:12px; width:80px;' value='"+row.liquidado_price+"' type='number'/></div></div>";
                        result += '</div>';
                        return result;
                    }
                },
                {
                    field: 'Acciones',
                    width: 147,
                    textAlign: 'center',
                    title: 'Acciones',
                    sortable: false,
                    overflow: 'visible',
                    template: function(row) {
                        var title = ['Habilitar', 'Desactivar'];
                        var action = ['Habilitará', 'Desactivará'];
                        var dropup = (row.getDatatable().getPageSize() - row.getIndex()) <= 4 ? 'dropup' : '';
                        var statusIcon = ['la-check-circle', 'la-ban'];
                        return '\
						<a href="#" data-id="' + row.id + '" data-toggle="modal" title="' + title[row.status] + ' producto" data-type="product" data-action="' + action[row.status] + '" data-name="' + row.name + '" data-url="' + Config['status'].replace("id", row.id) + '" data-target="#modalRUsure" class="changeStatus void m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">\
							<i class="la ' + statusIcon[row.status] + '"></i>\
                        </a>\
						<a href="' + Config['edit'].replace("id", row.id) + '" class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill" title="Editar producto">\
							<i class="la la-edit"></i>\
                        </a>\
                        <a href="#" data-id="' + row.id + '" data-name="' + row.name + '" class="void m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill duplicateItem" data-action="Duplicará" title="Duplicar producto">\
                            <i class="la la-clone"></i>\
                        </a>\
						<a href="#" data-id="' + row.id + '" data-name="' + row.name + '" class="void m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill deleteProduct">\
							<i class="la la-trash"></i>\
						</a>\
					';
                    },
                }
            ],
            translate: {
                records: {
                    processing: 'Por favor espere...',
                    noRecords: 'No se encontraron registros',
                },
                toolbar: {
                    pagination: {
                        items: {
                            default: {
                                first: 'Primero',
                                prev: 'Anterior',
                                next: 'Siguiente',
                                last: 'Ultimo',
                                more: 'Más páginas',
                                input: 'Número de página',
                                select: 'Elija tamaño de página'
                            },
                            info: 'Mostrando {{start}} - {{end}} de {{total}} registros'
                        }
                    }
                }
            }
        });

        var query = datatable.getDataSourceQuery();

        $('#category').on('change', function() {
            var slc_subcategory = $("#subcategory");
            var slc_brands = $("#brand");
            /** get subcategories */
            $.ajax({
                method: "POST",
                url: Config.subcategory,
                data: {
                    '_token': Config._token,
                    'category': $(this).val()
                }
            }).done(function(subcategories) {
                slc_subcategory.find('option').not(':first').remove();
                slc_subcategory.selectpicker('refresh');
                if (subcategories.length == 0) {
                    slc_subcategory.attr('disabled', 'disabled').trigger('change');
                    slc_subcategory.selectpicker('refresh');
                    return false;
                }
                slc_subcategory.removeAttr('disabled');
                slc_subcategory.trigger('change');
                // slc_subcategory.append(new Option('Elegir opción','', false, false));
                $.each(subcategories, function(key, text) {
                    newOption = new Option(text, key, false, false);
                    slc_subcategory.append(newOption);
                });
                slc_subcategory.selectpicker('refresh');
            });

            /** Brands */
            $.ajax({
                method: "POST",
                url: Config.brands,
                data: {
                    '_token': Config._token,
                    'category': $(this).val()
                }
            }).done(function(brands) {
                slc_brands.find('option').not(':first').remove();
                slc_brands.selectpicker('refresh');
                if (slc_brands.length == 0) {
                    slc_brands.attr('disabled', 'disabled').trigger('change');
                    slc_brands.selectpicker('refresh');
                    return false;
                }
                slc_brands.removeAttr('disabled');
                slc_brands.trigger('change');
                // slc_subcategory.append(new Option('Elegir opción','', false, false));
                $.each(brands, function(index, val) {
                    newOption = new Option(val.text, val.id, false, false);
                    slc_brands.append(newOption);
                });
                slc_brands.selectpicker('refresh');
            });


            // shortcode to datatable.getDataSourceParam('query');
            var query = datatable.getDataSourceQuery();
            query.Category = $(this).val().toLowerCase();
            // shortcode to datatable.setDataSourceParam('query', query);
            datatable.setDataSourceQuery(query);
            datatable.load();
        }).val(typeof query.Category !== 'undefined' ? query.Category : '');

        $('#subcategory').on('change', function() {
            // shortcode to datatable.getDataSourceParam('query');
            var query = datatable.getDataSourceQuery();
            query.Subcategory = $(this).val().toLowerCase();
            // shortcode to datatable.setDataSourceParam('query', query);
            datatable.setDataSourceQuery(query);
            datatable.load();

        });

        $('#status').on('change', function() {
            // shortcode to datatable.getDataSourceParam('query');
            var query = datatable.getDataSourceQuery();
            query.Status = $(this).val().toLowerCase();
            // shortcode to datatable.setDataSourceParam('query', query);
            datatable.setDataSourceQuery(query);
            datatable.load();
        }).val(typeof query.Status !== 'undefined' ? query.Status : '');

        $("#brand").on('change', function() {
            var query = datatable.getDataSourceQuery();
            query.Brand = $(this).val().toLowerCase();
            // shortcode to datatable.setDataSourceParam('query', query);
            datatable.setDataSourceQuery(query);
            datatable.load();
        })

        $("#btn-toggle-product-search").on("click", function() {
            var query = datatable.getDataSourceQuery();

            if (!Config.searchByProductNumber) {
                $(this).addClass('btn-success').removeClass('btn-default')
                $("#generalSearch").attr('placeholder', 'Búsqueda por número de producto');
            } else {
                $(this).addClass('btn-default').removeClass('btn-success')
                $("#generalSearch").attr('placeholder', 'Búsqueda por SKU, nombre, descripción...')
            }

            Config.searchByProductNumber = !Config.searchByProductNumber;

            query.searchByProductNumber = Config.searchByProductNumber
            datatable.setDataSourceQuery(query);
            datatable.load();
        })


        $('#category, #status, #subcategory, #brand').selectpicker();
        

    };

    return {
        // public functions
        init: function() {
            demo();
        },
    };
}();