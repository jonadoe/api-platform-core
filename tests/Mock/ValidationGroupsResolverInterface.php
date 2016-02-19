<?php

/*
 * This file is part of the API Platform Builder package.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ApiPlatform\Builder\Tests\Mock;

use Symfony\Component\Validator\Constraints\GroupSequence;

/**
 * @author Teoh Han Hui <teohhanhui@gmail.com>
 */
interface ValidationGroupsResolverInterface
{
    /**
     * Gets validation groups for the provided data.
     *
     * @param mixed $data
     *
     * @return string[]|GroupSequence
     */
    public function getValidationGroups($data);
}