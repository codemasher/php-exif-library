<?php
/**
 * Interface PelEntryNumberInterface
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
interface PelEntryNumberInterface extends PelEntryInterface{

	/**
	 * Change the value.
	 *
	 * This method can change both the number of components and the
	 * value of the components. Range checks will be made on the new
	 * value, and a {@link PelOverflowException} will be thrown if the
	 * value is found to be outside the legal range.
	 *
	 * @param array $values
	 *            the new values. The array must contain the new
	 *            numbers.
	 * @see PelEntryNumber::getValue
	 */
	public function setValueArray(array $values):void;

	/**
	 * Convert a number into bytes.
	 *
	 * The concrete subclasses will have to implement this method so
	 * that the numbers represented can be turned into bytes.
	 *
	 * The method will be called once for each number held by the entry.
	 *
	 * @param int $number
	 *            the number that should be converted.
	 * @param boolean $order
	 *            one of {@link PelConvert::LITTLE_ENDIAN} and
	 *            {@link PelConvert::BIG_ENDIAN}, specifying the target byte order.
	 * @return string bytes representing the number given.
	 */
	public function numberToBytes(int $number, bool $order):string;

	/**
	 * Validate a number.
	 *
	 * This method will check that the number given is within the range
	 * given my {@link getMin()} and {@link getMax()}, inclusive. If
	 * not, then a {@link PelOverflowException} is thrown.
	 *
	 * @param int|array $n
	 *            the number in question.
	 * @return void nothing, but will throw a {@link
	 *         PelOverflowException} if the number is found to be outside the
	 *         legal range and {@link Pel::$strict} is true.
	 */
	public function validateNumber($n):void;

	/**
	 * Add a number.
	 *
	 * This appends a number to the numbers already held by this entry,
	 * thereby increasing the number of components by one.
	 *
	 * @param int|array $n
	 *            the number to be added.
	 */
	public function addNumber($n):void;

	/**
	 * Format a number.
	 *
	 * This method is called by {@link getText} to format numbers.
	 * Subclasses should override this method if they need more
	 * sophisticated behavior than the default, which is to just return
	 * the number as is.
	 *
	 * @param mixed|int $number
	 *            the number which will be formatted.
	 * @param boolean $brief
	 *            it could be that there is both a verbose and a
	 *            brief formatting available, and this argument controls that.
	 * @return string the number formatted as a string suitable for
	 *         display.
	 */
	public function formatNumber($number, bool $brief = false):string;

	/**
	 * Get the numeric value of this entry as text.
	 *
	 * @param boolean $brief
	 *            use brief output? The numbers will be separated
	 *            by a single space if brief output is requested, otherwise a space
	 *            and a comma will be used.
	 * @return string the numbers(s) held by this entry.
	 */
	public function getText(bool $brief = false):string;


}
