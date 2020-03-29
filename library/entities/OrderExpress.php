<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * OrderExpress
 *
 * @ORM\Table(name="order_express", indexes={@ORM\Index(name="order_sn", columns={"order_sn", "goods_id", "goods_num"})})
 * @ORM\Entity
 */
class OrderExpress
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
     * @ORM\Column(name="order_express_sn", type="string", length=25, nullable=false)
     */
    private $orderExpressSn;

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
     * @var string
     *
     * @ORM\Column(name="express_sn", type="string", length=40, nullable=false)
     */
    private $expressSn;

    /**
     * @var string
     *
     * @ORM\Column(name="express_third", type="string", length=100, nullable=false)
     */
    private $expressThird;

    /**
     * @var string
     *
     * @ORM\Column(name="express_name", type="string", length=15, nullable=false)
     */
    private $expressName;

    /**
     * @var string
     *
     * @ORM\Column(name="express_info", type="string", length=350, nullable=false)
     */
    private $expressInfo;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee_id", type="string", length=18, nullable=false)
     */
    private $consigneeId;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee_realname", type="string", length=60, nullable=false)
     */
    private $consigneeRealname;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee_name", type="string", length=60, nullable=false)
     */
    private $consigneeName;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee_phone", type="string", length=15, nullable=false)
     */
    private $consigneePhone;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee_phone2", type="string", length=15, nullable=false)
     */
    private $consigneePhone2;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee_address", type="string", length=250, nullable=false)
     */
    private $consigneeAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee_zip", type="string", length=15, nullable=false)
     */
    private $consigneeZip;

    /**
     * @var string
     *
     * @ORM\Column(name="consignee_fee", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $consigneeFee;

    /**
     * @var boolean
     *
     * @ORM\Column(name="consignee_status", type="boolean", nullable=false)
     */
    private $consigneeStatus;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_settlement", type="boolean", nullable=false)
     */
    private $isSettlement;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_subscribe", type="boolean", nullable=false)
     */
    private $isSubscribe;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_notice", type="boolean", nullable=false)
     */
    private $isNotice;

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
     * @ORM\Column(name="update_user", type="string", length=25, nullable=false)
     */
    private $updateUser;


}
