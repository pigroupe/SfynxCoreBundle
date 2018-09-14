<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Response\Serializer;

use Sfynx\CoreBundle\Layers\Domain\Service\Response\Generalisation\Interfaces\SerializerInterface;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\SerializationContext;

class SerializerStrategy implements SerializerInterface
{
    /** @var */
    protected $serializer;
    /** @var */
    protected $serializationContext;

    /**
     * SerializerStrategy constructor.
     * @param $serializer
     */
    public function __construct($serializer)
    {
        $this->serializer = $serializer;
        $this->setSerializationContext();
    }

    /**
     * @return static
     */
    public static function create()
    {
        return new static(SerializerBuilder::create()->build());
    }

    /**
     * @param null $context
     */
    protected function setSerializationContext($context = null)
    {
        $this->serializationContext = $context;
        if (null === $context) {
            $this->serializationContext = SerializationContext::create();//let null ?
        }
    }

    /**
     * @param $data
     * @param $format
     * @param null $context
     * @return mixed
     */
    public function serialize($data, $format, $context = null)
    {
        if(null === $this->serializationContext) {
            return $this->serializer->serialize($data, $format, $context);
        }
        return $this->serializer->serialize($data, $format, $this->serializationContext);
    }

    /**
     * @param $data
     * @param $type
     * @param $format
     * @param null $context
     * @return mixed
     */
    public function deserialize($data, $type, $format, $context = null)
    {
        return $this->serializer->deserialize($data, $format, $context);
    }

    /**
     * @return mixed
     */
    public function getSerializationContext()
    {
        return $this->serializationContext;
    }
}
