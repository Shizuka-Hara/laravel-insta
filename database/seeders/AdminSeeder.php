<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; //password hashing algorithm

class AdminSeeder extends Seeder
{
    private $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->user->name = 'Administrator1';
        $this->user->email = 'superadmin1@gmail.com';
        $this->user->password = Hash::make('superadmin12345'); 
        $this->user->role_id = User::ADMIN_ROLE_ID; // 1
        $this->user->save();
    }
}
