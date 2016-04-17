jQuery(document).ready(function ($) {
  "use strict";

// Operations Page Latest Updates ScrollTo Functions
  var paneTarget = $(".latest-updates .view-content");

  $(document).ajaxSuccess(function () {
    paneTarget.stop().scrollTo("100%", 2000);
  });

// makes top button scrollable
  $("#top-ops").click(function () {
    paneTarget.stop().scrollTo("0%", 2000);
  });
// makes bottom button scrollable
  $("#bottom-ops").click(function () {
    paneTarget.stop().scrollTo("100%", 2000);
  });

// Show and Hide top scroll button
  paneTarget.scroll(function () {
    if (paneTarget.scrollTop() > 0) {
      $(".top-onload").hide();
      $("#top-ops").fadeIn("fast");
      }
      else if (paneTarget.scrollTop() === 0) {
        $(".top-onload").fadeIn("fast");
        $("#top-ops").hide();
      }
  });

 //  makes the bottom circle a scroll button
  $(document).delegate(".latest-updates .pager-next", "click", function () {
    paneTarget.stop().scrollTo("100%", 2000);
  });
  $(".year_group").click(function () {
    $(this).find(".months").toggle();
    $(this).find(".year").toggleClass("year-active");
  });
// SEARCH FIELD
  $(".search_button").click(function () {
    $("#views-exposed-form-view-search-page-panel-pane-1").submit();
  });
// fixes style tweak for chosen facets, should be pushed back into the module.
  $(".chosen-container-single").removeAttr("style");

});
