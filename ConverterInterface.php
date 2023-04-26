<?php
/*
 * This file is part of the Sidus/ConverterBundle package.
 *
 * Copyright (c) 2021-2023 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sidus\ConverterBundle;

use Sidus\ConverterBundle\Event\EventInterface;
use Sidus\ConverterBundle\Model\ConverterConfiguration;
use Sidus\ConverterBundle\Model\Mapping\Mapping;

/**
 * Convert data from a format to another format.
 */
interface ConverterInterface
{
    public function convert(
        mixed $input,
        ConverterConfiguration | string $configuration,
        mixed $output = null,
    ): mixed;

    public function convertWithParent(
        EventInterface $parentEvent,
        Mapping $parentMapping,
        mixed $input,
        ConverterConfiguration | string $configuration,
        mixed $output = null,
    ): mixed;
}
