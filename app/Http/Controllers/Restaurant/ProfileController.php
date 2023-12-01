<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{


    public function show()
    {
        $user = auth()->user();
        return view('restaurant.profile.index', ['user' => $user]);
    }

    public function edit()
    {
        $user = auth()->user();
        return view('restaurant.profile.edit', ['user' => $user]);
    }

    public function update()
    {
        $user = auth()->user();
        $request = request();
        $input = $request->only('first_name', 'last_name', 'phone_number', 'language', 'city', 'state', 'country', 'zip', 'address', 'profile_image');
        $request->validate([
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone_number' => [
                'required', 'regex:/^[+]{0,1}[1-9]{0,1}[0-9]{9,15}$/',
            ],
            'profile_image' => ['max:10000', "image", 'mimes:jpeg,png,jpg,gif,svg'],
        ], [
            "first_name.required" => __('validation.required', ['attribute' => 'first name']),
            "first_name.string" => __('validation.custom.invalid', ['attribute' => 'first name']),

            "last_name.required" => __('validation.required', ['attribute' => 'last name']),
            "last_name.string" => __('validation.custom.invalid', ['attribute' => 'last name']),

            "phone_number.required" => __('validation.required', ['attribute' => 'phone number']),
            "phone_number.regex" => __('validation.custom.invalid', ['attribute' => 'phone number']),

            "profile_image.max" => __('validation.gt.file', ['attribute' => 'profile image', 'value' => 10000]),
            "profile_image.image" => __('validation.enum', ['attribute' => 'profile image']),
            "profile_image.mimes" => __('validation.enum', ['attribute' => 'profile image']),

        ]);

        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.profile.menu')]));

        $user->fill($input);
        $user->save();
        return redirect(route('restaurant.profile'));
    }

    public function passwordEdit()
    {
        $user = auth()->user();
        return view('restaurant.password.edit', ['user' => $user]);
    }

    public function passwordUpdate()
    {
        $user = auth()->user();
        $request = request();

        $request->validate([
            'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
                if (!\Hash::check($value, $user->password)) {
                    return $fail(__('validation.custom.currentpassword'));
                }
            }],
            'password' => ['required', 'string', 'min:8', 'confirmed', 'different:current_password'],

        ], [
            "password.required" => __('validation.required', ['attribute' => 'new password']),
            "password.string" => __('validation.password.invalid', ['attribute' => 'new password']),
            "password.regex" => __('validation.password.invalid', ['attribute' => 'new password']),
            "password.different" => __('validation.custom.samepassword', ['attribute' => 'new password']),
        ]);
        $user->password = Hash::make($request->password);
        $user->save();
        $request->session()->flash('Success', __('system.messages.change_success_message', ['model' => __('system.password.title')]));

        return redirect(route('restaurant.profile'));
    }
}
