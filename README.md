lib-google-maps
========================

Google Maps API library for Yii2

[![Latest Stable Version](https://poser.pugx.org/panix/lib-google-maps/v/stable.svg)](https://packagist.org/packages/panix/lib-google-maps) [![Total Downloads](https://poser.pugx.org/panix/lib-google-maps/downloads.svg)](https://packagist.org/packages/panix/lib-google-maps) [![Latest Unstable Version](https://poser.pugx.org/panix/lib-google-maps/v/unstable.svg)](https://packagist.org/packages/panix/lib-google-maps) [![License](https://poser.pugx.org/panix/lib-google-maps/license.svg)](https://packagist.org/packages/panix/lib-google-maps)

Introduction
------------
Even though we already created an extension to display maps that are away from Google's policies and works with
[LeafLetJs](http://leafletjs.com/ "http://leafletjs.com/") library, we still received requests to have
[EGMap extension for Yii1](http://www.yiiframework.com/extension/egmap) updated. So we thought that we should update
this library and make it work with Yii2 if we were to update it. Thats the reason behind the creation of this extension.

Nevertheless, it is important to note that we didn't have time (**yet**) to write any good documentation about it.
We wanted to publish it already, just in case somebody working with Yii2 was missing the EGMap library for its projects,
and wishes to update us with its tests and bug findings.

The github repository will keep being updated, and documentation well written for its usage. So please, do not be
impatient. If you do, any help will be highly appreciated.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require "panix/lib-google-maps" "*"
```
or add

```json
"panix/lib-google-maps" : "*"
```

to the require section of your application's `composer.json` file.

## Usage

Even though there will be plenty of examples on how to use it, here is one that will provide you with a glimpse of its
usage:

## Service
```php
use panix\lib\google\maps\LatLng;
use panix\lib\google\maps\services\DirectionsWayPoint;
use panix\lib\google\maps\services\TravelMode;
use panix\lib\google\maps\overlays\PolylineOptions;
use panix\lib\google\maps\services\DirectionsRenderer;
use panix\lib\google\maps\services\DirectionsService;
use panix\lib\google\maps\overlays\InfoWindow;
use panix\lib\google\maps\overlays\Marker;
use panix\lib\google\maps\Map;
use panix\lib\google\maps\services\DirectionsRequest;
use panix\lib\google\maps\overlays\Polygon;
use panix\lib\google\maps\layers\BicyclingLayer;

$coord = new LatLng(['lat' => 39.720089311812094, 'lng' => 2.91165944519042]);
$map = new Map([
    'center' => $coord,
    'zoom' => 14,
]);

// lets use the directions renderer
$home = new LatLng(['lat' => 39.720991014764536, 'lng' => 2.911801719665541]);
$school = new LatLng(['lat' => 39.719456079114956, 'lng' => 2.8979293346405166]);
$santo_domingo = new LatLng(['lat' => 39.72118906848983, 'lng' => 2.907628202438368]);

// setup just one waypoint (Google allows a max of 8)
$waypoints = [
    new DirectionsWayPoint(['location' => $santo_domingo])
];

$directionsRequest = new DirectionsRequest([
    'origin' => $home,
    'destination' => $school,
    'waypoints' => $waypoints,
    'travelMode' => TravelMode::DRIVING
]);

// Lets configure the polyline that renders the direction
$polylineOptions = new PolylineOptions([
    'strokeColor' => '#FFAA00',
    'draggable' => true
]);

// Now the renderer
$directionsRenderer = new DirectionsRenderer([
    'map' => $map->getName(),
    'polylineOptions' => $polylineOptions
]);

// Finally the directions service
$directionsService = new DirectionsService([
    'directionsRenderer' => $directionsRenderer,
    'directionsRequest' => $directionsRequest
]);

// Thats it, append the resulting script to the map
$map->appendScript($directionsService->getJs());

// Lets add a marker now
$marker = new Marker([
    'position' => $coord,
    'title' => 'My Home Town',
]);

// Provide a shared InfoWindow to the marker
$marker->attachInfoWindow(
    new InfoWindow([
        'content' => '<p>This is my super cool content</p>'
    ])
);

// Add marker to the map
$map->addOverlay($marker);

// Now lets write a polygon
$coords = [
    new LatLng(['lat' => 25.774252, 'lng' => -80.190262]),
    new LatLng(['lat' => 18.466465, 'lng' => -66.118292]),
    new LatLng(['lat' => 32.321384, 'lng' => -64.75737]),
    new LatLng(['lat' => 25.774252, 'lng' => -80.190262])
];

$polygon = new Polygon([
    'paths' => $coords
]);

// Add a shared info window
$polygon->attachInfoWindow(new InfoWindow([
        'content' => '<p>This is my super cool Polygon</p>'
    ]));

// Add it now to the map
$map->addOverlay($polygon);


// Lets show the BicyclingLayer :)
$bikeLayer = new BicyclingLayer(['map' => $map->getName()]);

// Append its resulting script
$map->appendScript($bikeLayer->getJs());

// Display the map -finally :)
echo $map->display();
```

## Client
```php
use panix\lib\google\maps\services\DirectionsClient;

$direction = new DirectionsClient([
    'params' => [
        'language' => Yii::$app->language,
        'origin' => 'street from',
        'destination' => 'street to'
    ]
]);

$data = $direction->lookup(); //get data from google.maps API
```

This extension has also a plugin architecture that allow us to enhance it, so expect plugins to be developed in near
future too.

## Configuration

To configure the Google Map key or other options like language, version, library, use the [Asset Bundle customization](http://www.yiiframework.com/doc-2.0/guide-structure-assets.html#customizing-asset-bundles) feature.

```php
'components' => [
    'assetManager' => [
        'bundles' => [
            'panix\lib\google\maps\MapAsset' => [
                'options' => [
                    'key' => 'this_is_my_key',
                    'language' => 'id',
                    'version' => '3.1.18'
                ]
            ]
        ]
    ],
],
```

To get key, please visit https://code.google.com/apis/console/

## Resources

 * [Google Maps API Reference](https://developers.google.com/maps/documentation/)
 * [GitHub](https://github.com/andrtechno/lib-google-maps)
 * [Panix Packagist Profile](https://packagist.org/packages/panix/)

