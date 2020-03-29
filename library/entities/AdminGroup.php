<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * AdminGroup
 *
 * @ORM\Table(name="admin_group")
 * @ORM\Entity
 */
class AdminGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="group_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $groupId;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="permissions", type="text", length=65535, nullable=true)
     */
    private $permissions;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;


}
