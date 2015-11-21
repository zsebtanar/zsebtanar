window.onload = function () {
    new Ajax.Autocompleter("keyword", "autocomplete_choices", base_url+"application/AjaxSearch", {})
}