<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantUser;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller {

    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {

        $lbl_restaurant_name = strtolower(__('system.fields.restaurant_name'));
        $lbl_restaurant_type = strtolower(__('system.fields.restaurant_type'));
        $lbl_phone_number = strtolower(__('system.fields.phone_number'));
        $lbl_first_name = strtolower(__('system.fields.first_name'));
        $lbl_last_name = strtolower(__('system.fields.last_name'));
        $lbl_email = strtolower(__('system.fields.email'));
        $lbl_password = strtolower(__('system.fields.password'));

        return Validator::make($data, [
            'first_name'      => ['required', 'string', 'max:50'],
            'last_name'       => ['required', 'string', 'max:50'],
            'restaurant_name' => ['required', 'string', 'max:255'],
            'restaurant_type' => ['required', 'string', 'in:' . (implode(',', array_keys(Restaurant::RESTAURANT_TYPE)))],
            'email'           => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number'    => ['required', 'regex:/^[+]{0,1}[1-9]{0,1}[0-9]{9,15}$/', 'unique:users,phone_number'],
            'password'        => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            "first_name.required" => __('validation.required', ['attribute' => $lbl_first_name]),
            "first_name.string"   => __('validation.custom.invalid', ['attribute' => $lbl_first_name]),


            "last_name.required" => __('validation.required', ['attribute' => $lbl_last_name]),
            "last_name.string"   => __('validation.custom.invalid', ['attribute' => $lbl_last_name]),

            "restaurant_name.required" => __('validation.required', ['attribute' => $lbl_restaurant_name]),
            "restaurant_name.string"   => __('validation.required', ['attribute' => $lbl_restaurant_name]),


            "restaurant_type.required" => __('validation.custom.select_required', ['attribute' => $lbl_restaurant_type]),
            "restaurant_type.string"   => __('validation.enum', ['attribute' => $lbl_restaurant_type]),
            "restaurant_type.in"       => __('validation.enum', ['attribute' => $lbl_restaurant_type]),

            "phone_number.required" => __('validation.required', ['attribute' => $lbl_phone_number]),
            "phone_number.regex"    => __('validation.custom.invalid', ['attribute' => $lbl_phone_number]),
            "phone_number.unique"   => __('validation.unique', ['attribute' => $lbl_phone_number]),

            "email.required" => __('validation.required', ['attribute' => $lbl_email]),
            "email.string"   => __('validation.custom.invalid', ['attribute' => $lbl_email]),
            "email.email"    => __('validation.custom.invalid', ['attribute' => $lbl_email]),
            "email.unique"   => __('validation.unique', ['attribute' => $lbl_email]),

            "password.required" => __('validation.required', ['attribute' => $lbl_password]),
            "password.string"   => __('validation.password.invalid', ['attribute' => $lbl_password]),
            "password.regex"    => __('validation.password.invalid', ['attribute' => $lbl_password]),

        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {

        $user = User::create([
            'first_name'   => $data['first_name'],
            'last_name'    => $data['last_name'],
            'email'        => $data['email'],
            'phone_number' => $data['phone_number'],
            'password'     => Hash::make($data['password']),
            'status'       => User::STATUS_ACTIVE,
        ]);

        $user->assignRole('restaurant');

        $restaurant = Restaurant::create([
            'user_id' => $user->id,
            'name'    => $data['restaurant_name'],
            'type'    => $data['restaurant_type'],
        ]);

        $user->restaurant_id = $restaurant->id;
        $user->save();

        $restaurant_user = RestaurantUser::create([
            'restaurant_id' => $restaurant->id,
            'user_id'       => $user->id,
            'role'          => RestaurantUser::ROLE_ADMIN,
        ]);

        return $user;
    }
}
