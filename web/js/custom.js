/**
 * Created by alpo on 28.12.16.
 */
(function($){

    /**
     *
     * @type {{userName: FormData.userName, getDealerName: FormData.getDealerName, setDealerName: FormData.setDealerName}}
     */
    var FormData =
    {
        /**
         *
         * @returns {*}
         */
        userName : function () {
            var $identityField  = $('#w2').find('form').find('.logout');
            if ($identityField.html().indexOf('(') > 0){
                var startPos = $identityField.html().indexOf('(');
                var stopPos = $identityField.html().indexOf(')');
                return $identityField.html().substr (startPos + 1, (stopPos - startPos -1));
            }else{
                return false;
            }
        },

        /**
         *
         * @returns {*}
         * @param $field
         */
        getDealerName : function ($field) {

            if (!!$field){
                return $field.val();
            }else{
                return false;
            }
        },

        /**
         *
         * @param $field
         * @param name
         */
        setDealerName : function ($field, name) {
            if (!!$field) {
                $field.val(name);
            }

        },

        /**
         *
         * @param mat_id
         * @returns {Array}
         */
        getLocations : function (mat_id) {
            var locations = $('.locations-list').children();
            var places_list = [];
            for (var i = 0; i < locations.length; i++ ){
                var $location = $(locations[i]);
                if ($location.children('.materials_id').html() == mat_id){
                    places_list[parseInt($location.children('.stocks_id').html())] =
                        parseInt($location.children('.qty').html());
                }
            }
            return places_list;
        },
        
        /**
         * 
         * @param available 
         * @param qty
         * @returns {number}
         */
        updatePlaces : function (available, qty) {
            var $places_container = $('#movements-stocks_id');
            var $places_dropdown = $places_container.children('option');
            var options_number = 0;
            $places_dropdown.each(function(i, opt){
                $(opt).show();
                if (available[parseInt($(opt).val())] === undefined || available[parseInt($(opt).val())] < parseInt(qty)){
                    $(opt).attr("selected", false);
                    $(opt).hide();
                }else{
                    $(opt).attr("selected", "selected");
                    options_number ++;
                }
            });
            return options_number;
        },

        /**
         * 
         * @param id
         * @returns {string|JQuery}
         */
        getPlaceNames : function (id) {
            var name = '#place_' + id;
            return $('.stocks-list').find(name).html();
        }
    };

    /**
     * Fills recipients field when material comes to the stock.
     */
    $('#movements-direction').on('change', function(){
        $('#movements-qty').change();
        var uname;
        if (this.value == 0){
            uname = FormData.userName();
        }else{
            uname = 'Эльвира';
        }
        FormData.setDealerName($('#movements-person_receiver'), uname);
    });

    /**
     * Checks if material is available at the stock then displays a message and change stock places dropdown.
     */
    $('#movements-qty').on('change', function(){
        //noinspection JSUnresolvedFunction
        $('#movements-stocks_id').attr("disabled", false);
        if ($('#movements-direction').val() == 0) {
            var materialId = parseInt($('#movements-longname').autocomplete(':selected').val());
            var enabledStockPlaces = FormData.getLocations(materialId);
            var $modal_window = $("#qty-modal");
            var $modal_body = $('.modal-body');
            if (enabledStockPlaces.length < 1) {
                //noinspection JSUnresolvedFunction
                $modal_window.modal('show');
                setTimeout('location.replace("/movements")', 2000);
            } else {
                var message = 'Расположение:' + '<br />';
                enabledStockPlaces.forEach(function (item, i, arr) {
                        message += 'Складское место:' + FormData.getPlaceNames(i) + ', количество: ' + item + '<br />';
                });
                $modal_body.find('p').html(message);
                $modal_window.modal('show');
                var opt_available = FormData.updatePlaces(enabledStockPlaces, this.value);
                if (opt_available == 0) {
                    $('#movements-stocks_id').attr("disabled", "disabled");
                    message = 'Ни на одном складском месте нет такого количества материала.' + '<br />' + 'Попробуйте взять меньше.';
                    $modal_body.find('p').html(message);
                    $modal_window.modal('show');
                    $(this).val(null);
                }
            }
        }
    });


})(jQuery);
