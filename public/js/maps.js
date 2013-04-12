var geocoder;
var image = '/public/images/ih.png';
var map;

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
            }
        });
    }else{
        addMarker(latlng);
    }
}