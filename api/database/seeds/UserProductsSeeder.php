<?php


use Phinx\Seed\AbstractSeed;

class UserProductsSeeder extends AbstractSeed
{
    private $table;
    
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
        $this->table = $this->table('users_products');

        $this->execute('SET foreign_key_checks = 0');

        $this->table->truncate();

        for ($i = 1; $i <= 5; $i++) {
            $this->insertPackage($i);
        }

        $this->execute('SET foreign_key_checks = 1');
    }

    private function insertPackage(int $iteration)
    {
        $data = [];

        $maxIteration = 2e5;

        $start = $maxIteration * $iteration - $maxIteration;
        
        for ($i = 1 + $start; $i <= ($maxIteration * $iteration); $i++) {

            $interval = rand(1, 10);
            
            $datetime = new DateTime();
            $datetime->modify('+' . $interval . ' day');
            
            $data[] = [
                'user_id' => $i,
                'product_id' => 1,
                'validts' => $datetime->format('U'),
            ];
        }

        $this->table->insert($data)
            ->saveData();
    }
}
