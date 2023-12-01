<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:50'],
            'last_name' => ['required', 'string', 'max:50'],
            'phone_number' => ['required', 'regex:/^[+]{0,1}[1-9]{0,1}[0-9 \(\)]{9,15}$/', 'unique:users,phone_number'],
            'profile_image' => ['max:10000', "image", 'mimes:jpeg,png,jpg,gif,svg'],
            'password' => ['required', 'string', 'min:8'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
        ];
        if (isset($this->user)) {
            $rules['phone_number'] = ['required', 'regex:/^[+]{0,1}[1-9]{0,1}[0-9 \(\)]{9,15}$/', 'unique:users,phone_number,' . $this->user->id];
            unset($rules['password']);
            unset($rules['email']);
        }
        return $rules;
    }

    public function messages()
    {
        $lbl_phone_number = strtolower(__('system.fields.phone_number'));
        $lbl_first_name = strtolower(__('system.fields.first_name'));
        $lbl_last_name = strtolower(__('system.fields.last_name'));
        $lbl_email = strtolower(__('system.fields.email'));
        $lbl_password = strtolower(__('system.fields.password'));
        $lbl_profile_image = strtolower(__('system.fields.profile_image'));
        return [
            "first_name.required" => __('validation.required', ['attribute' => $lbl_first_name]),
            "first_name.string" => __('validation.custom.invalid', ['attribute' => $lbl_first_name]),

            "last_name.required" => __('validation.required', ['attribute' => $lbl_last_name]),
            "last_name.string" => __('validation.custom.invalid', ['attribute' => $lbl_last_name]),

            "phone_number.required" => __('validation.required', ['attribute' => $lbl_phone_number]),
            "phone_number.regex" => __('validation.custom.invalid', ['attribute' => $lbl_phone_number]),
            "phone_number.unique" => __('validation.unique', ['attribute' => $lbl_phone_number]),

            "profile_image.max" => __('validation.gt.file', ['attribute' => $lbl_profile_image, 'value' => 10000]),
            "profile_image.image" => __('validation.enum', ['attribute' => $lbl_profile_image]),
            "profile_image.mimes" => __('validation.enum', ['attribute' => $lbl_profile_image]),
            "email.required" => __('validation.required', ['attribute' => $lbl_email]),
            "email.string" => __('validation.custom.invalid', ['attribute' => $lbl_email]),
            "email.email" => __('validation.custom.invalid', ['attribute' => $lbl_email]),
            "email.unique" => __('validation.unique', ['attribute' => $lbl_email]),

            "password.required" => __('validation.required', ['attribute' => $lbl_password]),
            "password.string" => __('validation.password.invalid', ['attribute' => $lbl_password]),
            "password.regex" => __('validation.password.invalid', ['attribute' => $lbl_password]),

        ];
    }
}
