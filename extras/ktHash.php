<?php

/**
 * LICENSE
 *
 * Copyright (c) 2011, Luk� Unger
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 * Redistributions of source code must retain the above copyright notice, this
 * list of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice,
 * this list of conditions and the following disclaimer in the documentation
 * and/or other materials provided with the distribution.
 * Neither the name of the ark8, s.r.o. nor the names of its contributors may be
 * used to endorse or promote products derived from this software without
 * specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF
 * ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */

/**
 * Generates or verifies a keyed, random-salted message digests using the HMAC method.
 *
 * @category	kt
 * @package	core.kaytee
 * @author	Luk� Unger <looky.msc@gmail.com>
 * @copyright	Copyright (c) 2011, Luk� Unger
 * @license	http://www.opensource.org/licenses/BSD-3-Clause Modified BSD License
 * @version	1.0.0
 */
class ktHash {

  /**
   * There is probably no mathematical reason for this particular choice of constants. Override this to produce different hashes. These values don't even have to be used if you override the getIndex() method.
   *
   * @var array   First sixteen kt primes. n is a kt prime if it is a prime, and p(n) mod n is also a prime, where p(n) is the n-th prime.
   */
  protected static $primes = array(3, 7, 13, 31, 43, 47, 53, 59, 73, 191, 193, 197, 199, 211, 223, 227);

  /**
   * hash_hmac("sha1", "", "")
   *
   * @var string  Hash of the original message.
   */
  private static $hdata = "fbdb1d1b18aa6c08324b7d64b71fb76370690e1d";

  /**
   * @var int	    Length of the original message.
   */
  private static $dlen = 0;

  /**
   * This class cannot be instantiated.
   */
  final private function __construct() {}

  /**
   * Objects of this class cannot be cloned.
   */
  final private function __clone() {}

  /**
   * This class cannot be deserialized.
   */
  final private function __wakeup() {}

  /**
   * Generates or verifies a keyed, random-salted message digest using the HMAC method.
   *
   * @param	string	$data		Message to be hashed.
   * @param	string	$key		Shared secret key used for generating the HMAC variant of the message digest.
   * @param	mixed	$hash		Anything that evaluates to boolean false to generate a message digest, or a message digest string to verify.
   * @param	string	$algorithm	Name of selected hashing algorithm.
   * @return	mixed                   Returns a string containing the calculated message digest as lowercase hexits unless hash does not evaluate to boolean false and the verification fails in which case boolean false is returned.
   */
  final public static function hash($data, $key = "", $hash = NULL, $algorithm = "sha1") {
    $mode = in_array($algorithm, hash_algos());
    self::$dlen = strlen($data);
    $result = self::$hdata = $mode ? hash_hmac($algorithm, $data, $key) : self::sha1Hmac($data, $key);
    $salt = uniqid(mt_rand(), TRUE);
    $salt = $mode ? hash_hmac($algorithm, $salt, $key) : self::sha1Hmac($salt, $key);
    $slen = strlen($salt);
    $slen = max($slen >> 3, ($slen >> 2) - strlen($data));
    $salt = $hash ? ktHash::harvest($hash, $slen) : substr($salt, -$slen);
    $result = ktHash::scramble($result, $salt);
    $result = $mode ? hash_hmac($algorithm, $result, $key) : self::sha1Hmac($result, $key);
    $result = substr($result, $slen);
    $result = ktHash::scramble($result, $salt);
    return $hash && $hash !== $result ? FALSE : $result;
  }

  /**
   * Scrambles a salt string into the target string. Override this to produce different hashes.
   *
   * @param	string	$hash	    Target string.
   * @param	string	$salt	    Salt string.
   * @return	string		    Target string with salt string scrambled into it.
   */
  protected static function scramble($hash, $salt) {
    $hash .= $salt;
    for ($pos = strlen($hash) - strlen($salt); $pos < strlen($hash); $pos++) {
      $index = ktHash::getIndex($pos);
      $tmp = $hash[$pos];
      $hash[$pos] = $hash[$index];
      $hash[$index] = $tmp;
    }
    return $hash;
  }

  /**
   * Harvests a substring from a target string. Reverse scramble. Override this if you're also overriding scramble().
   *
   * @param	string	$hash	    Target string.
   * @param	int	$slen	    Length of the output string.
   * @return	string		    String of length slen harvested from target string.
   */
  protected static function harvest($hash, $slen) {
    for ($pos = strlen($hash) - 1; $pos >= strlen($hash) - $slen; $pos--) {
      $index = ktHash::getIndex($pos);
      $tmp = $hash[$pos];
      $hash[$pos] = $hash[$index];
      $hash[$index] = $tmp;
    }
    return substr($hash, -$slen);
  }

  /**
   * Generate a keyed hash value using the SHA1-HMAC method. This class uses its own implementation in case of PHP's hash_algos() somehow doesn't provide SHA1.
   *
   * @param	string	$data	    Message to be hashed.
   * @param	string	$key	    Shared secret key used for generating the HMAC variant of the message digest.
   * @return	string		    Returns a string containing the calculated message digest as lowercase hexits.
   */
  final private static function sha1Hmac($data, $key) {
    $key = str_pad(strlen($key) > 64 ? sha1($key, TRUE) : $key, 64, chr(0x00));
    return sha1(($key ^ str_repeat(chr(0x5c), 64)) . sha1(($key ^ str_repeat(chr(0x36), 64)) . $data, TRUE));
  }

  /**
   * Calculates a pseudorandom index from a given position. Override this to produce different hashes. This method doesn't even have to be used if you override the scramble() and harvest() methods.
   *
   * @param	int	$position   Position in the string. Must be greater than zero.
   * @return	int		    A pseudorandom index.
   */
  protected static function getIndex($position) {
    return (self::$dlen * ktHash::$primes[(int)strpos("0123456789abcdef", self::$hdata[$position % strlen(self::$hdata)])]) % $position;
  }
}