<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Pay
 *
 * @ORM\Table(name="pay", indexes={@ORM\Index(name="uid_pay_status", columns={"uid", "pay_status"}), @ORM\Index(name="uid_createtime", columns={"uid", "createtime"})})
 * @ORM\Entity
 */
class Pay
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
     * @ORM\Column(name="device_id", type="string", length=60, nullable=false)
     */
    private $deviceId;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_sn", type="bigint", nullable=false)
     */
    private $orderSn;

    /**
     * @var string
     *
     * @ORM\Column(name="pay_price", type="decimal", precision=16, scale=2, nullable=false)
     */
    private $payPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="third_trade_no", type="string", length=100, nullable=false)
     */
    private $thirdTradeNo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pay_status", type="boolean", nullable=false)
     */
    private $payStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="pay_channel", type="boolean", nullable=false)
     */
    private $payChannel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notice_type", type="boolean", nullable=false)
     */
    private $noticeType;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createtime", type="datetime", nullable=false)
     */
    private $createtime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="pay_time", type="datetime", nullable=false)
     */
    private $payTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="notice_time", type="datetime", nullable=false)
     */
    private $noticeTime;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=500, nullable=false)
     */
    private $ext;

    /**
     * @var string
     *
     * @ORM\Column(name="pay_id", type="string", length=32, nullable=false)
     */
    private $payId;


}
