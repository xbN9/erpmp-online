<?php
use Doctrine\ORM\Mapping as ORM;

/**
 * Cart
 *
 * @ORM\Table(name="cart", uniqueConstraints={@ORM\UniqueConstraint(name="goods_id", columns={"goods_id", "status", "device_id", "create_time"})})
 * @ORM\Entity
 */
class Cart
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
     * @ORM\Column(name="uid", type="integer", nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="device_id", type="string", length=100, nullable=false)
     */
    private $deviceId;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_id", type="string", length=25, nullable=false)
     */
    private $goodsId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_id", type="string", length=30, nullable=false)
     */
    private $productId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="goods_num", type="integer", nullable=false)
     */
    private $goodsNum;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_name", type="string", length=200, nullable=false)
     */
    private $goodsName;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $goodsPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="goods_activity_id", type="integer", nullable=false)
     */
    private $goodsActivityId;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_img", type="string", length=45, nullable=false)
     */
    private $goodsImg;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_ext", type="text", length=65535, nullable=false)
     */
    private $goodsExt;

    /**
     * @var integer
     *
     * @ORM\Column(name="goods_stock", type="integer", nullable=false)
     */
    private $goodsStock;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_coupon", type="string", length=25, nullable=false)
     */
    private $goodsCoupon;

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
     * @var string
     *
     * @ORM\Column(name="share_code", type="string", length=60, nullable=false)
     */
    private $shareCode;
}
