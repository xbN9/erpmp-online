<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * GoodsActivity
 *
 * @ORM\Table(name="goods_activity")
 * @ORM\Entity
 */
class GoodsActivity
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
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $goodsPrice;

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

    /**
     * @var integer
     *
     * @ORM\Column(name="activity_id", type="integer", nullable=false)
     */
    private $activityId;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer", nullable=false)
     */
    private $stock;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;


}
