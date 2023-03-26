<?php


use Phinx\Seed\AbstractSeed;
use Ramsey\Uuid\Uuid;

class UserEmailSeeder extends AbstractSeed
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
        $this->table = $this->table('users_emails');

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
            $data[] = [
                'user_id' => $i,
                'email' => 'user-' . $i . '@mail.ru',
                'token' => Uuid::uuid1(),
            ];
        }

        $this->table->insert($data)
            ->saveData();
    }
}
