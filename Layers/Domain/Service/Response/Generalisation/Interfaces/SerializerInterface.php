<?php
namespace Sfynx\CoreBundle\Layers\Domain\Service\Response\Generalisation\Interfaces;

interface SerializerInterface
{
    /**
     * @param $data
     * @param $format
     * @param null $context
     * @return mixed
     */
    public function serialize($data, $format, $context = null);

    /**
     * @param $data
     * @param $type
     * @param $format
     * @param null $context
     * @return mixed
     */
    public function deserialize($data, $type, $format, $context = null);

    /**
     * @return mixed
     */
    public function getSerializationContext();
}
