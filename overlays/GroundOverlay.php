<?php
/**
 * @copyright Copyright (c) 2014 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace panix\lib\google\maps\overlays;


use panix\lib\google\maps\OverlayTrait;
use panix\lib\google\maps\LatLngBounds;
use yii\base\InvalidConfigException;

/**
 * GroundOverlay
 *
 * Object to render rectangular image overlay on the map.
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package panix\lib\google\maps
 */
class GroundOverlay extends CircleOptions
{
    /**
     * @var string the image URL
     */
    public $url;
    /**
     * @var LatLngBounds
     */
    private $_bounds;

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function init()
    {

        if ($this->url == null || $this->getBounds() == null) {
            throw new InvalidConfigException('"url" and/or "bounds" cannot be null');
        }
    }


    /**
     * Sets the [OverlayGround] bounds.
     *
     * @param LatLngBounds $value
     */
    public function setBounds(LatLngBounds $value)
    {
        $this->_bounds = $value;
    }

    /**
     * Returns the [OverlayGround] bounds.
     *
     * @return LatLngBounds
     */
    public function getBounds()
    {
        return $this->_bounds;
    }


    /**
     * Returns the js code to create a rectangle on a map
     * @return string
     */
    public function getJs()
    {
        $bounds = $this->getBounds()->getJs();
        $options = $this->getEncodedOptions();
        $js = [];
        $js[] = "var {$this->getName()} = new google.maps.GroundOverlay('{$this->url}',{$bounds}, {$options});";

        foreach ($this->events as $event) {
            /** @var \panix\lib\google\maps\Event $event */
            $js[] = $event->getJs($this->getName());
        }


        return implode("\n", $js);
    }
} 