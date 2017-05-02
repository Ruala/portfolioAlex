


$(document).ready(function(){
	ymaps.ready(_our_map);

	function _our_map() {
		var map2 = new ymaps.Map("map", {
            center: [55.838091, 37.417167],
            zoom: 16,
            type: "yandex#map"
        });
        map2.controls
                .add("zoomControl");

        var myIcon = ymaps.templateLayoutFactory.createClass(
                '<div class="map-marker"></div>'
        );

        map2.geoObjects
                .add(new ymaps.Placemark([55.838091, 37.417167], {
                    balloonContent: "Россия, Москва, ул. Походный проезд, дом 14",
                    hideIcon : false
                }, {
                    iconLayout: myIcon
                }));

	}
});