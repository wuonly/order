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

namespace Module\Order\Model;

use Pi\Application\Model\Model;

class Basket extends Model
{
    /**
     * {@inheritDoc}
     */
    protected $columns = array(
        'id',
        'order',
        'product',
        'product_price',
        'discount_price',
        'shipping_price',
        'packing_price',
        'setup_price',
        'vat_price',
        'total_price',
        'number',
        'extra',
    );
}
