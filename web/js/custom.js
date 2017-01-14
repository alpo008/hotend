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
        }
    };

    /**
     * Fills recipients field when material comes to the stock.
     */
    $('#movements-direction').on('change', function(){
        if (this.value == 1){
            FormData.setDealerName($('#movements-person_receiver'), FormData.userName())
        }
    })

})(jQuery);
