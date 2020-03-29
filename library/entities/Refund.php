<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Refund
 *
 * @ORM\Table(name="refund", indexes={@ORM\Index(name="order_sn", columns={"order_sn"})})
 * @ORM\Entity
 */
class Refund
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
     * @ORM\Column(name="refund_info_id", type="integer", nullable=false)
     */
    private $refundInfoId;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_sn", type="bigint", nullable=false)
     */
    private $orderSn;

    /**
     * @var integer
     *
     * @ORM\Column(name="goods_id", type="integer", nullable=false)
     */
    private $goodsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="goods_num", type="integer", nullable=false)
     */
    private $goodsNum;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_refund", type="boolean", nullable=false)
     */
    private $isRefund;

    /**
     * @var string
     *
     * @ORM\Column(name="refund_money", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $refundMoney;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sales_return", type="boolean", nullable=false)
     */
    private $isSalesReturn;

    /**
     * @var string
     *
     * @ORM\Column(name="applicant_user", type="string", length=25, nullable=false)
     */
    private $applicantUser;

    /**
     * @var string
     *
     * @ORM\Column(name="reviewer_user", type="string", length=25, nullable=false)
     */
    private $reviewerUser;

    /**
     * @var string
     *
     * @ORM\Column(name="note", type="string", length=255, nullable=false)
     */
    private $note;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="refund_time", type="datetime", nullable=false)
     */
    private $refundTime;


}
