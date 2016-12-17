<?php
/**
  */

namespace Application\State;

/**
 *  Interface for Context State Object
 */
interface ContextInterface
{
    /**
     * Set Username
     *
     * @return string
     */
    public static function setUsername();
    public static function getUsername();
    public static function setContext();
    public static function getContext();
}
