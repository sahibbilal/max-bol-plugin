
jQuery(document).ready( function () {
    //show add plugin zip modal and also get data for update
    jQuery(document).on('click', '.max_import_orders', function (e) {
        var ajax_url    = jQuery(this).data('ajax_url');
        var token_url   = jQuery(this).data('token_url');
        var single_url  = jQuery(this).data('single_url');
        var account_id  = jQuery(this).data('account_id');
        var user_name   = jQuery(this).data('user_name');
        var password    = jQuery(this).data('password');
        var orders_url  = jQuery(this).data('orders_url');
        var account     = jQuery(this).data('account');
        $that           = jQuery(this);
        $that.attr('disabled', true);
        jQuery(document).find('.max_res').empty();
        jQuery.ajax({
            url: ajax_url,
            data: {
                action      : "max_import_orders_from_api",
                token_url   : token_url,
                single_url  : single_url,
                account_id  : account_id,
                user_name   : user_name,
                password    : password,
                orders_url  : orders_url,
                account     : account,
            },
            type: "post",
            dataType: "json",
            success: function (res) {
                $that.attr('disabled', false);
                // if(res == 200){
                //     $that.closest('td').find('p').append('Crone job completed sucessfully');
                // }
                // if(res == 201){
                //     $that.closest('td').find('p').append('Error in generating access token');
                // }
                // if(res == 202){
                //     $that.closest('td').find('p').append('Orders are empty');
                // }
                // if(res == 203){
                //     $that.closest('td').find('p').append('Order products are empty');
                // }
            }
        });
    });
});