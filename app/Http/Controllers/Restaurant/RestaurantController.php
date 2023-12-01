<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\RestaurantRequest;
use App\Http\Requests\Restaurant\UpdateRestaurantRequest;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use App\Models\RestaurantUser;
use App\Models\User;
use App\Repositories\Restaurant\FoodCategoryRepository;
use App\Repositories\Restaurant\RestaurantRepository;
use Barryvdh\Debugbar\Facades\Debugbar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }
        $request = request();
        $user = auth()->user();

        $params = $request->only('par_page', 'sort', 'direction', 'filter');

        $par_page = 10;
        if (in_array($request->par_page, [10, 25, 50, 100])) {
            $par_page = $request->par_page;
        }
        $params['par_page'] = $par_page;
        if ($user->user_type != User::USER_TYPE_ADMIN)
            $params['user_id'] = $user->id;
        $restaurants = (new RestaurantRepository())->getUserRestaurants($params);
        return view('restaurant.restaurants.index', ["restaurants" => $restaurants]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('restaurant.restaurants.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RestaurantRequest $request)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }

        // dd($request->all());
        $user = auth()->user();
        $data = $request->only('first_name', 'last_name', 'email', 'phone_number', 'name', 'type', 'password', 'logo');
//        $data['user_id'] = $user->id;

        // dd($foodCategories[0]->foods[0]->toarray());
        DB::beginTransaction();

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
            'name'    => $data['name'],
            'type'    => $data['type'],
            'logo'    => $data['logo'] ?? null,
        ]);

        $user->restaurant_id = $restaurant->id;
        $user->save();

