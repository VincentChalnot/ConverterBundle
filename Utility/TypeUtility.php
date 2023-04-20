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

namespace Sidus\ConverterBundle\Utility;

/**
 * Helper class to handle some edge cases with types.
 */
class TypeUtility
{
    protected const CORE_TYPES = [
        'self',
        'parent',
        'array',
        'callable',
        'bool',
        'float',
        'int',
        'string',
        'iterable',
        'object',
        'mixed',
        'null',
        'void',
        'static',
    ];

    public static function normalizeType(?string $type): ?string
    {
        if (null === $type) {
            return null;
        }

        if (self::isNativeType($type)) {
            return $type;
        }

        if (str_contains($type, '\\')) {
            return ltrim($type, '\\');
        }

        if (class_exists($type) || interface_exists($type)) {
            return $type;
        }

        throw new \UnexpectedValueException("Unknown type {$type}");
    }

    public static function isNativeType(string $type): bool
    {
        return in_array($type, self::CORE_TYPES);
    }
}
