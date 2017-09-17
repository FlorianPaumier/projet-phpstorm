<?php

    namespace App\Blog\Table;

use App\Blog\Entity\Post;
use Framework\Database\paginatedQuery;
use Pagerfanta\Pagerfanta;

class PostTable
{

    /**
         * @var \PDO
         */
    private $pdo;

    public function __construct(\PDO $pdo)
    {

        $this->pdo = $pdo;
    }

    /**
         * Pagine les articles
         * @return Pagerfanta
         */
    public function findPaginated(int $perpage, $currentpage): Pagerfanta
    {
        $query =  new paginatedQuery(
            $this->pdo,
            "Select * From posts Order By created_at Desc",
            "Select Count(id) From posts",
            Post::class
        );

        return (new Pagerfanta($query))
            ->setMaxPerPage($perpage)
            ->setCurrentPage($currentpage);
    }

    /**
         * trouve un article pas l'id
         */
    public function find(int $id): ?Post
    {
        $query = $this->pdo
            ->prepare('Select * From posts Where id = ?');
        $query->execute([$id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Post::class);
        return $query->fetch() ?: null;
    }

    /**
     * @param int $id
     * @param array $fields
     * @return bool
     */
    public function update(int $id, array $params): bool
    {
        $fieldquery = $this->buildfieldquery($params);
        $params['id'] = $id;
        $statement = $this->pdo->prepare("Update posts Set $fieldquery where id = :id");

        return $statement->execute($params);
    }

    public function insert(array $params): bool
    {
        $fields = array_keys($params);
        $values = array_map(function ($field) {
            return ':' . $field;
        }, $fields);
        $statement = $this->pdo->prepare(
            "Insert Into posts ( " . join(',', $fields) . " ) Values ( " . join(',', $values). " )"
        );

        return $statement->execute($params);
    }


    public function delete(int $id)
    {

        $statement = $this->pdo->prepare('Delete From posts Where id = ?');
        return $statement->execute([$id]);
    }

    private function buildfieldquery(array $params)
    {
        return join(', ', array_map(function ($field) {
            return "$field = :$field";
        }, array_keys($params)));
    }
}
