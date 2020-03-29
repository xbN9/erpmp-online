<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * CouponShop
 *
 * @ORM\Table(name="coupon_shop", uniqueConstraints={@ORM\UniqueConstraint(name="coupon_id", columns={"coupon_id"})})
 * @ORM\Entity
 */
class CouponShop
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
     * @ORM\Column(name="coupon_id", type="bigint", nullable=false)
     */
    private $couponId;

    /**
     * @var integer
     *
     * @ORM\Column(name="batch_id", type="bigint", nullable=false)
     */
    private $batchId;

    /**
     * @var string
     *
     * @ORM\Column(name="sid", type="string", length=33, nullable=false)
     */
    private $sid;

    /**
     * @var integer
     *
     * @ORM\Column(name="num", type="integer", nullable=false)
     */
    private $num;

    /**
     * @var integer
     *
     * @ORM\Column(name="use_num", type="integer", nullable=false)
     */
    private $useNum;


}
