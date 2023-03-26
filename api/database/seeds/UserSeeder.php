<?php


use Phinx\Seed\AbstractSeed;

class UserSeeder extends AbstractSeed
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
        $table = $this->table('users');

        $this->execute('SET foreign_key_checks = 0');
        
        $table->truncate();
        
        $data = [];
        
        for ($i = 1; $i <= 1e6; $i++) {
            $data[] = [
                'username'    => 'user-name-' . $i,
            ];
        }

        $table->insert($data)
              ->saveData();

        $this->execute('SET foreign_key_checks = 1');
    }
}
