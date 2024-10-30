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

  $( function() {
    if (typeof getUserList === 'undefined') {
      return;
    }

    const userList = getUserList();

    $("input:text").on("keyup",function() {
      var input = $(this);

      makeList(input);
    });

    function makeList(input) {
      var ul = input.parents("div[class*='users_dropdown']").find("ul");
      if (userList.length > 0) { 
        var results = filterUsers(input.val());
        if (results.length > 0) {
          var list = "";
          for (var i = 0; i < results.length; i++) {
            list += "<li>" + results[i].user_login + "</li>";
          }
          ul.html(list);
        }
      }
    }

    function filterUsers( key ) {
      var results = [];
      for (var i = 0; i < userList.length; i++) {
        if (userList[i].user_login.toLowerCase().includes(key.toLowerCase())) {
          results.push(userList[i]);
        } else if (userList[i].user_nicename.toLowerCase().includes(key.toLowerCase())) {
          results.push(userList[i]);
        } else if (userList[i].user_email.toLowerCase().includes(key.toLowerCase())) {
          results.push(userList[i]);
        } else if (userList[i].id == key.toLowerCase()) {
          results.push(userList[i]);
        }
      }

      return results;
    }

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
