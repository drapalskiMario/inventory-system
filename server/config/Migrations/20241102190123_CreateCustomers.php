<?php

declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateCustomers extends AbstractMigration
{
    /**
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('customers');
        $table->addColumn('name', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('email', 'string', [
            'limit' => 255,
            'null' => false,
        ]);
        $table->addColumn('phone', 'string', [
            'limit' => 20,
            'null' => true,
        ]);
        $table->addIndex(['email'], [
            'unique' => true,
            'name' => 'UNIQUE_EMAIL'
        ]);
        $table->create();
    }
}
