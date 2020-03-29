<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Navtag
 *
 * @ORM\Table(name="navtag")
 * @ORM\Entity
 */
class Navtag
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=25, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="create_user", type="string", length=25, nullable=false)
     */
    private $createUser;

    /**
     * @var string
     *
     * @ORM\Column(name="update_user", type="string", length=25, nullable=false)
     */
    private $updateUser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=false)
     */
    private $sort;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=false)
     */
    private $updateTime;


}
