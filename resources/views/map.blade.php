<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Encounter | </title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script src="{{ mix('js/app.js') }}" defer></script>
</head>
<body>
<div class="relative">
    <div id="map" class="w-screen h-screen"></div>
</div>

<script>
	let coords = {!! $coords !!}, mapbox = window.mapboxgl
	let checkMapBox = setInterval(() => {
		mapbox = window.mapboxgl
		if (mapbox) {
			clearInterval(checkMapBox)

            let options = {
	            container: 'map',
	            style: 'mapbox://styles/mapbox/satellite-streets-v11',
	            zoom: 9
            }
			const lineString = turf.linestring(coords)
			const center = turf.center(lineString)
            options.center = center.geometry.coordinates

			const map = new mapboxgl.Map(options);
			map.on('load', () => {
				if (coords.length > 1) {
					const bBounds = turf.bBox(lineString)
					const curve = turf.bezierSpline(lineString)

					map.fitBounds([
						[bBounds[0], bBounds[1]],
						[bBounds[2], bBounds[3]],
					], {padding: 60});
					map.addSource('route', {
						type: 'geojson',
						data: curve
					})
					map.addLayer({
						'id': 'route',
						'type': 'line',
						'source': 'route',
						'layout': {
							'line-join': 'round',
							'line-cap': 'round'
						},
						'paint': {
							'line-color': '#ff844c',
							'line-width': 3
						}
					});
					map.markers({
                    backgroundImage: url('/images/marker.png'),
                    width: 50,
                    height: 50,
                    borderRadius: 50,
                    })
				}
			})
	            new mapboxgl.Marker({
                    color: "#ff844c"
                }).setLngLat(coords[coords.length -1]).addTo(map);
		}
	}, 100)
</script>
</body>
</html>