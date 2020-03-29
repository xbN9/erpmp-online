<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * AdminPermissions
 *
 * @ORM\Table(name="admin_permissions")
 * @ORM\Entity
 */
class AdminPermissions
{
    /**
     * @var integer
     *
     * @ORM\Column(name="permissions_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $permissionsId;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=35, nullable=false)
     */
    private $path;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=35, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="smallint", nullable=false)
     */
    private $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="self_group", type="boolean", nullable=false)
     */
    private $selfGroup;


}
