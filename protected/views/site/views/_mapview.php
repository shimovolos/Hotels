<script type="text/javascript"
        src="http://maps.google.com/maps/api/js?v=3.9&sensor=false&hl=ru&callback=initMap">
</script>
<script type="text/javascript">
    var geocoder;
    var icon = '/public/images/ih.png';
    var center = null;
    var map = null;
    var currentPopup;
    var bounds = new google.maps.LatLngBounds();
    function addMarker(lat, lng, info) {
        var point = new google.maps.LatLng(lat, lng);
       // bounds.extend(point);
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
        var address = <?=json_encode($result) ?>;
        geocoder = new google.maps.Geocoder();
        $('#map_canvas')
        map = new google.maps.Map(document.getElementById("map_canvas"), {
            center: new google.maps.LatLng(destinations[0].Lat, destinations[0].Long),
            zoom: 10,
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
        if(address != null){
            for(var i=0;i<address.length;i++){
                var loc = address[i];
                codeAddress(loc.HotelCode);
            }
        }
        for(var i = 0; i<destinations.length; i++){
            var city = destinations[i];
            addMarker(city.Lat,city.Long,"<b>"+ city.HotelName+"</b><br/>"+city.HotelCode);
        }

       // map.fitBounds(bounds);
    }
    function codeAddress(address)
    {
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    position: results[0].geometry.location,
                    map: map,
                    icon: icon
                });
            } else {
                alert("Geocode was not successful for the following reason: " + status);
            }
        });
    }
</script>


<div  id="map_canvas" style="height:500px">

</div>






