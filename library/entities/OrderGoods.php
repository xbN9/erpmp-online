<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * OrderGoods
 *
 * @ORM\Table(name="order_goods", indexes={@ORM\Index(name="order_sn", columns={"order_sn", "goods_id", "goods_num"})})
 * @ORM\Entity
 */
class OrderGoods
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
     * @ORM\Column(name="order_sn", type="bigint", nullable=false)
     */
    private $orderSn;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_id", type="string", length=25, nullable=false)
     */
    private $goodsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="goods_num", type="integer", nullable=false)
     */
    private $goodsNum;

    /**
     * @var string
     *
     * @ORM\Column(name="goods_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $goodsPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="coupon", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $coupon;

    /**
     * @var string
     *
     * @ORM\Column(name="balance", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $balance;

    /**
     * @var string
     *
     * @ORM\Column(name="commission", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $commission;

    /**
     * @var string
     *
     * @ORM\Column(name="fees", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $fees;


}
