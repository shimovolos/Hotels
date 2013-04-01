<?
$coord = array();
foreach ($test as $hotels) {
    $coord[] = array(
        'HotelName' => $hotels->HotelName,
        'HotelCode' => $hotels->HotelCode,
        'Long' => $hotels->Longitude,
        'Lat' => $hotels->Latitude
    );
}
?>
<script type="text/javascript"
        src="http://maps.google.com/maps/api/js?v=3.9&sensor=false&hl=ru&callback=initMap">
</script>
<script>

    function loadAPI()
    {
        var script = document.createElement("script");
        script.src = "http://maps.google.com/maps/api/js?v=3.9&sensor=false&hl=ru&callback=initMap";
        script.type = "text/javascript";
        document.getElementsByTagName("head")[0].appendChild(script);
    }

    var icon = new google.maps.MarkerImage(
        "<?=Yii::app()->baseUrl.'/public/images/ih.png'?>",
        new google.maps.Size(32, 32), new google.maps.Point(0, 0),
        new google.maps.Point(16, 32)
    );
    var center = null;
    var map = null;
    var currentPopup;
    var bounds = new google.maps.LatLngBounds();
    function addMarker(lat, lng, info) {
        var point = new google.maps.LatLng(lat, lng);
        //bounds.extend(point);
        var marker = new google.maps.Marker({
            position: point,
            icon: icon,
            map: map
        });
        var popup = new google.maps.InfoWindow({
            content: info,
            maxWidth: 300
        });
        google.maps.event.addListener(marker, "click", function() {
            if (currentPopup != null) {
                currentPopup.close();
                currentPopup = null;
            }
            popup.open(map, marker);
            currentPopup = popup;
        });
    }

    function initMap() {
        var destinations = <?=json_encode($coord)?>;
        map = new google.maps.Map(document.getElementById("map_canvas"), {
            center: new google.maps.LatLng(0, 0),
            zoom: 5,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            mapTypeControl: true,
            mapTypeControlOptions: {
                style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
            },
            navigationControl: true,
            navigationControlOptions: {
                style: google.maps.NavigationControlStyle.BIG
            }
        });
        for(var i = 0; i<destinations.length; i++){
            var city = destinations[i];
            addMarker(city.Lat,city.Long,'<b>'+ city.HotelName+'</b><br/>'+city.HotelCode);
        }
        map.fitBounds(bounds);
    }
</script>


<div id="map_canvas" style="width:100%; height:500px">

</div>



