<?php

namespace App\Repositories\Restaurant;

use App\Models\Language;
use JasonGuru\LaravelMakeRepository\Repository\BaseRepository;


/**
 * Class LanguageRepository.
 */
class LanguageRepository extends BaseRepository
{
    /**
     * @return string
     *  Return the model
     */
    public function model()
    {
        return Language::class;
    }

    public function getAllLanguagesData($params)
    {
        $table = $this->model->getTable();
        return $this->model->sortable()->when(isset($params['filter']), function ($q) use ($params, $table) {
            $q->where(function ($query) use ($params, $table) {
                $query->where("$table.name", 'like', '%' . $params['filter'] . '%');
                $query->orWhere("$table.id", '=',  $params['filter']);
            });
        })->paginate($params['par_page']);
    }
}
