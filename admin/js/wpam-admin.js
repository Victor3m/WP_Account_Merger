(function( $ ) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

  $( function() {

    $("input:text").on("keyup",function() {
      var dataList = $("datalist[id=" + $(this).attr("list") + "]");
      dataList.empty();
      if ($(this).val() == "") {
        dataList.empty();
      } else if ($(this).val().length >= 3) {
        var user = $(this).val();
        jQuery.ajax({
          type: "GET",
          url: "http://test-wpaccountmerger.local/wp-content/plugins/WP_Account_Merger/includes/user-search.php?user=" + user,
          success: function(data) {
            var array = JSON.parse(data);
            console.log(array);
            for (var key in array) {
              var option = document.createElement("option");
              option.text = array[key].user_login;
              dataList.append(option);
            }
          },
        });
      }
    });

    $("#account-details-table thead tr th input:radio").change(function() {
      var columnIndex = $(this).parent().index();
      var rows = $(this).parents("table").find("tbody tr");
      if (this.checked) {
        rows.each(function() {
          $(this).find("td:eq(" + columnIndex + ") input:radio").prop("checked", true);
        });
      } else {
        rows.each(function() {
          $(this).find("td:eq(" + columnIndex + ") input:radio").prop("checked", false);
        });
      }
    });

    $("tbody tr td input:radio").change(function() {
      if (this.checked) {
        $("#account-details-table thead tr th input:radio").prop("checked", false);
      }
    })

    $("table thead tr th input:checkbox").change(function() {
      if (this.checked) {
        $(this).parents("table").find("input:checkbox").prop("checked", true);
      } else {
        $(this).parents("table").find("input:checkbox").prop("checked", false);
      }
    })

    $("table tbody tr td input:checkbox").change(function() {
      if (!this.checked) {
        $(this).parents("table").find("thead tr th input:checkbox").prop("checked", false);
      }
    })
  })
})( jQuery );
