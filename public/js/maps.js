var geocoder;
var image = '/public/images/ih.png';
var map;

function initialize(address)
{
    geocoder = new google.maps.Geocoder();

    var latlng = new google.maps.LatLng(0,0);
    var settings = {
        zoom: 16,
        center: latlng,
        mapTypeControl: true,
        mapTypeControlOptions: {style: google.maps.MapTypeControlStyle.DROPDOWN_MENU},
        navigationControl: true,
        navigationControlOptions: {style: google.maps.NavigationControlStyle.SMALL},
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };

    var map = new google.maps.Map(document.getElementById("map_canvas"),settings);
    map.setMapTypeId(google.maps.MapTypeId.TERRAIN);

    var transitLayer = new google.maps.TransitLayer();
    transitLayer.setMap(map);

    geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                position: results[0].geometry.location,
                map: map,
                icon: image,
                draggable:true,
                animation: google.maps.Animation.DROP
            });
            google.maps.event.addListener(marker, 'click', function(){
                if (marker.getAnimation() != null) {
                    marker.setAnimation(null);
                } else {
                    marker.setAnimation(google.maps.Animation.BOUNCE);
                }
            });
        } else {
            alert("Некоторые отели не были отображениы по следующим причинам: " + status);
        }
    });

}