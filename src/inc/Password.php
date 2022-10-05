<?php
/**
 * @author    : JIHAD SINNAOUR
 * @package   : VanillePlugin
 * @version   : 0.7.9
 * @copyright : (c) 2018 - 2022 JIHAD SINNAOUR <mail@jihadsinnaour.com>
 * @link      : https://jakiboy.github.io/VanillePlugin/
 * @license   : MIT
 *
 * This file if a part of VanillePlugin Framework.
 */

namespace VanillePlugin\inc;

final class Password extends Tokenizer
{
    /**
     * @access public
     * @param string $password
     * @param string $hash
     * @return bool
     */
    public static function isValid($password, $hash)
    {
        return password_verify($password,$hash);
    }

    /**
     * @access public
     * @param string $password
     * @param string $algo
     * @param string $options
     * @return mixed
     */
    public static function hash($password, $algo = PASSWORD_BCRYPT, $options = [])
    {
        return password_hash($password,$algo,$options);
    }

    /**
     * @access public
     * @param string $password
     * @param int $length
     * @return bool
     */
    public static function isStrong($password = '', $length = 8)
    {
        $uppercase = Stringify::match('@[A-Z]@',$password);
        $lowercase = Stringify::match('@[a-z]@',$password);
        $number    = Stringify::match('@[0-9]@',$password);
        $special   = Stringify::match('@[^\w]@',$password);

        if ( !$uppercase 
            || !$lowercase 
            || !$number 
            || !$special 
            || strlen($password) < $length 
        ) {
            return false;
        }
        return true;
    }
}
