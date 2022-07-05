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
 * Classes for dealing with Exif entries.
 *
 * This file defines two exception classes and the abstract class
 * {@link PelEntry} which provides the basic methods that all Exif
 * entries will have. All Exif entries will be represented by
 * descendants of the {@link PelEntry} class --- the class itself is
 * abstract and so it cannot be instantiated.
 *
 * @author Martin Geisler <mgeisler@users.sourceforge.net>
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public
 *          License (GPL)
 * @package PEL
 */

/**
 * Common ancestor class of all {@link PelIfd} entries.
 *
 * As this class is abstract you cannot instantiate objects from it.
 * It only serves as a common ancestor to define the methods common to
 * all entries. The most important methods are {@link getValue()} and
 * {@link setValue()}, both of which is abstract in this class. The
 * descendants will give concrete implementations for them.
 *
 * If you have some data coming from an image (some raw bytes), then
 * the static method {@link newFromData()} is helpful --- it will look
 * at the data and give you a proper decendent of {@link PelEntry}
 * back.
 *
 * If you instead want to have an entry for some data which take the
 * form of an integer, a string, a byte, or some other PHP type, then
 * don't use this class. You should instead create an object of the
 * right subclass ({@link PelEntryShort} for short integers, {@link
 * PelEntryAscii} for strings, and so on) directly.
 *
 * @author Martin Geisler <mgeisler@users.sourceforge.net>
 * @package PEL
 */
namespace lsolesen\pel;

abstract class PelEntry implements PelEntryInterface
{

    /**
     * Type of IFD containing this tag.
     *
     * This must be one of the constants defined in {@link PelIfd}:
     * {@link PelIfd::IFD0} for the main image IFD, {@link PelIfd::IFD1}
     * for the thumbnail image IFD, {@link PelIfd::EXIF} for the Exif
     * sub-IFD, {@link PelIfd::GPS} for the GPS sub-IFD, or {@link
     * PelIfd::INTEROPERABILITY} for the interoperability sub-IFD.
     *
     * @var int
     */
    protected int $ifd_type = -1;

    /**
     * The bytes representing this entry.
     *
     * Subclasses must either override {@link getBytes()} or, if
     * possible, maintain this property so that it always contains a
     * true representation of the entry.
     *
     * @var string
     */
    protected string $bytes = '';

    /**
     * The {@link PelTag} of this entry.
     *
     * @var int
     */
    protected int $tag;

    /**
     * The {@link PelFormat} of this entry.
     *
     * @var int
     */
    protected int $format;

    /**
     * The number of components of this entry.
     *
     * @var int
     */
    protected int $components;

    /**
     * @inheritDoc
     */
    public function getTag():int
    {
        return $this->tag;
    }

    /**
     * @inheritDoc
     */
    public function getIfdType():int
    {
        return $this->ifd_type;
    }

    /**
     * @inheritDoc
     */
    public function setIfdType(int $type):void
    {
        $this->ifd_type = $type;
    }

    /**
     * @inheritDoc
     */
    public function getFormat():int
    {
        return $this->format;
    }

    /**
     * @inheritDoc
     */
    public function getComponents():int
    {
        return $this->components;
    }

    /**
     * @inheritDoc
     */
    public function getBytes(bool $o):string
    {
        return $this->bytes;
    }

    /**
     * @inheritDoc
     */
    public function __toString():string
    {
        $str = Pel::fmt("  Tag: 0x%04X (%s)\n", $this->tag, PelTag::getName($this->ifd_type, $this->tag));
        $str .= Pel::fmt("    Format    : %d (%s)\n", $this->format, PelFormat::getName($this->format));
        $str .= Pel::fmt("    Components: %d\n", $this->components);
        if ($this->getTag() != PelTag::MAKER_NOTE && $this->getTag() != PelTag::PRINT_IM) {
            $str .= Pel::fmt("    Value     : %s\n", print_r($this->getValue(), true));
        }
        $str .= Pel::fmt("    Text      : %s\n", $this->getText());
        return $str;
    }
}
