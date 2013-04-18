<script type="text/javascript"
        src="http://maps.google.com/maps/api/js?v=3.9&sensor=false&&libraries=weather&language=ru&callback=initMap">
</script>
<script type="text/javascript">
    var geocoder;
    var icon = '/public/images/ih.png';
    var center;
    var map;
    var currentPopup;
    function addMarker(lat, lng, info) {
        var point = new google.maps.LatLng(lat, lng);
        var marker = new google.maps.Marker({
            position: point,
            icon: icon,
            map: map
        });
        google.maps.event.addListener(marker, "click", function() {
            popup = new google.maps.InfoWindow({
                content: info,
                maxWidth: 200
            });
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
        geocoder = new google.maps.Geocoder();
        for(var i=0;i<destinations.length;i++){
            if((destinations[i].Lat == "0.000000" || destinations[i].Lat == "") && (destinations[i].Long == "0.000000" || destinations[i].Long == "")){
            }else{
                var latlng = new google.maps.LatLng(destinations[i].Lat,destinations[i].Long);
            }

        }
        map = new google.maps.Map(document.getElementById("map_canvas"), {
            zoom: 8,
            center: latlng,
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

        var arr = new Array();

        for(var i = 0; i<destinations.length; i++){
            var city = destinations[i];

            if((city.Lat == "0.000000" || city.Lat == "") && (city.Long == "0.000000" || city.Long == "")){
                arr.push(city);
            } else {
                addMarker(city.Lat, city.Long, getInfo(city));
            }
        }

        if(arr.length !=0){
            for(var i=0;i<arr.length;++i){
                (function(info){
                    geocoder.geocode( { 'address': arr[i].City + ',' + arr[i].HotelAddress + ',' + arr[i].Country},
                            function(results, status) {
                                if (status == google.maps.GeocoderStatus.OK) {
                                    addMarker(results[0].geometry.location.lat(), results[0].geometry.location.lng(),info);
                                }
                            })
                })(getInfo(arr[i]));
            }
        }

        var weatherLayer = new google.maps.weather.WeatherLayer({
            temperatureUnits: google.maps.weather.TemperatureUnit.Celsius
        });
        weatherLayer.setMap(map);
    }

    function getCoordinates(){

    }

    function getInfo(city){
        return info = "<a href='<?=baseUrl().'/site/details?HotelCode='?>"+city.HotelCode+"'"+"><b>"+city.HotelName+"</b></a>"+
                "<br><img src='"+city.Image+"' width=180 height=150 alt=image>"+
                "<br><b><img src='<? echo baseUrl() . '/public/images/star_icon.png?>'?>'/>"+city.StarRating+"</b>"+
                "<br>Полная стоимость: <b>$"+city.Price+"</b>";
    }
</script>
<span id="title">
    Найдено <? echo count($coord); ?> отелей.
</span>
    <? if(count($coord) != 0): ?>
<div  id="map_canvas" style="height:500px;margin-top: 20px">

</div>
    <? endif; ?>






