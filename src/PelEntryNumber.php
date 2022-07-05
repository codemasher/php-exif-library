<?php

/**
 * PEL: PHP Exif Library.
 * A library with support for reading and
 * writing all Exif headers in JPEG and TIFF images using PHP.
 *
 * Copyright (C) 2004, 2005, 2006 Martin Geisler.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program in the file COPYING; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA 02110-1301 USA
 */

/**
 * Abstract class for numbers.
 *
 * @author Martin Geisler <mgeisler@users.sourceforge.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public
 *          License (GPL)
 * @package PEL
 */

/**
 * Class for holding numbers.
 *
 * This class can hold numbers, with range checks.
 *
 * @author Martin Geisler <mgeisler@users.sourceforge.net>
 * @package PEL
 */
namespace lsolesen\pel;

abstract class PelEntryNumber extends PelEntry implements PelEntryNumberInterface
{

    /**
     * The value held by this entry.
     */
    protected array $value = [];

    /**
     * The minimum allowed value.
     *
     * Any attempt to change the value below this variable will result
     * in a {@link PelOverflowException} being thrown.
     */
    protected int $min;

    /**
     * The maximum allowed value.
     *
     * Any attempt to change the value over this variable will result in
     * a {@link PelOverflowException} being thrown.
     */
    protected int $max;

    /**
     * The dimension of the number held.
     *
     * Normal numbers have a dimension of one, pairs have a dimension of
     * two, etc.
     */
    protected int $dimension = 1;

    /**
     * Change the value.
     *
     * This method can change both the number of components and the
     * value of the components. Range checks will be made on the new
     * value, and a {@link PelOverflowException} will be thrown if the
     * value is found to be outside the legal range.
     *
     * The method accept several number arguments. The {@link getValue}
     * method will always return an array except for when a single
     * number is given here.
     *
     * @param $value
     *            the new value(s). This can be zero or
     *            more numbers, that is, either integers or arrays. The input will
     *            be checked to ensure that the numbers are within the valid range.
     *            If not, then a {@link PelOverflowException} will be thrown.
     * @see PelEntryNumber::getValue
     */
    public function setValue($value):void
    {
        $this->setValueArray(func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function setValueArray(array $values):void
    {
        foreach ($values as $v) {
            $this->validateNumber($v);
        }

        $this->components = count($values);
        $this->value = $values;
    }

    /**
     * Return the numeric value held.
     *
     * @return int|array this will either be a single number if there is
     *         only one component, or an array of numbers otherwise.
     */
    public function getValue()
    {
        if ($this->components == 1) {
            return $this->value[0];
        } else {
            return $this->value;
        }
    }

    /**
     * @inheritDoc
     */
    public function validateNumber($n):void
    {
        if ($this->dimension == 1 || is_scalar($n)) {
            if ($n < $this->min || $n > $this->max) {
                Pel::maybeThrow(new PelOverflowException((int) $n, $this->min, $this->max));
            }
        } else {
            for ($i = 0; $i < $this->dimension; $i ++) {
                if (! isset($n[$i])) {
                    continue;
                }
                if ($n[$i] < $this->min || $n[$i] > $this->max) {
                    Pel::maybeThrow(new PelOverflowException($n[$i], $this->min, $this->max));
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function addNumber($n):void
    {
        $this->validateNumber($n);
        $this->value[] = $n;
        $this->components ++;
    }

    /**
     * Turn this entry into bytes.
     *
     * @param boolean $o
     *            the desired byte order, which must be either
     *            {@link PelConvert::LITTLE_ENDIAN} or {@link
     *            PelConvert::BIG_ENDIAN}.
     * @return string bytes representing this entry.
     */
    public function getBytes(bool $o):string
    {
        $bytes = '';
        for ($i = 0; $i < $this->components; $i ++) {
            if ($this->dimension == 1) {
                $bytes .= $this->numberToBytes($this->value[$i], $o);
            } else {
                for ($j = 0; $j < $this->dimension; $j ++) {
                    $bytes .= $this->numberToBytes($this->value[$i][$j], $o);
                }
            }
        }
        return $bytes;
    }

    /**
     * @inheritDoc
     */
    public function formatNumber($number, bool $brief = false):string
    {
        return (string)$number;
    }

    /**
     * @inheritDoc
     */
    public function getText(bool $brief = false):string
    {
        if ($this->components == 0) {
            return '';
        }

        $str = $this->formatNumber($this->value[0]);
        for ($i = 1; $i < $this->components; $i ++) {
            $str .= ($brief ? ' ' : ', ');
            $str .= $this->formatNumber($this->value[$i]);
        }

        return $str;
    }
}
