<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\RestaurantUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminUserAndRestaruantSeeder extends Seeder {

    public function run()
    {
        $role = Role::firstOrCreate(['name' => 'admin']);
        $user = User::query()->where('email', 'user@restaurant.com')->count();
        if($user == 0){
            $user = User::query()->create([
                'first_name'   => 'Default',
                'last_name'    => 'Restaurant',
                'email'        => 'user@restaurant.com',
                'phone_number' => '+918855226633',
                'password'     => Hash::make('password'),
                'status'       => User::STATUS_ACTIVE,
                'user_type'    => User::USER_TYPE_ADMIN,
            ]);

            $user->assignRole('restaurant');

            $restaurant = Restaurant::query()->create([
                'user_id' => $user->id,
                'name'    => 'The Salad Life',
                'type'    => 'Hotel',
            ]);

            $user->restaurant_id = $restaurant->id;
            $user->save();

            $restaurant_user = RestaurantUser::query()->create([
                'restaurant_id' => $restaurant->id,
                'user_id'       => $user->id,
                'role'          => RestaurantUser::ROLE_ADMIN,
            ]);

        }

        $user = User::where('email', 'admin@admin.com')->count();
        if($user == 0){
            $user = User::query()->create([
                'first_name'   => 'Default',
                'last_name'    => 'Admin',
                'email'        => 'admin@admin.com',
                'phone_number' => '+918855226633',
                'password'     => Hash::make('password'),
                'status'       => User::STATUS_ACTIVE,
                'user_type'    => User::USER_TYPE_ADMIN,
            ]);

            $user->assignRole('admin');
        }
    }
}
