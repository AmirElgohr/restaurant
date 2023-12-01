<?php

namespace App\Repositories\Restaurant;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;



/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return User::class;
    }

    public function getRestaurantUsersQuery($params)
    {
        return $this->model->with('restaurants')
            ->When(isset($params['restaurant_id']), function ($q) use ($params) {
                $q->whereHas('restaurants', function ($q) use ($params) {
                    $q->where('restaurant_id', $params['restaurant_id']);
                });
            })
            ->When(isset($params['not_assigned']), function ($q) use ($params) {
                $q->doesntHave('restaurants');
            })
            ->When(isset($params['user_id']), function ($q) use ($params) {
                $q->where('id', '!=', $params['user_id']);
            });
    }
    public function getRestaurantUser($params)
    {
        $user = $this->getRestaurantUsersQuery($params)->where('id', $params['id'])->first();
        return $user;
    }

    public function getRestaurantUsers($params)
    {
        $table = $this->model->getTable();
        // DB::enableQueryLog();
        $users = $this->getRestaurantUsersQuery($params)->sortable()->when(isset($params['filter']), function ($q) use ($params, $table) {
            $q->where(function ($q) use ($params, $table) {
                $q->where(DB::raw('CONCAT(' . $table . '.first_name, \' \', ' . $table . '.last_name)'), 'like', '%' . $params['filter'] . '%')
                    ->orWhere($table . '.email', 'like', '%' . $params['filter'] . '%')
                    ->orWhere($table . '.phone_number', 'like',  $params['filter'] . '%')
                    ->orWhere($table . '.id', '=',  $params['filter']);
            });
        })->select(

            "$table.first_name",
            "$table.last_name",
            "$table.email",
            "$table.phone_number",
            "$table.profile_image",
            "$table.id",
            "$table.created_at",
        );

        $users = $users->paginate($params['par_page']);
        // dd(DB::getQueryLog());
        return $users;
    }
    public function getRestaurantsCount()
    {
        $users = $this->model->whereHas('roles', function($query){
            $query->where('name', 'restaurant');
        })->count('id');
        return $users;
    }

    public function getRestaurantUsersCount($params)
    {
        $table = $this->model->getTable();
        $users = $this->getRestaurantUsersQuery($params)->count('id');
        return $users;
    }
    public function getRestaurantUsersRecodes($params = null)
    {
        $table = $this->model->getTable();
        $users = $this->getRestaurantUsersQuery($params)->orderBy('id', 'desc')->select(

            "$table.first_name",
            "$table.last_name",
            "$table.email",
            "$table.profile_image",
            "$table.phone_number",
            "$table.id",
            "$table.created_at",
        );
        if (isset($params['recodes'])) {
            $users = $users->limit($params['recodes']);
        }
        $users = $users->get();
        return $users;
    }
}
