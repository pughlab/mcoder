jQuery.fn.extend({
createRepeater: function () {
        var addItem = function (items, key) {
            var itemContent = items;
            var group = itemContent.data("group");
            var item = itemContent;
            var input = item.find('input,select');
            input.each(function (index, el) {
                var attrName = $(el).data('name');
                var skipName = $(el).data('skip-name');
                if (skipName != true) {
                    $(el).attr("name", group + "[" + key + "]" + attrName);
                } else {
                    if (attrName != 'undefined') {
                        $(el).attr("name", attrName);
                    }
                }
            })
            var itemClone = items;

            /* Handling remove btn */
            var removeButton = itemClone.find('.remove-btn');

            if (key == 0) {
                removeButton.attr('disabled', true);
            } else {
                removeButton.attr('disabled', false);
            }

            $("<div class='items'>" + itemClone.html() + "<div/>").appendTo(repeater);
        };
        /* find elements */
        var repeater = this;
        var items = repeater.find(".items");
        var key = 0;
        var addButton = repeater.find('.repeater-add-btn');
        var newItem = items;

        if (key == 0) {
            items.remove();
            addItem(newItem, key);
        }

        var ids=1; /* id for datepickers*/
        /* handle click and add items */
        addButton.on("click", function () {
            key++;
            addItem(newItem, key);
            $('.selectpicker').selectpicker('refresh'); /* refresh the select field each time*/
            $('.comdates:last').attr('id', 'comdates_' + ids);// each datepicker must have a unique id.
            $('.cl1:last').attr('id', 'cl1_'+ ids);
            $('.cl2:last').attr('id', 'cl2_'+ ids);
            $('.cl3:last').attr('id', 'cl3_'+ ids);
            $('.cl4:last').attr('id', 'cl4_'+ ids);
            $('.cl5:last').attr('id', 'cl5_'+ ids);
            $('.cl6:last').attr('id', 'cl6_'+ ids);

            $('.cl1:last').attr('name', 'cl_'+ ids);
            $('.cl2:last').attr('name', 'cl_'+ ids);
            $('.cl3:last').attr('name', 'cl_'+ ids);
            $('.cl4:last').attr('name', 'cl_'+ ids);
            $('.cl5:last').attr('name', 'cl_'+ ids);
            $('.cl6:last').attr('name', 'cl_'+ ids);

            $('.cll1:last').attr('for', 'cl_'+ ids);
            $('.cll2:last').attr('for', 'cl_'+ ids);
            $('.cll3:last').attr('for', 'cl_'+ ids);
            $('.cll4:last').attr('for', 'cl_'+ ids);
            $('.cll5:last').attr('for', 'cl_'+ ids);
            $('.cll6:last').attr('for', 'cl_'+ ids);
            ids++; // increase the id numbers

        });

        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = yyyy + '-' + mm + '-' + dd + ' 00:00:00';

        $(document).on('focus',".comdates", function(){ //bind to all instances of class "comdates".
          $(this).datePicker({
            hasShortcut: true,
            language: 'en',
            min:'1900-01-01 04:00:00',
            max: today,
            shortcutOptions: [{
              name: 'Today',
              day: '0'
            }, {
              name: 'Yesterday',
              day: '-1'
            }, {
              name: 'One week ago',
              day: '-7'
            }]
          });
        });

    }
});
