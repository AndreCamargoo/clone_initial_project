<?php

namespace App\Services\Category;

use App\DTO\Category\CreateCategoryDTO;
use App\DTO\Category\UpdateCategoryDTO;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB as DBT;
use Illuminate\Database\DatabaseManager as DB;
use App\Repositories\Contracts\Category\RepositoryCategoryInterface;
use App\Repositories\Exceptions\PropertyTableNotExists;
use Carbon\Carbon;

class CategoryBaseQueryBuilderRepository implements RepositoryCategoryInterface
{
    protected $tb, $db;
    protected $orderBy = ["column" => "id", "order" => "DESC"];
    protected $innerJoin = ["tabela" => "", "foreign" => "", "references" => "", "type" => "left join", "main_table_fields" => [], "secondary_table_fields" => []];

    public function __construct(DB $db)
    {
        $this->tb = $this->resolveTable();
        $this->db =  $db;
    }

    public function getAll()
    {
        $result = $this->db->table($this->tb)->orderBy($this->tb . "." . $this->orderBy['column'], $this->orderBy['order']);

        if ($this->innerJoin["tabela"] && $this->innerJoin["foreign"] && $this->innerJoin["references"]) {
            $result = $this->verifyJoins($result);
        }

        $result = json_decode(json_encode($result->get()), true);

        return $result;
    }

    public function findById($id)
    {
        $result = (array) $this->db->table($this->tb)->find($id);
        return $result;
    }

    public function findWhere($column, $value)
    {
        $result = $this->db->table($this->tb)
            ->orderBy($this->tb . "." . $this->orderBy['column'], $this->orderBy['order']);

        if ($this->innerJoin["tabela"] && $this->innerJoin["foreign"] && $this->innerJoin["references"]) {
            $result = $this->verifyJoins($result);
        }

        $result = $result->where($this->tb . "." . $column, "LIKE", "%" . $value . "%");
        $result = $result->get();

        return $result;
    }

    public function findWhereFirst($column, $value)
    {
        $result = (array) $this->db->table($this->tb)->where($column, $value)->first();
        return $result;
    }

    public function paginate(int $page = 1, int $totalPerPage = 15, string $filter = null)
    {
        $result = $this->db->table($this->tb)->orderBy($this->tb . "." . $this->orderBy['column'], $this->orderBy['order']);

        if ($this->innerJoin["tabela"] && $this->innerJoin["foreign"] && $this->innerJoin["references"]) {
            $result = $this->verifyJoins($result);
        }

        $result = $result->paginate($totalPerPage, ['*'], 'page', $page);

        return $result;
    }

    public function store(CreateCategoryDTO $dto)
    {
        return DBT::transaction(function () use ($dto) {
            $insert = $this->db->table($this->tb)->insertGetId([
                "title" => $dto->title,
                "url" => Str::slug($dto->title),
                "description" => $dto->description,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now()
            ]);

            $category = $this->findById($insert);

            return  (array) $category;
        });
    }

    public function update(UpdateCategoryDTO $dto)
    {
        if (!$category = $this->findById($dto->id)) return null;


        return DBT::transaction(function () use ($dto, $category) {
            unset($dto->id);
            $dto = (array) $dto;
            $this->db->table($this->tb)->where('id', $category["id"])->update($dto);
            return (array) $this->findById($category["id"]);
        });
    }

    public function delete($id)
    {
        return $this->db->table($this->tb)->where('id', $id)->delete();
    }

    public function orderBy($column, $order = 'DESC')
    {
        $this->orderBy = ["column" => $column, "order" => $order];
        return $this;
    }

    /**
     * Last optional parameter, but if sent, inform one of the options "inner join" or "left join" or "right join"
     *
     * @param $relationships = "tabela;foreign;reference;left join"
     */
    public function relationships(array $relationships, array $mainFields = [], array $secondaryFields = [])
    {
        foreach ($relationships as $references) {
            $references = explode(";", $references);
            $this->innerJoin =  [
                "tabela" => $references[0],
                "foreign" => $references[1],
                "references" => $references[2],
                "type" => $references[3] ?? "inner join",
                "main_table_fields" => $mainFields,
                "secondary_table_fields" => $secondaryFields
            ];
        }
        return $this;
    }

    public function resolveTable()
    {
        if (!property_exists($this, 'table')) {
            throw new PropertyTableNotExists();
        }

        return $this->table;
    }

    private function verifyJoins(object $result)
    {
        if (Str::lower($this->innerJoin["type"] === 'inner join')) {
            $result = $result->join($this->innerJoin["tabela"], $this->innerJoin["tabela"] . "." . $this->innerJoin["foreign"], $this->tb . "." . $this->innerJoin["references"]);
        } else if (Str::lower($this->innerJoin["type"] === 'left join')) {
            $result = $result->leftJoin($this->innerJoin["tabela"], $this->innerJoin["tabela"] . "." . $this->innerJoin["foreign"], $this->tb . "." . $this->innerJoin["references"]);
        } else {
            $result = $result->rightJoin($this->innerJoin["tabela"], $this->innerJoin["tabela"] . "." . $this->innerJoin["foreign"], $this->tb . "." . $this->innerJoin["references"]);
        }

        $fields = $this->selectFieldsJoins();
        $result = $result->select($fields);
        return $result;
    }

    private function selectFieldsJoins()
    {
        $mainFields = [];
        $secondaryFields = [];

        if (count($this->innerJoin['main_table_fields']) > 0) {
            foreach ($this->innerJoin['main_table_fields'] as $field) {
                $mainFields[] = $this->tb . "." . $field . " AS " . $this->tb . "_" . $field;
            }
        } else {
            $mainFields[] = $this->tb . ".*";
        }

        if (count($this->innerJoin['secondary_table_fields']) > 0) {
            foreach ($this->innerJoin['secondary_table_fields'] as $field) {
                $secondaryFields[] = $this->innerJoin['tabela'] . "." . $field . " AS " . $this->innerJoin['tabela'] . "_" . $field;
            }
        } else {
            $secondaryFields[] = $this->innerJoin['tabela'] . ".*";
        }

        return array_merge($mainFields, $secondaryFields);
    }
}
