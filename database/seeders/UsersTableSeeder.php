<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UsersTableSeeder extends Seeder
{   
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = [
            [
                'name' => 'Kredo Admin',
                'email' => 'admin@kredo.mail',
                'password' => Hash::make('password'),
                'role_id' => 1
            ],
            [
                'name' => 'Kredo Admin 2',
                'email' => 'admin2@kredo.mail',
                'password' => Hash::make('password'),
                'role_id' => 1
            ]
        ];

        $this->user->insert($admin);
    }
}
