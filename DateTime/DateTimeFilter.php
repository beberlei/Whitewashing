<?php
/**
 * Whitewashing
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so I can send you a copy immediately.
 */

namespace Whitewashing\DateTime;

class DateTimeFilter implements \Zend_Filter_Interface
{
    /**
     * @var string
     */
    protected $_format = '!Y-m-d H:i:s';

    /**
     * @param string $value
     */
    public function filter($value)
    {
        return DateTime::createFromFormat($this->_format, $value, DateFactory::getDefaultTimeZone());
    }
}