//        $newUser = Restaurant::create($data);

        $restaurant_user = RestaurantUser::create([
            'restaurant_id' => $restaurant->id,
            'user_id'       => $user->id,
            'role'          => RestaurantUser::ROLE_ADMIN,
        ]);

        DB::commit();

        $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.restaurants.title')]));

        return redirect(route('restaurant.restaurants.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function show(Restaurant $restaurant)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }

        if (($redirect = $this->checkRestaurantIsValidUser($restaurant)) != null) {
            return redirect($redirect);
        }
        $restaurant->load(['created_user', 'users' => function ($q) {
            $q->limit(5);
        }]);
        return view('restaurant.restaurants.view', ['restaurant' => $restaurant]);
    }

    public function genarteQR(Restaurant $restaurant)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }

        $request = request();
        //  QR size

        $size = $request->size  && ($request->size >= 100 &&  $request->size <= 325) ? $request->size : 325;


        // logo size
        $logo_size = $request->logo_size  && ($request->logo_size >= 0.25 &&  $request->logo_size <=  0.5) ? $request->logo_size : 0.25;
        if (isset($request->image) && $request->has('image')) {
            $file = $request->image;
            $logo = File::get($file->getRealPath());
        } elseif ($request->logo == true && isset($restaurant->qr_details['logo'])) {
            $logo = Storage::get($restaurant->qr_details['logo']);
        }
        // color
        $color = $request->color ?? "#000000";
        list($cr, $cg, $cb) = sscanf($color, "#%02x%02x%02x");
        $color_transparent = $request->color_transparent  && ($request->color_transparent >= 1 &&  $request->color_transparent <=  100) ? $request->color_transparent : 100;

        // background
        $back_color = $request->back_color  ??  "#ffffff";
        list($br, $bg, $bb) = sscanf($back_color, "#%02x%02x%02x");
        $back_color_transparent = $request->back_color_transparent  && ($request->back_color_transparent >= 0 &&  $request->back_color_transparent <=  100) ? $request->back_color_transparent : 1;



        // gradient
        $gradient_method = $request->gradient_method  && in_array($request->gradient_method, ['vertical', 'horizontal', 'diagonal', 'inverse_diagonal', 'radial']) ? $request->gradient_method : 'vertical';
        $gradient_color1 = $request->gradient_color1 ?? "#000000";
        list($l1r, $l1g, $l1b) = sscanf($gradient_color1, "#%02x%02x%02x");
        $gradient_color2 =  $request->gradient_color2 ??  "#000000";
        list($l2r, $l2g, $l2b) = sscanf($gradient_color2, "#%02x%02x%02x");




        // QR Style
        $qr_style = $request->qr_style  && in_array($request->qr_style,  ['square', 'dot', 'round']) ? $request->qr_style : 'square';
        $qr_style_size = $request->qr_style_size  && ($request->qr_style_size >= 0.25 &&  $request->qr_style_size <=  0.5) ? $request->qr_style_size : 1;



        // EYE style

        $eye_style = $request->eye_style  && in_array($request->eye_style,  ['square', 'circle']) ? $request->eye_style : 'square';

        // eye color
        $eye_inner_color  = $request->eye_inner_color ?? "#000000";
        list($eir, $eig, $eib) = sscanf($eye_inner_color, "#%02x%02x%02x");
        $eye_outer_color  = $request->eye_outer_color ?? "#000000";
        list($eor, $eog, $eob) = sscanf($eye_outer_color, "#%02x%02x%02x");


        $QR  = QrCode::size($size)->format('png');

        if (isset($logo)) {
            if ($request->save == 0) {
                list($width, $height) = getimagesize(imageDataToCollection($logo));
                // print_r([$width * $logo_size, $height * $logo_size]);
                if (($width * $logo_size) > 500 || ($height * $logo_size) > 500) {
                    $logo_size /= 2;
                }
            }
            // print_r([$width * $logo_size, $height * $logo_size]);
            $QR = $QR->mergeString($logo, $logo_size);
        }
        // Background color
        $QR = $QR->backgroundColor($br ?? 0, $bg ?? 0, $bb ?? 0, $back_color_transparent);



        // QR color
        if ($request->gradient_method) {
            $QR = $QR->gradient($l1r ?? 0, $l1g ?? 0, $l1b ?? 0, $l2r ?? 0, $l2g ?? 0, $l2b ?? 0, $gradient_method);
        } else if ($request->color) {
            $QR = $QR->color($cr ?? 0, $cg ?? 0, $cb ?? 0, $color_transparent);
            Debugbar::info(['color' => [
                $cr ?? 0, $cg ?? 255, $cb ?? 255, $color_transparent
            ]]);
        }

        $QR = $QR->eye($eye_style);
        $QR = $QR->eyeColor(0, $eir ?? 0, $eig ?? 0, $eib ?? 0, $eor ?? 0, $eog ?? 0, $eob ?? 0);
        $QR = $QR->eyeColor(1, $eir ?? 0, $eig ?? 0, $eib ?? 0, $eor ?? 0, $eog ?? 0, $eob ?? 0);
        $QR = $QR->eyeColor(2, $eir ?? 0, $eig ?? 0, $eib ?? 0, $eor ?? 0, $eog ?? 0, $eob ?? 0);

        // QR style
        $QR = $QR->style($qr_style);



        if ($request->save == 1) {
            $qr_details = [
                'size' => $size,
                'logo' => $request->logo == true && isset($restaurant->qr_details['logo']) ? $restaurant->qr_details['logo'] : '',

                'is_logo_visible' => $request->is_logo_visible,
                'logo_size' => $logo_size,

                'color' => sprintf("#%02x%02x%02x", $cr ?? 0, $cg ?? 0, $cb ?? 0),
                'color_transparent' => $color_transparent,

                'back_color' => sprintf("#%02x%02x%02x", $br ?? 0, $bg ?? 0, $bb ?? 0),
                'back_color_transparent' => $back_color_transparent,

                'gradient_method' => $request->gradient_method != null ? $gradient_method : '',
                'gradient_color1' => sprintf("#%02x%02x%02x", $l1r ?? 0, $l1g ?? 0, $l1b ?? 0),
                'gradient_color2' => sprintf("#%02x%02x%02x", $l2r ?? 0, $l2g ?? 0, $l2b ?? 0),

                'qr_style' => $qr_style,
                'qr_style_size' => $qr_style_size,



                'eye_style' => $eye_style,
                'eye_inner_color' => sprintf("#%02x%02x%02x", $eir ?? 0, $eig ?? 0, $eib ?? 0),
                'eye_outer_color' => sprintf("#%02x%02x%02x", $eor ?? 0, $eog ?? 0, $eob ?? 0),

            ];
            if ($request->has('image')) {
                $file = $request->image;
                $qr_details['logo'] = uploadFile($file, 'qr_code_logo');
            }
            $restaurant->fill(['qr_details' => $qr_details])->save();
            $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.qr_code.menu')]));
        }

        $image = base64_encode(
            $QR->generate(route('restaurant.menu', $restaurant->id)),
        );
        return view('restaurant.restaurants.genarteqr', ['image' => $image]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function edit(Restaurant $restaurant)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }

        if (($redirect = $this->checkRestaurantIsValidUser($restaurant)) != null) {
            return redirect($redirect);
        }
        $user = $restaurant->adminUser();
        return view('restaurant.restaurants.edit', ['restaurant' => $restaurant, 'user' => $user]);
    }

    public function checkRestaurantIsValidUser($restaurant)
    {

        if(!auth()->user()->isAdmin()){
            abort(403);
        }

        $user = auth()->user();
        if ($user->user_type == User::USER_TYPE_ADMIN) {
            return;
        }
        $restaurant->load(['users' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }]);
        if (count($restaurant->users) == 0) {
            $back = request()->get('back', route('restaurant.restaurants.index'));
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.restaurants.title')]));

            return $back;
        }
    }

    public function createQR()
    {
        $users = auth()->user();
        $users->load(['restaurant' => function ($q) {
            $q->select('restaurants.*');
        }]);
        $restaurant = $users->restaurant;
        return view('restaurant.restaurants.create_qr', ['restaurant' => $restaurant]);
    }

    public function getQR()
    {
        $uuid = auth()->user()->restaurant->uuid;
        $url = config('app.url') . '?menu=' . $uuid;
        return view('restaurant.restaurants.get_qr', ['url' => $url]);
    }

    /**
     * Update the specified resource in storage.
     *
     */
    public function update(UpdateRestaurantRequest $request, Restaurant $restaurant)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }

        if (($redirect = $this->checkRestaurantIsValidUser($restaurant)) != null) {
            return redirect($redirect);
        }
