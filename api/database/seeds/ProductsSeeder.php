<?php


use Phinx\Seed\AbstractSeed;

class ProductsSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $table = $this->table('products');

        $this->execute('SET foreign_key_checks = 0');

        $table->truncate();

        $table->insert([
            'name' => 'Product subscription',
        ])
        ->saveData();

        $this->execute('SET foreign_key_checks = 1');
    }
}
