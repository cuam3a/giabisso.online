function imgError(image) {
    image.onerror = "";
    image.src = "/website/img/ico-hec-25.png";
    return true;
}

function number_format(number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
$(function () {

    $(".dropdown-item").click(function (event) {
        event.stopPropagation();//en header se detiene clic para que categoria padre redirija a pagina
    });

    jQuery('img.inline-svg').each(function () {
        var $img = jQuery(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');

        jQuery.get(imgURL, function (data) {
            // Get the SVG tag, ignore the rest
            var $svg = jQuery(data).find('svg');

            // Add replaced image's ID to the new SVG
            if (typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Check if the viewport is set, else we gonna set it if we can.
            if (!$svg.attr('viewBox') && $svg.attr('height') && $svg.attr('width')) {
                $svg.attr('viewBox', '0 0 ' + $svg.attr('height') + ' ' + $svg.attr('width'))
            }

            // Replace image with new SVG
            $img.replaceWith($svg);

        }, 'xml');

    });
  $('[data-toggle="tooltip"]').tooltip();
    $(".void").click(function (e) {
        e.preventDefault();
    });

    $(document).on("click", ".modalLogin", function () {
        var msg = $(this).data('msg');
        var title = $(this).data('title');
        var rte='';
        if ($(this).data("redirect")) {
            rte = "?redirect="+$(this).data('redirect');
        }
        swal({
            title: '<h4 style="font-weight:bold">'+title+'</h4>',
            imageUrl: '/website/img/is.svg',
            imageAlt: title,
            html: msg,
            imageHeight: 60,
            confirmButtonColor: "#5aa407",
            confirmButtonText:'INGRESA o REGISTRATE',
            showCancelButton: false,
            showCloseButton: true,
            showConfirmButton: true,
            buttonsStyling:true,
        }).then((result) => {
            if (result.value) {
                window.location = login_url+rte;
            }
        })
    });
});