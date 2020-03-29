<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * StoreWaiter
 *
 * @ORM\Table(name="store_waiter", indexes={@ORM\Index(name="store_id", columns={"store_id", "waiter_id"})})
 * @ORM\Entity
 */
class StoreWaiter
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
     * @ORM\Column(name="store_id", type="integer", nullable=false)
     */
    private $storeId;

    /**
     * @var integer
     *
     * @ORM\Column(name="waiter_id", type="integer", nullable=false)
     */
    private $waiterId;

    /**
     * @var string
     *
     * @ORM\Column(name="waiter_name", type="string", length=30, nullable=false)
     */
    private $waiterName;

    /**
     * @var string
     *
     * @ORM\Column(name="avator", type="string", length=100, nullable=false)
     */
    private $avator;

    /**
     * @var string
     *
     * @ORM\Column(name="store_product_id", type="string", length=15, nullable=false)
     */
    private $storeProductId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show", type="boolean", nullable=false)
     */
    private $isShow;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;


}
