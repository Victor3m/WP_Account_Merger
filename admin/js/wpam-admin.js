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

  // Source dropdown
  $( function() {
    const dropdownList = $("#source_dropdownList");
    const dropdown = $(".source_users_dropdown");
    const input = $("#source_user_id");

    input.on("keyup", () => {
      dropdown.addClass("show");

      const searchTerm = input.val().toLowerCase();

      dropdownList.find("li").each((_, el) => {
        const text = $(el).text().toLowerCase();
        if (text.includes(searchTerm)) {
          $(el).show();
        } else {
          $(el).hide();
        }
      })
    })

    $("*").on("focus click", (e) => {
      if(!$(e.target).is(input) && !$(e.target).is(dropdown)) {
        dropdown.removeClass("show");
      }
    })

    dropdownList.on("click", "li", (e) => {
      var selectedUser = $(e.target).text();

      input.val(selectedUser);

      dropdown.removeClass("show");
    })
  })

  // Target dropdown
  $( function() {
    const dropdownList = $("#target_dropdownList");
    const dropdown = $(".target_users_dropdown");
    const input = $("#target_user_id");

    input.on("keyup", () => {
      dropdown.addClass("show");

      const searchTerm = input.val().toLowerCase();

      dropdownList.find("li").each((_, el) => {
        const text = $(el).text().toLowerCase();
        if (text.includes(searchTerm)) {
          $(el).show();
        } else {
          $(el).hide();
        }
      })
    })

    $("*").on("focus click", (e) => {
      if(!$(e.target).is(input) && !$(e.target).is(dropdown)) {
        dropdown.removeClass("show");
      }
    })

    dropdownList.on("click", "li", (e) => {
      var selectedUser = $(e.target).text();

      input.val(selectedUser);

      dropdown.removeClass("show");
    })
  })



})( jQuery );