//        $data = $request->only('name', 'type', 'contact_email', 'phone_number', 'language', 'city', 'state', 'country', 'zip', 'address', 'logo', 'cover_image', 'dark_logo');
        $user = $restaurant->adminUser();

        $userData = $request->only(['first_name', 'last_name', 'email', 'phone_number']);
        if($request->has('password') && !empty($request->get('password'))){
            $userData['password'] = Hash::make($request->get('password'));
        }
        $user = $user->fill($userData)->save();

        $restaurant = $restaurant->fill($request->only(['name', 'type', 'logo']))->save();
        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.restaurants.title')]));

        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.restaurants.index'));
    }

    public function defaultRestaurant(Restaurant $restaurant)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }

        if (($redirect = $this->checkRestaurantIsValidUser($restaurant)) != null) {
            return redirect($redirect);
        }
        $user = request()->user();
        $request = request();
        $user->restaurant_id = $restaurant->id;
        $user->save();
        $request->session()->flash('Success', __('system.messages.change_success_message', ['model' => __('system.restaurants.title')]));

        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('home'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restaurant  $restaurant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restaurant $restaurant)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }

        $request = request();
        if (($redirect = $this->checkRestaurantIsValidUser($restaurant)) != null) {
            return redirect($redirect);
        }
        $restaurant->load(['users' => function ($q) use ($restaurant) {
            $q->where('users.restaurant_id', $restaurant->id);
        }]);

        if (count($restaurant->users) > 0) {
            // $restaurant->load(['users.restaurants' =>  function ($q) use ($restaurant) {
            //     $q->wherePivot('restaurant_id', '!=', $restaurant->id);
            // }]);
            // dd($restaurant->users->toArray());
            foreach ($restaurant->users as $restoUser) {
                $restoUser->load(['restaurants' => function ($q) use ($restaurant) {
                    $q->wherePivot('restaurant_id', '!=', $restaurant->id);
                }]);
                // dd($restoUser->restaurants->toArray());
                if (count($restoUser->restaurants) > 0) {
                    $restoUser->restaurant_id = $restoUser->restaurants->first()->id;
                } else {
                    $restoUser->restaurant_id = null;
                }
                $restoUser->save();
            }
        }
        $restaurant->delete();
        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.restaurants.title')]));

        if ($request->back) {
            return redirect($request->back);
        }

        return redirect(route('restaurant.restaurants.index'));
    }

    public static function getRestaurantsDropdown()
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }

        $user = auth()->user();
        $restaurants = (new RestaurantRepository())->getAllRestaurantsWithIdAndName();
        return $restaurants;
    }
}
