var all = false;
function All() {
    $('#all').text(all ? "All" : "Clear")
    all = !all;
    $('input[type="checkbox"]').prop("checked", all)
}