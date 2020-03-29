<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * WarehouseSupplier
 *
 * @ORM\Table(name="warehouse_supplier")
 * @ORM\Entity
 */
class WarehouseSupplier
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
     * @ORM\Column(name="name", type="string", length=35, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="the_contact", type="string", length=35, nullable=false)
     */
    private $theContact;

    /**
     * @var string
     *
     * @ORM\Column(name="account", type="string", length=50, nullable=false)
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=50, nullable=false)
     */
    private $mobile;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;


}
