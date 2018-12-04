<?php
namespace Sfynx\CoreBundle\Generator\Application\Config;

/**
 * Class Config
 * @package Sfynx\CoreBundle\Generator\Application\Config
 *
 * @author Etienne de Longeaux <etienne.delongeaux@gmail.com>
 */
class Config
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

    /**
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param $key
     * @return null
     */
    public function get($key)
    {
        return $this->has($key) ? $this->data[$key] : null;
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * @inheritdoc
     */
    function jsonSerialize()
    {
        return $this->all();
    }

    /**
     * @param array $array
     * @return $this
     */
    public function fromArray(array $array)
    {
        foreach ($array as $key => $value) {
            $this->set($key, $value);
        }
        return $this;
    }
}
