<?php

namespace Framework\Database;

use \Pagerfanta\Adapter\AdapterInterface;

class paginatedQuery implements AdapterInterface
{

    /**
     * @var \PDO
     */
    private $pdo;
    /**
     * @var
     */
    private $query;
    /**
     * @var
     */
    private $countQuery;
    /**
     * @var string
     */
    private $entity;

    /**
     * paginatedQuery constructor.
     * @param \PDO $pdo
     * @param $query requete de recupération de x resultat
     * @param $countQuery nbtotal de resultat de la requête
     */
    public function __construct(\PDO $pdo, $query, $countQuery, string $entity)
    {
        $this->pdo = $pdo;
        $this->query = $query;
        $this->countQuery = $countQuery;
        $this->entity = $entity;
    }

    /**
     * Returns the number of results.
     *
     * @return integer The number of results.
     */
    public function getNbResults() : int
    {
        return $this->pdo->query($this->countQuery)->fetchColumn();
    }

    /**
     * Returns an slice of the results.
     *
     * @param integer $offset The offset.
     * @param integer $length The length.
     *
     * @return array|\Traversable The slice.
     */
    public function getSlice($offset, $length) : array
    {
        $offset = (int) $offset;
        $length = (int) $length;

        $statement = $this->pdo->prepare($this->query . ' Limit :offset, :length');
        $statement->bindParam('offset', $offset, \PDO::PARAM_INT);
        $statement->bindParam('length', $length, \PDO::PARAM_INT);
        $statement->setFetchMode(\PDO::FETCH_CLASS, $this->entity);
        $statement->execute();
        return $statement->fetchAll();
    }
}
