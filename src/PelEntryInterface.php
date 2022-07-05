<?php
/**
 * Interface PelEntryInterface
 *
 * @created      05.07.2022
 * @author       smiley <smiley@chillerlan.net>
 * @copyright    2022 smiley
 * @license      MIT
 */

namespace lsolesen\pel;

/**
 *
 */
interface PelEntryInterface{

	/**
	 * Return the tag of this entry.
	 *
	 * @return int the tag of this entry.
	 */
	public function getTag():int;

	/**
	 * Return the type of IFD which holds this entry.
	 *
	 * @return int one of the constants defined in {@link PelIfd}:
	 *         {@link PelIfd::IFD0} for the main image IFD, {@link PelIfd::IFD1}
	 *         for the thumbnail image IFD, {@link PelIfd::EXIF} for the Exif
	 *         sub-IFD, {@link PelIfd::GPS} for the GPS sub-IFD, or {@link
	 *         PelIfd::INTEROPERABILITY} for the interoperability sub-IFD.
	 */
	public function getIfdType():int;

	/**
	 * Update the IFD type.
	 *
	 * @param int $type
	 *            must be one of the constants defined in {@link
	 *            PelIfd}: {@link PelIfd::IFD0} for the main image IFD, {@link
	 *            PelIfd::IFD1} for the thumbnail image IFD, {@link PelIfd::EXIF}
	 *            for the Exif sub-IFD, {@link PelIfd::GPS} for the GPS sub-IFD, or
	 *            {@link PelIfd::INTEROPERABILITY} for the interoperability
	 *            sub-IFD.
	 */
	public function setIfdType(int $type):void;

	/**
	 * Return the format of this entry.
	 *
	 * @return int the format of this entry.
	 */
	public function getFormat():int;

	/**
	 * Return the number of components of this entry.
	 *
	 * @return int the number of components of this entry.
	 */
	public function getComponents():int;

	/**
	 * Turn this entry into bytes.
	 *
	 * @param boolean $o
	 *            the desired byte order, which must be either
	 *            {@link Convert::LITTLE_ENDIAN} or {@link Convert::BIG_ENDIAN}.
	 * @return string bytes representing this entry.
	 */
	public function getBytes(bool $o):string;

	/**
	 * Get the value of this entry as text.
	 *
	 * The value will be returned in a format suitable for presentation,
	 * e.g., rationals will be returned as 'x/y', ASCII strings will be
	 * returned as themselves etc.
	 *
	 * @param boolean $brief
	 *            some values can be returned in a long or more
	 *            brief form, and this parameter controls that.
	 * @return string the value as text.
	 */
	public function getText(bool $brief = false):string;

	/**
	 * Get the value of this entry.
	 *
	 * The value returned will generally be the same as the one supplied
	 * to the constructor or with {@link setValue()}. For a formatted
	 * version of the value, one should use {@link getText()} instead.
	 *
	 * @return mixed the unformatted value.
	 */
	public function getValue();

	/**
	 * Set the value of this entry.
	 *
	 * The value should be in the same format as for the constructor.
	 *
	 * @param mixed $value the new value.
	 */
	public function setValue($value):void;

	/**
	 * Turn this entry into a string.
	 *
	 * @return string a string representation of this entry. This is
	 *         mostly for debugging.
	 */
	public function __toString():string;
}
