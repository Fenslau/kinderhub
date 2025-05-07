<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&apikey={{ env('YANDEX_MAP_KEY') }}" type="text/javascript"></script>
<div id="map" style="width: auto; height: 400px;"></div>

<script type="text/javascript">
    ymaps.ready(init);
    function init() {
        var latitude = {{ $latitude }};
        var longitude = {{ $longitude }};

        var map = new ymaps.Map("map", {
            center: [latitude, longitude],
            zoom: 15,
            controls: ['zoomControl', 'fullscreenControl']
        });

        var placemark = new ymaps.Placemark([latitude, longitude], {
            preset: 'islands#blueDotIcon'
        });

        map.geoObjects.add(placemark);
        placemark.options.set('draggable', false);
    }
</script>