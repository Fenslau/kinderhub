<div id="map" style="width: 100%; height: 400px;"></div>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ env('YANDEX_MAP_KEY') }}" type="text/javascript"></script>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        ymaps.ready(init);
    });

    function init() {
        var latitudeInput = document.getElementById('data.profile.latitude');
        var longitudeInput = document.getElementById('data.profile.longitude');

        var latitude = parseFloat(latitudeInput.value);
        var longitude = parseFloat(longitudeInput.value);

        if (isNaN(latitude)) {
            latitude = 56.472160804211;
        }
        if (isNaN(longitude)) {
            longitude = 84.998472612998;
        }

        var map = new ymaps.Map("map", {
            center: [latitude, longitude],
            zoom: 11
        });

        var placemark = new ymaps.Placemark([latitude, longitude], {}, {
            draggable: true
        });
        map.geoObjects.add(placemark);

        map.events.add('click', function(e) {
            var coords = e.get('coords');
            placemark.geometry.setCoordinates(coords);

            latitudeInput.value = coords[0];
            longitudeInput.value = coords[1];
            latitudeInput.dispatchEvent(new Event('input'));
            longitudeInput.dispatchEvent(new Event('input'));
        });
    }
</script>