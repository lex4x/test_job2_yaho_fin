/**
 * Created by lex4 on 04.03.2016.
 */

$('.show_contact_me').on('click', function(e){
    e.preventDefault();
    if($('#header_about_me').hasClass('invisible')){
        $('.contact_link').parent().addClass('active');
        $('#header_about_me').slideDown(function() {
            $(this).removeClass('invisible')
        });
    } else {
        $('.contact_link').parent().removeClass('active');
        $('#header_about_me').slideUp( "slow", function() {
            $(this).addClass('invisible')
        });
    }

});

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

(function(){

    wwt_data_parser_function = function (data_type_node) {
        data_type_node.find('[data-type]').each(function(i, node){
            var function_name = normalize_name($(node).data('type'));
            var object;
            if (typeof window[function_name] === 'function'){
                object = new window[function_name]($(node));
            }
        });
    };

    wwt_data_parser_function($(document));


    function normalize_name(name){
//        return name.replace(/_(\w)/g,)
        name = name.replace(/(?:_)\w/g, function(match){
            return match.toUpperCase();
        });
        name = name.replace(/_/g, '');
        return name;
    }


})();