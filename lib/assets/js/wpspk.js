/*===============================
=           ADMIN SCRIPTS       =
===============================*/

jQuery(function($) {
  jQuery("#spektrix_show_data").change(function() {
    $('#title').attr('value' , this.value.split("|")[1]);
    $('label[for="title"]').addClass('screen-reader-text');
  });
});
