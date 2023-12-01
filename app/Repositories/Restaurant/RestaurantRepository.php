<?php

namespace App\Repositories\Restaurant;

use App\Models\Restaurant;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;



/**
 * Class RestaurantRepository.
 */
class RestaurantRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Restaurant::class;
    }

    public function getUserRestaurantsQuery($params)
    {
        return $this->model->with('users')->when(isset($params['user_id']), function ($query) use ($params) {
            $query->whereHas('users', function ($q) use ($params) {
                $q->where('user_id', $params['user_id']);
            });
        });
    }

    public function getUserRestaurants($params)
    {
        $table = $this->model->getTable();

        $restaurants = $this->getUserRestaurantsQuery($params)->sortable()->when(isset($params['filter']), function ($q) use ($params, $table) {

            $q->where(function ($query) use ($params, $table) {
                $query->where("$table.name", 'like', '%' . $params['filter'] . '%')
                    ->orWhere("$table.type", 'like', '%' . $params['filter'] . '%')
                    ->orWhere("$table.phone_number", 'like',  $params['filter'] . '%')
                    ->orWhere("$table.id", '=',  $params['filter'])
                    ->orWhere("$table.contact_email", 'like', '%' . $params['filter'] . '%');
            });
        })->select(
            "$table.name",
            "$table.uuid",
            "$table.type",
            "$table.logo",
            "$table.cover_image",
            "$table.theme",
            "$table.contact_email",
            "$table.address",
            "$table.phone_number",
            "$table.created_at",
            "$table.id",
            "$table.user_id",
            "$table.language",
        )->paginate($params['par_page']);

        return $restaurants;
    }
    public function getUserRestaurantsDetails($params)
    {
        $table = $this->model->getTable();
        $restaurants = $this->getUserRestaurantsQuery($params)->when(isset($params['except_restaurant_id']), function ($q) use ($params) {
            $q->where('id', '!=', $params['except_restaurant_id']);
        })->select(
            "$table.name",
            "$table.logo",
            "$table.cover_image",
            "$table.theme",
            "$table.id",
            "$table.user_id",
            "$table.language",
            "$table.created_at",
        );
        if (isset($params['latest'])) {
            $restaurants = $restaurants->orderBy('id', 'desc');
        }
        if (isset($params['recodes'])) {
            $restaurants = $restaurants->limit($params['recodes'])->get();
        } else {
            $restaurants = $restaurants->get();
        }
        return $restaurants;
    }
    public function getAllRestaurantsWithIdAndName($params = [])
    {
        $table = $this->model->getTable();

        $restaurants = $this->getUserRestaurantsQuery($params)->when(isset($params['restaurant_id']), function ($query) use ($params) {
            $query->orderByRaw("FIELD(id," . $params['restaurant_id'] . " ) asc");
        })->pluck('name', 'id');

        return $restaurants->toArray();
    }

    public function getCountRestaurants($params = [])
    {
        $table = $this->model->getTable();

        $restaurants = $this->getUserRestaurantsQuery($params)->when(isset($params['restaurant_id']), function ($query) use ($params) {
            $query->orderByRaw("FIELD(id," . $params['restaurant_id'] . " ) asc");
        });

        return $restaurants->count('id');
    }
}
