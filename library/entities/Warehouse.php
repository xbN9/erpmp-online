<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Warehouse
 *
 * @ORM\Table(name="warehouse", indexes={@ORM\Index(name="index3", columns={"storehouse_sn"})})
 * @ORM\Entity
 */
class Warehouse
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
     * @var integer
     *
     * @ORM\Column(name="warehouse_sup_id", type="integer", nullable=false)
     */
    private $warehouseSupId;

    /**
     * @var string
     *
     * @ORM\Column(name="storehouse_sn", type="string", length=20, nullable=false)
     */
    private $storehouseSn;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=35, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="provinces", type="string", length=20, nullable=false)
     */
    private $provinces;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=20, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="areas", type="string", length=20, nullable=false)
     */
    private $areas;

    /**
     * @var string
     *
     * @ORM\Column(name="export_address", type="string", length=35, nullable=false)
     */
    private $exportAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="the_contact", type="string", length=35, nullable=false)
     */
    private $theContact;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=50, nullable=false)
     */
    private $mobile;


}
