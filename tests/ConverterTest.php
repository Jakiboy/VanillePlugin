<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.9.6
 * @copyright : (c) 2018 - 2023 Jihad Sinnaour <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

use VanillePlugin\inc\Converter;
use PHPUnit\Framework\TestCase;

final class ConverterTest extends TestCase
{
    public function testToObject()
    {
        $this->assertTrue(is_object(
            Converter::toObject(['test' => '12345'])
        ));
    }

    public function testToArray()
    {
        $object = new \stdClass();
        $object->test = '12345';
        $this->assertTrue(is_array(
            Converter::toArray($object)
        ));
    }

    public function testToMoney()
    {
        $this->assertSame(Converter::toMoney('15'),'15.00');
    }
}
