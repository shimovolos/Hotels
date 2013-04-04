<script type="text/javascript"
        src="http://maps.google.com/maps/api/js?v=3.9&sensor=false&language=ru&callback=initMap">
</script>
<!--&libraries=weather-->
<script type="text/javascript">
    var geocoder;
    var icon = '/public/images/ih.png';
    var center = null;
    var map = null;
    var currentPopup;
    function addMarker(lat, lng, info) {
        var point = new google.maps.LatLng(lat, lng);
        var marker = new google.maps.Marker({
            position: point,
            icon: icon,
            map: map
        });
        var popup = new google.maps.InfoWindow({
            content: info,
            maxWidth: 200
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
        map = new google.maps.Map(document.getElementById("map_canvas"), {
            zoom: 12,
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
                info = "<a href='<?=baseUrl().'/site/details?HotelCode='?>"+loc.HotelCode+"'"+"><b>"+loc.HotelName+"</b></a>"+
                        "<br><img src='"+loc.Image+"'width=180 height=150 alt=image>"+
                        "<br><b><img src='<? echo baseUrl() . '/public/images/star_icon.png?>'?><!--'/>"+loc.StarRating+"</b>"+
                        "<br>Полная стоимость: <b>$"+loc.Price+"</b>";
                codeAddress(loc.HotelAddress,info);
            }
        }
        for(var i = 0; i<destinations.length; i++){
            var city = destinations[i];
            addMarker(city.Lat,city.Long,"<a href='<?=baseUrl().'/site/details?HotelCode='?>"+city.HotelCode+"'"+"><b>"+city.HotelName+"</b></a>"+
                    "<br><img src='"+city.Image+"'width=180 height=150 alt=image>"+
                    "<br><b><img src='<? echo baseUrl() . '/public/images/star_icon.png?>'?><!--'/>"+city.StarRating+"</b>"+"<br>Полная стоимость: <b>$"+city.Price+"</b>");
        }
//        var weatherLayer = new google.maps.weather.WeatherLayer({
//            temperatureUnits: google.maps.weather.TemperatureUnit.Celsius
//        });
//        weatherLayer.setMap(map);

    }
    function codeAddress(address,info)
    {
        geocoder.geocode( { 'address': address}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                var marker = new google.maps.Marker({
                    position: results[0].geometry.location,
                    map: map,
                    icon: image
                });
                var popup = new google.maps.InfoWindow({
                    content: info,
                    maxWidth: 200
                });
                google.maps.event.addListener(marker, "click", function() {
                    if (currentPopup != null) {
                        currentPopup.close();
                        currentPopup = null;
                    }
                    popup.open(map, marker);
                    currentPopup = popup;
                });
            } else {
//                alert("Некоторые отели не были отображениы по следующим причинам: " + status);
            }
        });
    }
</script>


<div  id="map_canvas" style="height:500px">

</div>






