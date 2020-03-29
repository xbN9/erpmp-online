<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * RefundInfo
 *
 * @ORM\Table(name="refund_info", indexes={@ORM\Index(name="order_sn", columns={"order_sn"})})
 * @ORM\Entity
 */
class RefundInfo
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
     * @var string
     *
     * @ORM\Column(name="out_refund_no", type="string", length=65, nullable=false)
     */
    private $outRefundNo;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_sn", type="bigint", nullable=false)
     */
    private $orderSn;

    /**
     * @var string
     *
     * @ORM\Column(name="transaction_id", type="string", length=35, nullable=false)
     */
    private $transactionId;

    /**
     * @var string
     *
     * @ORM\Column(name="refund_id", type="string", length=35, nullable=false)
     */
    private $refundId;

    /**
     * @var string
     *
     * @ORM\Column(name="total_fee", type="decimal", precision=18, scale=2, nullable=false)
     */
    private $totalFee;

    /**
     * @var string
     *
     * @ORM\Column(name="settlement_total_fee", type="decimal", precision=18, scale=2, nullable=false)
     */
    private $settlementTotalFee;

    /**
     * @var string
     *
     * @ORM\Column(name="refund_fee", type="decimal", precision=18, scale=2, nullable=false)
     */
    private $refundFee;

    /**
     * @var string
     *
     * @ORM\Column(name="settlement_refund_fee", type="decimal", precision=18, scale=2, nullable=false)
     */
    private $settlementRefundFee;

    /**
     * @var boolean
     *
     * @ORM\Column(name="refund_status", type="boolean", nullable=false)
     */
    private $refundStatus;

    /**
     * @var string
     *
     * @ORM\Column(name="refund_recv_accout", type="string", length=80, nullable=false)
     */
    private $refundRecvAccout;

    /**
     * @var string
     *
     * @ORM\Column(name="refund_account", type="string", length=35, nullable=false)
     */
    private $refundAccount;

    /**
     * @var string
     *
     * @ORM\Column(name="refund_request_source", type="string", length=35, nullable=false)
     */
    private $refundRequestSource;

    /**
     * @var boolean
     *
     * @ORM\Column(name="refund_channel", type="boolean", nullable=false)
     */
    private $refundChannel;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=255, nullable=false)
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="refund_time", type="datetime", nullable=false)
     */
    private $refundTime;


}
