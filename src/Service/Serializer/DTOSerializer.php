<?php

namespace App\Service\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class DTOSerializer implements SerializerInterface
{

    private SerializerInterface $serializer;

    public function __construct(){
        $this->serializer = new Serializer(
            // normalizers
        [new ObjectNormalizer(nameConverter: new CamelCaseToSnakeCaseNameConverter())],
            // encoders
        [new JsonEncoder()]

        );
    }

    public function serialize(mixed $data, string $format, array $context = []): string
    {
        // TODO: Implement serialize() method.
        return $this->serializer->serialize($data, $format, $context);
    }

    public function deserialize(mixed $data, string $type, string $format, array $context = []): mixed
    {
        // TODO: Implement deserialize() method.
        return $this->serializer->deserialize($data, $type, $format, $context);
    }
}