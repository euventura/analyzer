<?php

namespace App\Casts;

use GeoIO\WKB\Parser\Parser;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class LatLong implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */

    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        $srid = substr($value, 0, 4);
        $srid = unpack('L', $srid)[1];
        $wkb = substr($value, 4);
        $parser = new Parser(new Factory());
        $result = $parser->parse($wkb);
        return[
            'lat' => $result->getLat(),
            'long' => $result->getLng()
        ];
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        dd(2);
        return $value;
    }
}

    class Factory implements \GeoIO\Factory
{
    public function createPoint($dimension, array $coordinates, $srid = null)
    {
        return new Point($coordinates['y'], $coordinates['x'], $srid);
    }

    public function createLineString($dimension, array $points, $srid = null)
    {
        return new LineString($points, $srid);
    }

    public function createLinearRing($dimension, array $points, $srid = null)
    {
        return new LineString($points, $srid);
    }

    public function createPolygon($dimension, array $lineStrings, $srid = null)
    {
        return new Polygon($lineStrings, $srid);
    }

    public function createMultiPoint($dimension, array $points, $srid = null)
    {
        return new MultiPoint($points, $srid);
    }

    public function createMultiLineString($dimension, array $lineStrings, $srid = null)
    {
        return new MultiLineString($lineStrings, $srid);
    }

    public function createMultiPolygon($dimension, array $polygons, $srid = null)
    {
        return new MultiPolygon($polygons, $srid);
    }

    public function createGeometryCollection($dimension, array $geometries, $srid = null)
    {
        return new GeometryCollection($geometries, $srid);
    }
}

class Point
{
    protected $lat;

    protected $lng;

    public function __construct($lat, $lng, $srid = 0)
    {
        $this->lat = (float) $lat;
        $this->lng = (float) $lng;
    }

    public function getLat()
    {
        return $this->lat;
    }

    public function setLat($lat)
    {
        $this->lat = (float) $lat;
    }

    public function getLng()
    {
        return $this->lng;
    }

    public function setLng($lng)
    {
        $this->lng = (float) $lng;
    }

    public function toPair()
    {
        return $this->getLng().' '.$this->getLat();
    }

    public static function fromPair($pair, $srid = 0)
    {
        list($lng, $lat) = explode(' ', trim($pair, "\t\n\r \x0B()"));

        return new static((float) $lat, (float) $lng, (int) $srid);
    }

    public function toWKT()
    {
        return sprintf('POINT(%s)', (string) $this);
    }

    public static function fromString($wktArgument, $srid = 0)
    {
        return static::fromPair($wktArgument, $srid);
    }

    public function __toString()
    {
        return $this->getLng().' '.$this->getLat();
    }

    /**
     * @param $geoJson  \GeoJson\Feature\Feature|string
     *
     * @return \Grimzy\LaravelMysqlSpatial\Types\Point
     */
    

}