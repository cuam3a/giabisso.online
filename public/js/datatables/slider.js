//== Class definition
var Slider = function() {
    //== Private functions
    // basic demo
    var datatable;
    var demo = function() {
        
        datatable = $(".m_datatable").mDatatable({
          data: {
            type: "local",
            source: data_tmp,
            pageSize: 10,
            saveState: {
              cookie: true,
              webstorage: true
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
          columns: [
            {
              field: "path",
              textAlign: "center",
              title: "Imagen",
              width: 80,
              template: function(row) {
                return (
                  '<a data-toggle="modal" class="img-thumb"  data-src="'+row.path+'" href="#basic"><img src="'+row.path+'" style="width:40px"></a> '
                );
              },
            },
            {
              field: "description",
              title: "Descripción",
              width: 150
            },
            {
              field: "status",
              textAlign: "center",
              title: "Estatus",
              width: 120,
              template: function(row){
                    badge_status = {};
                    row.status == 1 ? badge_status = {class: 'success', text: 'Activo'} : badge_status = {class:'danger', text: 'Inactivo' }
                    return(    
                        '<span class="m-badge m-badge--'+badge_status.class+' m-badge--wide">'+badge_status.text+'</span>'
                    )
              }
            },
            {
              field: "status_filtered",
              textAlign: "center",
              title: "",
              width: 0
            },
            {
              field: "created_at",
              title: "Fecha",
              textAlign: "center",
              width: 80
            },
            {
              field: "order",
              title: "Orden",
              textAlign: "center",
              width: 80
            },
            {
              field: "_status",
              title: "",
              textAlign: "center",
              width:0,
              template: function(row){
                  return (row.status)
              }
            },
            {
              field: "Acciones",
              textAlign: "center",
              title: "Acciones",
              sortable: false,
              overflow: "visible",
              width: 200,
              template: function(row) {
                data = {};
                data.url = Config["estatus"].replace("slider_image_id", row.id)

                if(row.status == 1){
                    data.title = 'Deshabilitar imagen'
                    data.action = 'deshabilitará'
                    data.icon = 'la la-ban'
                }
                else{
                    data.title = 'Habilitar imagen'
                    data.action = 'habilitará'
                    data.icon = 'la la-check-circle'
                }

                return (
                  '\
						  <a data-toggle="modal" data-url="'+ data.url +'" data-action = "'+data.action+'" data-description="'+ row.description +'" data-id="'+ row.id +'" data-title="' + data.title + '" data-target="#modalRUsure" href="#" title = "'+ data.title +'"' +
                  '" class="toggle-product m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">\
							<i class="'+ data.icon + '"></i></a>\
                    <a data-id="'+row.id+'" title="Bajar orden" class="order-down toggle-product m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"><i class="la la-arrow-down"></i></a>\
                    <a data-id="'+row.id+'" title="Subir orden" class="order-up toggle-product m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"><i class="la la-arrow-up"></i></a>\
                    <a href="'+Config.edit_slider.replace('slider_replace',row.id)+'" title="Editar slider" class="del-slider m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"><i class="la la-edit"></i></a>\
                    <a data-id="'+ row.id +'" data-target="#modalRUsure" data-toggle="modal" href="#" data-action="eliminará" data-description="'+ row.description +'" data-title="Eliminar slider" title="Eliminar slider" class="del-slider m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"><i class="la la-times"></i></a>\
                    '
                );
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
                  info:
                    "Mostrando {{start}} - {{end}} de {{total}} registros"
                }
              }
            }
          }
        });

        $('#status').on('change', function() {
         
        });

        $("#status").selectpicker();

    };

    var handleStatusFilter = function(){
      $("#status").on("change",function(){
          datatable.search($(this).val(), 'status_filtered')
      });
    }

    return {
        // public functions
        init: function() {
            demo();
            handleStatusFilter();

        }
    };
}();

jQuery(document).ready(function() {
    Slider.init();
    
    var setModalInfo = function(info){
        $("#modalRUsure .modal-body").html(`Se ${info.action} la imagen <b>${info.description}</b>.<br> ¿Desea continuar?`);
        $("#modalRUsure form").attr('action', info.url);
    }

   

    $("body").on("click",".toggle-product",function(){
        var info = {}
        info.msg = $(this).data('title');
        info.action = $(this).data('action');
        info.description = $(this).data('description');

        setModalInfo(info);
        console.log(info)
    });


    $("body").on("click",".img-thumb",function(){
      console.log($(this).data('src'));
      $(".modal-body img").attr('src', '');
      $(".modal-body img").attr('src', $(this).data('src'));
    });

    $("body").on("click", ".order-down",function(){
      var id = $(this).data('id');
      $('<form action="'+Config.change_order_slider+'" method="POST">'+Config.csrf_field+'<input type="hidden" name="order" value="down"><input type="hidden" name="id" value="'+id+'"></form>').appendTo('body').submit();

    })

     $("body").on("click", ".order-up",function(){
      var id = $(this).data('id');
      $('<form action="'+Config.change_order_slider+'" method="POST">'+Config.csrf_field+'<input type="hidden" name="order" value="up"><input type="hidden" name="id" value="'+id+'"></form>').appendTo('body').submit();

    })

    $("body").on("click", ".del-slider",function(){
      var id = $(this).data('id');
      var info = {}
      info.url = Config.delete_slider.replace('slider_replace', $(this).data('id'));
      info.msg = $(this).data('title');
      info.action = $(this).data('action');
      info.description = $(this).data('description');

      setModalInfo(info);
      
      // $('<form action="'+Config.change_status_url+'" method="POST">'+Config.csrf_field+'<input type="hidden" name="order" value="up"><input type="hidden" name="id" value="'+id+'"></form>').appendTo('body').submit();

    })


    
});