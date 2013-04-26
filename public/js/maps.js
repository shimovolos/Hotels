var geocoder;
var image = '/public/images/ih.png';
var map;
var markers = Array();
var infos = Array();

function addMarker(point)
{
    var marker = new google.maps.Marker({
        position: point,
        icon: image,
        map: map,
        draggable:true
    });
    google.maps.event.addListener(marker, 'click', function(){
        if (marker.getAnimation() != null) {
            marker.setAnimation(null);
        } else {
            marker.setAnimation(google.maps.Animation.BOUNCE);
        }
    });
}

function clearOverlays()
{
    if (markers) {
        for (i in markers) {
            markers[i].setMap(null);
        }
        markers = [];
        infos = [];
    }
}

function clearInfo()
{
    if (infos) {
        for (i in infos) {
            if (infos[i].getMap()) {
                infos[i].close();
            }
        }
    }
}

function initialize(address,lat,long,country,city)
{
    geocoder = new google.maps.Geocoder();

    var latlng = new google.maps.LatLng(lat,long);
    var settings = {
        zoom: 16,
        center: latlng,
        mapTypeControl: true,
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
        navigationControl: true,
        navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    map = new google.maps.Map(document.getElementById("map_canvas"),settings);
    map.setMapTypeId(google.maps.MapTypeId.TERRAIN);

    var transitLayer = new google.maps.TransitLayer();
    transitLayer.setMap(map);

    if((lat == "0.000000" || lat == "") && (long == "0.000000" || long == "")){
        geocoder.geocode( { 'address': city+','+address+','+country}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                map.setCenter(results[0].geometry.location);
                addMarker(results[0].geometry.location);
                document.getElementById('lat').value = results[0].geometry.location.lat();
                document.getElementById('lng').value = results[0].geometry.location.lng();
            }
        });
    }else{
        addMarker(latlng);
        document.getElementById('lat').value = lat;
        document.getElementById('lng').value = long;

    }
}

function findPlaces()
{
    var type = document.getElementById('gmap_type').value;
    var radius = document.getElementById('gmap_radius').value;
    var lat = document.getElementById('lat').value;
    var lng = document.getElementById('lng').value;
    var cur_location = new google.maps.LatLng(lat, lng);
    var request = {
        location: cur_location,
        radius: radius,
        types: [type]
    };

    service = new google.maps.places.PlacesService(map);
    service.search(request, createMarkers);
}

function createMarkers(results, status)
{
    if (status == google.maps.places.PlacesServiceStatus.OK) {
        clearOverlays();
        for (var i = 0; i < results.length; i++) {
            createMarker(results[i]);
        }
    } else if (status == google.maps.places.PlacesServiceStatus.ZERO_RESULTS) {
        alert('Извините, ничего не найдено');
    }
}

function createMarker(obj)
{
    var mark = new google.maps.Marker({
        position: obj.geometry.location,
        map: map,
        title: obj.name
    });
    markers.push(mark);

    var infowindow = new google.maps.InfoWindow({
        content: '<img src="' + obj.icon + '" /><font style="color:#000;">' + obj.name +
            '<br />Рейтинг: ' + obj.rating + '<br />Адрес: ' + obj.vicinity + '</font>'
    });

    google.maps.event.addListener(mark, 'click', function() {
        clearInfo();
        infowindow.open(map,mark);
    });
    infos.push(infowindow);
}