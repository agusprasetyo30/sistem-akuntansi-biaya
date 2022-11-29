$(document).ready((function () {
    "use strict";
    $(".select2").select2({
        minimumResultsForSearch: 1 / 0,
        width: "100%"
    }), $(".select2-show-search").select2({
        minimumResultsForSearch: "",
        width: "100%"
    }), $("#select2").select2({
        dropdownCssClass: "hover-success",
        minimumResultsForSearch: 1 / 0,
        width: "100%"
    }), $("#select3").select2({
        dropdownCssClass: "hover-danger",
        minimumResultsForSearch: 1 / 0
    }), $("#select4").select2({
        containerCssClass: "select2-outline-success",
        dropdownCssClass: "bd-success hover-success",
        minimumResultsForSearch: 1 / 0
    }), $("#select5").select2({
        containerCssClass: "select2-outline-info",
        dropdownCssClass: "bd-info hover-info",
        minimumResultsForSearch: 1 / 0
    }), $("#select6").select2({
        containerCssClass: "select2-full-color select2-primary",
        minimumResultsForSearch: 1 / 0
    }), $("#select7").select2({
        containerCssClass: "select2-full-color select2-danger",
        dropdownCssClass: "hover-danger",
        minimumResultsForSearch: 1 / 0
    }), $("#select8").select2({
        dropdownCssClass: "select2-drop-color select2-drop-primary",
        minimumResultsForSearch: 1 / 0
    }), $("#select9").select2({
        dropdownCssClass: "select2-drop-color select2-drop-indigo",
        minimumResultsForSearch: 1 / 0
    }), $("#select10").select2({
        containerCssClass: "select2-full-color select2-primary",
        dropdownCssClass: "select2-drop-color select2-drop-primary",
        minimumResultsForSearch: 1 / 0
    }), $("#select11").select2({
        containerCssClass: "select2-full-color select2-indigo",
        dropdownCssClass: "select2-drop-color select2-drop-indigo",
        minimumResultsForSearch: 1 / 0
    })
}));
