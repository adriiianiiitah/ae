    jQuery(document).ready(function ($) {
    'use strict';
    // GOOGLE MAPS START
    window.marker = null;

    function initialize() {
        var map;

        var nottingham = new google.maps.LatLng(35.3882631, -119.0548651);

        var style = [
            {"featureType": "road",
                "elementType":
                    "labels.icon",
                "stylers": [
                    {"saturation": 1},
                    {"gamma": 1},
                    {"visibility": "on"},
                    {"hue": "#e6ff00"}
                ]
            },
            {"elementType": "geometry", "stylers": [
                {"saturation": -85}
            ]
            }
        ];

        var mapOptions = {
            // SET THE CENTER
            center: nottingham,
            // SET THE MAP STYLE & ZOOM LEVEL
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            zoom: 15,
            // SET THE BACKGROUND COLOUR
            backgroundColor: "#eeeeee",
            // REMOVE ALL THE CONTROLS EXCEPT ZOOM
            panControl: true,
            zoomControl: true,
            mapTypeControl: true,
            scaleControl: true,
            streetViewControl: true,
            overviewMapControl: true,
            scrollwheel: false,
            zoomControlOptions: {
                style: google.maps.ZoomControlStyle.SMALL
            }

        };
        map = new google.maps.Map(document.getElementById('map'), mapOptions);

        // SET THE MAP TYPE
        var mapType = new google.maps.StyledMapType(style, {name: "Grayscale"});
        map.mapTypes.set('grey', mapType);
        map.setMapTypeId('grey');

        //CREATE A CUSTOM PIN ICON
        var marker_image = 'img/marker.png';
        var pinIcon = new google.maps.MarkerImage(marker_image, null, null, null, new google.maps.Size(21, 34));

         var marker = new google.maps.Marker({
            position: nottingham,
            map: map,
            icon: pinIcon,
            title: 'Trucking Headquarters, Nothingham'
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);

    });