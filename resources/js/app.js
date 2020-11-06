; (function ($) {
   $.fn.toJSON = function () {
      var $elements = {};
      var $form = $(this);
      $form.find('input, select, textarea').each(function () {
         var name = $(this).attr('name')
         var type = $(this).attr('type')
         if (name) {
            var $value;
            if (type == 'radio') {
               $value = $('input[name=' + name + ']:checked', $form).val()
            } else if (type == 'checkbox') {
               $value = $(this).is(':checked')
            } else {
               $value = $(this).val()
            }
            $elements[$(this).attr('name')] = $value
         }
      });
      return JSON.stringify($elements)
   };

   $.fn.loadJSON = function (json_string) {
      var $form = $(this)
      var data = JSON.parse(json_string)
      $.each(data, function (key, value) {
         var $elem = $('[name="' + key + '"]', $form)
         var type = $elem.first().attr('type')
         if (type == 'radio') {
            $('[name="' + key + '"][value="' + value + '"]').prop('checked', true)
         } else if (type == 'checkbox' && (value == true || value == 'true')) {
            $('[name="' + key + '"]').prop('checked', true)
         } else {
            $elem.val(value)
         }
      })
   };

   $.fn.resetForm = function () {
      var $form = $(this)
      $form.find("input[type=text],input[type=hidden], textarea").val("")
      $form.find('input:checkbox').removeAttr('checked');
   }

}(jQuery));

var func = {}
