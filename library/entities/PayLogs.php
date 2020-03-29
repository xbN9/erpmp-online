<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * PayLogs
 *
 * @ORM\Table(name="pay_logs", indexes={@ORM\Index(name="uid_addtime", columns={"uid", "addtime"})})
 * @ORM\Entity
 */
class PayLogs
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
     * @ORM\Column(name="uid", type="bigint", nullable=false)
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
     * @var integer
     *
     * @ORM\Column(name="operator", type="bigint", nullable=false)
     */
    private $operator;

    /**
     * @var boolean
     *
     * @ORM\Column(name="action", type="boolean", nullable=false)
     */
    private $action;

    /**
     * @var string
     *
     * @ORM\Column(name="pay_price", type="decimal", precision=16, scale=2, nullable=false)
     */
    private $payPrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="addtime", type="datetime", nullable=false)
     */
    private $addtime;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=500, nullable=false)
     */
    private $ext;


}
