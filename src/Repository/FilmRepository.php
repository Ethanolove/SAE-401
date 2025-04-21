<?php

// src/Repository/FilmRepository.php
namespace Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;


class FilmRepository extends EntityRepository{
    public function getAllGenres(){
        $rsm=new ResultSetMapping();
        $rsm->addScalarResult('genre','genre');
        $sql="SELECT DISTINCT genre FROM Film";
        $query=$this->getEntityManager()->createNativeQuery($sql,$rsm);
        return $query->getResult();
    }
    public function getListOfFilmByGenres($genre) {
        $qb = $this->getEntityManager()->createQuery("SELECT f FROM Entity\Film f WHERE f.genre = :genre");
        $qb->setParameter('genre', $genre);
        return $qb->getResult();
    }
    public function searchListOfFilmsByTitle($title){
        $qb=$this->getEntityManager()->createQuery("SELECT f FROM Film f WHERE f.titre LIKE :title");
        $qb->setParameter('title','%'.$title.'%');
        return $qb->getResult();
    }
}


?>