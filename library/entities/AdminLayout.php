<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * AdminLayout
 *
 * @ORM\Table(name="admin_layout")
 * @ORM\Entity
 */
class AdminLayout
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
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", length=16777215, nullable=false)
     */
    private $content;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=true)
     */
    private $status;


}
