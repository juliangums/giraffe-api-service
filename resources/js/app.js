require('./bootstrap');
import mapboxgl from 'mapbox-gl'
import bBox from '@turf/bbox'
import linestring from 'turf-linestring'
import bezierSpline from '@turf/bezier-spline'
import center from '@turf/center'
import points from '@turf/points-within-polygon'

mapboxgl.accessToken = process.env.MIX_MAPBOX_ACCESS_TOKEN
window.mapboxgl = mapboxgl
window.turf = {
	bBox: bBox,
	linestring: linestring,
	bezierSpline: bezierSpline,
	center: center,
	points: points,
}