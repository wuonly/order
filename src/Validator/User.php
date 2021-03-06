<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Order\Validator;

use Pi;
use Zend\Validator\AbstractValidator;

class User extends AbstractValidator
{
    const TAKEN = 'userNotExists';

    /**
     * @var array
     */
    protected $messageTemplates = array();

    protected $options = array();

    /**
     * {@inheritDoc}
     */
    public function __construct($options = null)
    {
        $this->messageTemplates = array(
            self::TAKEN => __('Select user ID not exist on system'),
        );

        parent::__construct($options);
    }

    /**
     * Slug validate
     *
     * @param  mixed $value
     * @param  array $context
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        $value = intval($value);

        $user = Pi::user()->get($value);

        if (isset($user['id']) && $user['id'] > 0) {
            return true;
        } else {
            $this->error(static::TAKEN);
            return false;
        }
    }
}