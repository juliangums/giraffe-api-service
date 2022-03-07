<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LocationController extends Controller
{

    /**
     *Check location data in imgae
     *
     * @param Request $request
     * @return string
     */
    public function pickImageData(Request $request)
    {
        $request->validate([
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg,raw,heif'],
        ]);

        $file = $request->file('image');

        //get location of image
        $imgLocation = $this->get_image_location($file);

        return new Response([
            'latitude' => $imgLocation['latitude'],
            'longitude' => $imgLocation['longitude'],
        ]);
    }

    /**
     *Check image lat lng
     *
     * @param string $image
     * @return float[]|int[]
     */
    public function get_image_location($image)
    {
        $exif = exif_read_data($image, 0, true);
        try {
            $GPSLatitudeRef = $exif['GPS']['GPSLatitudeRef'];
            $GPSLatitude = $exif['GPS']['GPSLatitude'];
            $GPSLongitudeRef = $exif['GPS']['GPSLongitudeRef'];
            $GPSLongitude = $exif['GPS']['GPSLongitude'];

            $lat_degrees = count($GPSLatitude) > 0 ? $this->gps2Num($GPSLatitude[0]) : 0;
            $lat_minutes = count($GPSLatitude) > 1 ? $this->gps2Num($GPSLatitude[1]) : 0;
            $lat_seconds = count($GPSLatitude) > 2 ? $this->gps2Num($GPSLatitude[2]) : 0;

            $lon_degrees = count($GPSLongitude) > 0 ? $this->gps2Num($GPSLongitude[0]) : 0;
            $lon_minutes = count($GPSLongitude) > 1 ? $this->gps2Num($GPSLongitude[1]) : 0;
            $lon_seconds = count($GPSLongitude) > 2 ? $this->gps2Num($GPSLongitude[2]) : 0;

            $lat_direction = ($GPSLatitudeRef == 'W' or $GPSLatitudeRef == 'S') ? -1 : 1;
            $lon_direction = ($GPSLongitudeRef == 'W' or $GPSLongitudeRef == 'S') ? -1 : 1;

            $latitude = $lat_direction * ($lat_degrees + ($lat_minutes / 60) + ($lat_seconds / (60 * 60)));
            $longitude = $lon_direction * ($lon_degrees + ($lon_minutes / 60) + ($lon_seconds / (60 * 60)));

            return array('latitude' => $latitude, 'longitude' => $longitude);
        } catch (Exception $e) {
            return array('latitude' => -12, 'longitude' => -12);
        }
    }

    /**
     *Convert GPS coord part in float val.
     *
     * @param $coordPart
     * @return float|int|string
     */
    public function gps2Num($coordPart): float|int|string
    {
        $parts = explode('/', $coordPart);
        if (count($parts) <= 0) {
            return 0;
        }
        if (count($parts) == 1) {
            return $parts[0];
        }
        return floatval($parts[0]) / floatval($parts[1]);
    }
}
