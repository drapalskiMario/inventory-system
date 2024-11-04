<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateOrders extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('orders');
        $table->addColumn('customer_id', 'integer', [
            'null' => false,
        ])->addForeignKey('customer_id', 'customers', 'id', [
            'delete' => 'CASCADE',
            'update' => 'NO_ACTION',
            'constraint' => 'fk_orders_clients'
        ]);
        $table->addColumn('product_id', 'integer', [
            'null' => false,
        ])->addForeignKey('product_id', 'products', 'id', [
            'delete' => 'CASCADE',
            'update' => 'NO_ACTION',
            'constraint' => 'fk_orders_products'
        ]);
        $table->addColumn('quantity', 'integer', [
            'null' => false,
        ]);
        $table->addColumn('order_date', 'datetime', [
            'default' => 'CURRENT_TIMESTAMP',
            'null' => false,
        ]);
        $table->addColumn('status', 'enum', [
            'values' => ['new', 'processed', 'shipped', 'cancelled'],
            'default' => 'new',
            'null' => false,
        ]);
        $table->create();
    }
}
