;(function ($) {
    $(function () {
        var $ul = $('.custom-post-parents-selectedlist');
        var $adders = $('.custom-post-parents-selectlist-add');

        var cache = {};
                        $.post(ajaxurl, {
                        'action': 'custom-post-parents_search_post_types'
                    },
                    function (result) {
                            var it=$("<option>").attr("name","custom-post-parents-post_types");
                            it.text("Choose option:");
                            $('.custom-post-parents-selectlist-add').append(it);
                        $.each(result.data.lookup, function (index, item) {
                            cache[index] = {value: index, label: item};
                            var it=$("<option>").attr("name","custom-post-parents-post_types").attr("id",item);
                            it.text(item);
                            $('.custom-post-parents-selectlist-add').append(it);
                        });
                    }
                );
       /* var tags=[{"nombre":"","padre":""},{"nombre":"persona","padre":"page"},{"nombre":"pepe","padre":"proyecto"},{"nombre":"juan","padre":"page"},{"nombre":"manolo","padre":"persona"}];
        $.each(tags,function(i,v)
        {
            var item=$("<option>").attr("name","custom-post-parents-post_types");
            item.text(v.nombre);
            $('.custom-post-parents-selectlist-add').append(item);   
        })*/
        //$('.custom-post-parents-selectlist-add').change(function(event){
        $('.custom-post-parents-selectlist-add').change(function(event){
            var s=event.target;
            var section=s.getAttribute("data-section")
            var selectedElements = $('input[name="custom-post-parents-post_types['+section+'][]"]');
            var currentVals = [];
            var valActual=$(this).val();
            var seleccionado=$("#cpt-f-"+section+" option:selected");
            if(valActual!="Choose option:")
                $("#cpt-f-"+section+" option:selected").hide();
            $.each(selectedElements, function (i,v) {
                    currentVals.push($(this).val());
                });
               var v=$.inArray(valActual, currentVals) === -1;
            if ($.inArray(valActual, currentVals) === -1 && valActual!="Choose option   :") {
                    var s=event.target;
                    var section=s.getAttribute("data-section");
                    var ite = $([
                         '<span>',
                         '<input name="custom-post-parents-post_types['+section+'][]" type="hidden" value="'+valActual+'"/>',
                         "<a name='"+valActual+"'class='ntdelbutton'><span class='remove-tag-icon' aria-hidden='true'></span></a>&nbsp;",
                         valActual,
                         '</span>'
                     ].join(''));
                             $ul.on('click', '.ntdelbutton',mostrar(section));
                     $("div[data-section='"+section+"']").append(ite);
            }
                
                //$(".custom-post-parents-selectlist-add option:selected").remove();
        })
        
        
       /* $adders.autocomplete({
            minLength: 0,
            source: function( request, response ) {
                var term = request.term;
                if ( term in cache ) {
                    response( cache[ term ] );
                    return;
                }
                $.post(ajaxurl, {
                        'action': 'custom-post-parents_search_post_types'
                    },
                    function (result) {
                        $.each(result.data.lookup, function (index, item) {
                            cache[index] = {value: index, label: item};
                        });
                        response( cache );
                    }
                );
            },
            select: function( event, ui ) {
                var label = ui.item.label;
                var type = ui.item.value;
                var $input = $(event.target);
                var section = $input.data('section');
                var selectedElements = $('input[name="custom-post-parents-post_types['+section+'][]"]');
                var currentVals = [];
                $.each(selectedElements, function () {
                    currentVals.push($(this).val());
                });

                if ($.inArray(type, currentVals) === -1) {
                    var item = $([
                        '<span>',
                        '<input name="custom-post-parents-post_types['+section+'][]" type="hidden" value="'+type+'" />',
                        '<a class="ntdelbutton"><span class="remove-tag-icon" aria-hidden="true"></span></a>&nbsp;',
                        label,
                        '</span>'
                    ].join(''));
                    $('.custom-post-parents-selectedlist[data-section="'+section+'"]').append(item);
                }
                /*if ($.inArray(type, currentVals) === -1) {
                    var item = $([
                        '<option name="custom-post-parents-post_types['+section+'][]" type="hidden" value="'+type+'">',
                        label,
                        '</option>'].join(''));
                    $('.custom-post-parents-selectedlist[data-section="'+section+'"]').append(item);
                }

                $input.val('');
                return false;
            }
        }).focus(function(){       
            $(this).autocomplete( "search",$(this).val());
            //$(this)
        })*/;;


    });
    function mostrar(section)
    {
        return function(e)
        {
            e.preventDefault();
            var texto=$(this).attr("name");
            $("#cpt-f-"+section+" >option[id='"+texto+"']").show();
            $(this).parents('span').remove();
        }
    }
})(jQuery);