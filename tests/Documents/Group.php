<?php

namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\ClassMetadata;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document(db="doctrine_odm_tests", collection="groups") */
class Group
{
    /** @ODM\Id */
    private $id;

    /** @ODM\Field */
    private $name;

    public function __construct($name = null)
    {
        $this->name = $name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}