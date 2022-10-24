declare var $:any;

class Header{
    
    private selectors = {
        close: ".close-search",
        number: ".number-input",
        numberRest: '.product-rest',
        numberAdd: '.product-add',
        searchLink: ".search-link",
        searchForm: ".header-search",
        searchMobile: ".search-mobile",
        searchPC: ".search-pc",
        btnMobile: ".btnMobile",
        frmSearch: ".frmMenuSearch"
    };

    constructor(){
        this.bindAll();
    }

    showSearch(){
        $(this.selectors.searchForm).addClass("showing");
    }

    hideSearch(){
        $(this.selectors.searchForm).removeClass("showing");   
    }

    bindAll(){
        let header: Header = this;
        $(this.selectors.searchLink).unbind("click.show");
        $(this.selectors.searchLink).bind("click.show", function(){
            header.showSearch();
        });

        $(this.selectors.searchForm).find(header.selectors.close).unbind("click.hide");
        $(this.selectors.searchForm).find(header.selectors.close).bind("click.hide", function(){
            header.hideSearch();
        });

        $(this.selectors.number).find(header.selectors.numberAdd).unbind("click.add");
        $(this.selectors.number).find(header.selectors.numberAdd).bind("click.add", function(){
            let $this = $(this).closest(".number-input");
            let value: number = parseInt($this.find("input").val());
            let max: number = parseInt($this.find("input").attr('max'));
            if(value < max){
                ++value;
                $this.find("input").val(value);
            }
        });

        $(this.selectors.number).find(header.selectors.numberRest).unbind("click.rest");
        $(this.selectors.number).find(header.selectors.numberRest).bind("click.rest", function(){
            let $this = $(this).closest(".number-input");
            let value: number = parseInt($this.find("input").val());
            let min: number = parseInt($this.find("input").attr('min'));
            if(value > min){
                --value;
                $this.find("input").val(value);
            }
        });

        $(header.selectors.searchMobile).unbind('change');
        $(header.selectors.searchMobile).bind('change', function () {
            $(header.selectors.searchPC).val($(this).val());
        });

        $(header.selectors.searchPC).unbind('change');
        $(header.selectors.searchPC).bind('change', function () {
            $(header.selectors.searchMobile).val($(this).val());
        });

        $(header.selectors.btnMobile).unbind('click');
        $(header.selectors.btnMobile).bind('click', function () {
            $(header.selectors.frmSearch).submit();
        });
    }
}
new Header();