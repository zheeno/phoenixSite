// var host = "https://courier.phoenixexpress.org";
var host = "http://phoenix.cluster/courier";

var spinner = "<div style='top:0px;left:0px;position:fixed' class='w-100 h-100 flex-center center-align'><span class='fa fa-spinner fa-spin fa-4x blue-text'></span></div>";
$('.weight-options a').on('click', function (event) {
    $("#weight").empty().text($(this).text())
})

$('.tracking-form').on('submit', function (evt) {
    evt.preventDefault();
    var id = escape($("#track-id").val());
    if (id.length > 10) {
        window.location = host + "/tracking/" + id + "/trackParcel";
    }
})
$('.send-parcel').on('click', function () {
    var url = host + "/parcel-manager.php?addParcel";
    $(".console-screen").append(spinner).load(url, function () {
        //...
    });
})

$(".find-parcel").on("click", function () {
    $(".find-parcel-form input").focus();
})

$(".find-parcel-form").on("submit", function (e) {
    e.preventDefault();
    var keyword = escape($(".find-parcel-form input").val());
    var url = host + "/parcel-manager.php?keyword=" + keyword + "&allParcels";
    $(".console-screen").append(spinner).load(url, function () {
        //...
    });
})

$(".all-parcels").on("click", allParcels);
$(".console-screen").ready(allParcels);
function allParcels() {
    var url = host + "/parcel-manager.php?allParcels";
    $(".console-screen").append(spinner).load(url, function () {
        //...
    });
}



/////////////////////////////////////////////////////