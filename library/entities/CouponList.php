<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * CouponList
 *
 * @ORM\Table(name="coupon_list")
 * @ORM\Entity
 */
class CouponList
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
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", nullable=false)
     */
    private $uid;

    /**
     * @var string
     *
     * @ORM\Column(name="device_id", type="string", length=60, nullable=false)
     */
    private $deviceId;

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

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=false)
     */
    private $updateTime;


}
