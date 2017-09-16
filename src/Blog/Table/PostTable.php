<?php

    namespace App\Blog\Table;

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
         * @return array
         */
    public function findPaginated(): array
    {
        return $this->pdo->query('Select * From posts  Order By created_at Desc Limit 10')
            ->fetchAll();
    }

    /**
         * trouve un article pas l'id
         */
    public function find(int $id)
    {
        $query = $this->pdo
            ->prepare('Select * From posts Where id = ?');
        $query->execute([$id]);
        return $query->fetch();
    }
}
