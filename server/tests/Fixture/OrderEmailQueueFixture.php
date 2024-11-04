<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * OrderEmailQueueFixture
 */
class OrderEmailQueueFixture extends TestFixture
{
    /**
     * Table name
     *
     * @var string
     */
    public string $table = 'order_email_queue';
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
                'customer_name' => 'Lorem ipsum dolor sit amet',
                'customer_email' => 'Lorem ipsum dolor sit amet',
                'order_id' => 1,
                'quantity' => 1,
                'product_name' => 'Lorem ipsum dolor sit amet',
                'total_value' => 1.5,
                'order_status' => 'Lorem ipsum dolor sit amet',
                'created' => 1730644713,
                'modified' => 1730644713,
            ],
        ];
        parent::init();
    }
}
