<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * NotifyLog
 *
 * @ORM\Table(name="notify_log")
 * @ORM\Entity
 */
class NotifyLog
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
     * @ORM\Column(name="trade_no", type="string", length=35, nullable=false)
     */
    private $tradeNo;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notify_status", type="boolean", nullable=false)
     */
    private $notifyStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="notify_channel", type="boolean", nullable=false)
     */
    private $notifyChannel;

    /**
     * @var boolean
     *
     * @ORM\Column(name="log_status", type="boolean", nullable=false)
     */
    private $logStatus;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;


}
