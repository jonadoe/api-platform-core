<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiPlatform\Core\Bridge\Symfony\PropertyInfo\Metadata\Property;

use ApiPlatform\Core\Exception\PropertyNotFoundException;
use ApiPlatform\Core\Metadata\Property\Factory\PropertyMetadataFactoryInterface;
use ApiPlatform\Core\Metadata\Property\PropertyMetadata;
use Symfony\Component\PropertyInfo\PropertyInfoExtractorInterface;

/**
 * PropertyInfo metadata loader decorator.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
final class PropertyInfoPropertyMetadataFactory implements PropertyMetadataFactoryInterface
{
    private $propertyInfo;
    private $decorated;

    public function __construct(PropertyInfoExtractorInterface $propertyInfo, PropertyMetadataFactoryInterface $decorated = null)
    {
        $this->propertyInfo = $propertyInfo;
        $this->decorated = $decorated;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $resourceClass, string $name, array $options = []): PropertyMetadata
    {
        if (null === $this->decorated) {
            $propertyMetadata = new PropertyMetadata();
        } else {
            try {
                $propertyMetadata = $this->decorated->create($resourceClass, $name, $options);
            } catch (PropertyNotFoundException $propertyNotFoundException) {
                $propertyMetadata = new PropertyMetadata();
            }
        }

        if (null === $propertyMetadata->getType()) {
            $types = $this->propertyInfo->getTypes($resourceClass, $name, $options);
            if (isset($types[0])) {
                $propertyMetadata = $propertyMetadata->withType($types[0]);
            }
        }

        if (null === $propertyMetadata->getDescription() && null !== $description = $this->propertyInfo->getShortDescription($resourceClass, $name, $options)) {
            $propertyMetadata = $propertyMetadata->withDescription($description);
        }

        if (null === $propertyMetadata->isReadable() && null !== $readable = $this->propertyInfo->isReadable($resourceClass, $name, $options)) {
            $propertyMetadata = $propertyMetadata->withReadable($readable);
        }

        if (null === $propertyMetadata->isWritable() && null !== $writable = $this->propertyInfo->isWritable($resourceClass, $name, $options)) {
            $propertyMetadata = $propertyMetadata->withWritable($writable);
        }

        return $propertyMetadata;
    }
}
