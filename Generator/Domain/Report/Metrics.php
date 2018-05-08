<?php
namespace Sfynx\CoreBundle\Generator\Domain\Report;

/**
 * Class Metrics
 * @category   Sfynx\CoreBundle\Generator
 * @package    Domain
 * @subpackage Report
 */
class Metrics implements \JsonSerializable
{

    /**
     * @var array
     */
    private $data = [];

    /**
     * @param $metric
     * @return $this
     */
    public function attach($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
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
     * @param $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @return Metric[]
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
}