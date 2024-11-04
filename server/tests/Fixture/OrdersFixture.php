<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrdersFixture
 */
class OrdersFixture extends TestFixture
{
    /**
     * Init method
     *
     * @return void
     */
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'customer_id' => 1,
                'product_id' => 1,
                'quantity' => 1,
                'order_date' => 1730576732,
                'status' => 'Lorem ipsum dolor ',
                'created' => 1730576732,
                'modified' => 1730576732,
            ],
        ];
        parent::init();
    }
}
