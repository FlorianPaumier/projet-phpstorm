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
    public function find(int $id): Post
    {
        $query = $this->pdo
            ->prepare('Select * From posts Where id = ?');
        $query->execute([$id]);
        $query->setFetchMode(\PDO::FETCH_CLASS, Post::class);
        return $query->fetch();
    }
}
