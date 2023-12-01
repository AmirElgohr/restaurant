<?php

namespace App\Http\Controllers\Restaurant;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use App\Models\RestaurantUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Searchable\ModelSearchAspect;
use App\Http\Requests\Restaurant\UserRequest;
use App\Repositories\Restaurant\UserRepository;

class UserController extends Controller
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
        $params['user_id'] = $user->id;
        if ($request->get('user_list', 'current') == 'current' || !isAdmin())
            $params['restaurant_id'] = $user->restaurant_id;
        elseif ($request->user_list == 'not_assigned') {
            $params['not_assigned'] = true;
        } elseif ($request->user_list !=  'all') {
            return redirect(route('restaurant.users.index'));
        }
//        $users = (new UserRepository())->getRestaurantUsers($params);
//        if ($request->user_list ==  'all') {
//            $users->load('restaurants');
//        }

        $users = User::query()->whereHas('roles', function($query){
            $query->where('name', 'admin');
        })->where('users.id', '!=', $user->id)->paginate($par_page);
        return view('restaurant.users.index', ['users' => $users]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }
        return view('restaurant.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }
        $user = auth()->user();
        $data = $request->all();

        $newUser = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'profile_image' => $data['profile_image'] ?? null,
            'password' => Hash::make($data['password']),
            'status' => User::STATUS_ACTIVE,
            'city' => $data['city'],
            'state' => $data['state'],
            'city' => $data['city'],
            'country' => $data['country'],
            'zip' => $data['zip'],
            'address' => $data['address'],
            'restaurant_id' => $user->restaurant_id,
        ]);

        $newUser->assignRole('admin');
        $restaurant_user = RestaurantUser::create([
            'restaurant_id' => $user->restaurant_id,
            'user_id' => $newUser->id,
            'role' => RestaurantUser::ROLE_STAFF,
        ]);
        $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.users.title')]));

        return redirect(route('restaurant.users.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }
        if (($redirect = $this->checkRestaurantIsValidUser($user)) != null) {
            return redirect($redirect);
        }

        return view('restaurant.users.edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request, User $user)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }
        if (($redirect = $this->checkRestaurantIsValidUser($user)) != null) {
            return redirect($redirect);
        }
        $data = $request->only('first_name', 'last_name', 'phone_number',  'city', 'state', 'country', 'zip', 'profile_image', 'address');
        $user = $user->fill($data)->save();
        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.users.title')]));

        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.users.index'));
    }

    public function checkRestaurantIsValidUser($user)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }
        if (auth()->user()->user_type == User::USER_TYPE_ADMIN) {
            return;
        }

        $restaurant = auth()->user()->restaurant;
        $restaurant->load(['users' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }]);
        if (count($restaurant->users) == 0) {
            $back = request()->get('back', route('restaurant.users.index'));
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.users.title')]));

            return $back;
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }
        $request = request();
        if (($redirect = $this->checkRestaurantIsValidUser($user)) != null) {
            return redirect($redirect);
        }
        $user->load(['restaurants' => function ($q) use ($user) {
            $q->where('restaurants.user_id', $user->id);
        }]);
        foreach ($user->restaurants as $restaurant) {
            $restaurant->load(['users' => function ($q) use ($user) {
                $q->wherePivot('user_id', "!=", $user->id);
            }]);
            if (count($restaurant->users) > 0) {
                $restaurant->user_id = $restaurant->users->first()->id;
            } else {
                $restaurant->user_id = null;
            }
            $restaurant->save();
            // dd($restaurant->users->toArray());
        }
        $user->delete();
        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.users.title')]));

        if ($request->back) {
            return redirect($request->back);
        }

        return redirect(route('restaurant.users.index'));
    }

    public function assignRestaurant(User $user)
    {
        if(!auth()->user()->isAdmin()){
            abort(403);
        }
        $request = request();
        if (($redirect = $this->checkRestaurantIsValidUser($user)) != null) {
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.users.title')]));
        }
        $restaurant = Restaurant::find($request->assign_restaurant);
        if (!$restaurant) {
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.restaurants.title')]));
        }
        $restaurant_user = RestaurantUser::create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id,
            'role' => RestaurantUser::ROLE_STAFF,
        ]);
        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.users.title')]));

        return redirect()->back();
    }
}
