<?php

namespace App\Repositories\Core;

use Illuminate\Support\Str;
// use Illuminate\Support\Facades\DB;
use Illuminate\Database\DatabaseManager as DB;
use App\Repositories\Contracts\RepositoryInterface;
use App\Repositories\Exceptions\PropertyTableNotExists;

class BaseQueryBuilderRepository implements RepositoryInterface
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

        $result = $result->get();
        return $result;
    }

    public function findById($id)
    {
        return $this->db->table($this->tb)->find($id);
    }

    public function findWhere($column, $value)
    {
        $result = $this->db->table($this->tb)
            ->orderBy($this->tb . "." . $this->orderBy['column'], $this->orderBy['order']);

        if ($this->innerJoin["tabela"] && $this->innerJoin["foreign"] && $this->innerJoin["references"]) {
            $result = $this->verifyJoins($result);
        }

        $result = $result->where($this->tb . "." . $column, $value);

        $result = $result->get();
        return $result;
    }

    public function findWhereFirst($column, $value)
    {
        return $this->db->table($this->tb)->where($column, $value)->first();
    }

    public function paginate($totalPage = 10)
    {
        $result = $this->db->table($this->tb)->orderBy($this->tb . "." . $this->orderBy['column'], $this->orderBy['order']);

        if ($this->innerJoin["tabela"] && $this->innerJoin["foreign"] && $this->innerJoin["references"]) {
            $result = $this->verifyJoins($result);
        }

        $result = $result->paginate($totalPage);
        return $result;
    }

    public function store(array $data)
    {
        return $this->db->table($this->tb)->insert($data);
    }

    public function update($id, array $data)
    {
        return $this->db->table($this->tb)->where('id', $id)->update($data);
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
