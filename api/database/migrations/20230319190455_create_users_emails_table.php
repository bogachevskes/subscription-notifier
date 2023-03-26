<?php
declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateUsersEmailsTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('users_emails');
        $table->addColumn('user_id', 'integer', ['signed' => false, 'null' => false])
            ->addColumn('email', 'string', ['limit' => 50, 'null' => false])
            ->addColumn('token', 'string', ['limit' => 36, 'null' => false])
            ->addColumn('confirmed', 'boolean', ['default' => 0])
            ->addColumn('checked', 'boolean', ['default' => 0])
            ->addColumn('valid', 'boolean', ['default' => 0])
            ->addColumn('created_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP'])
            ->addColumn('updated_at', 'datetime', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
            ->addIndex(['email'], ['unique' => true])
            ->addIndex(['token'], ['unique' => true])
            ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
