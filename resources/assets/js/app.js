
/**
 * First we will load all of this project's JavaScript dependencies which
 * include Vue and Vue Resource. This gives a great starting point for
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the body of the page. From here, you may begin adding components to
 * the application, or feel free to tweak this setup for your needs.
 */

require('./vendor/datepicker/bootstrap-datepicker');


(function($){
    $(document).ready(function(){
        let options = {
            todayBtn: "linked",
            keyboardNavigation: false,
            forceParse: true,
            autoclose: true,
            format: 'mm/dd/yyyy'
        };

        $('.datepicker').datepicker(options);

        //check if function exists already
        if(!window.hasOwnProperty('confirmDelete')){
            /**
             * Use this for confirming deletion to the user
             *
             * @param item
             */
            window.confirmDelete = (item) => {
                if(!confirm('Are you sure you want to delete this '+item+'?')){
                    event.preventDefault();
                }
            }
        }
    })
})(jQuery);
