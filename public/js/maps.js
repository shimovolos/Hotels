 function initialize(latitude,longitude)
 {
    var latlng = new google.maps.LatLng(latitude,longitude);
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
    var marker = new google.maps.Marker({
        position: latlng,
        map: map
    });

 }
