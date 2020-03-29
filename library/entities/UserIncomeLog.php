<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserIncomeLog
 *
 * @ORM\Table(name="user_income_log")
 * @ORM\Entity
 */
class UserIncomeLog
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
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
     * @var integer
     *
     * @ORM\Column(name="order_sn", type="bigint", nullable=false)
     */
    private $orderSn;

    /**
     * @var string
     *
     * @ORM\Column(name="income", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $income;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_settlement", type="boolean", nullable=false)
     */
    private $isSettlement;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_cnt", type="boolean", nullable=false)
     */
    private $isCnt;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_record", type="boolean", nullable=false)
     */
    private $isRecord;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_balance", type="boolean", nullable=false)
     */
    private $isBalance;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=false)
     */
    private $updateTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sign_time", type="datetime", nullable=false)
     */
    private $signTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="confirm_time", type="datetime", nullable=false)
     */
    private $confirmTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="balance_time", type="datetime", nullable=false)
     */
    private $balanceTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="record_time", type="datetime", nullable=false)
     */
    private $recordTime;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=30, nullable=false)
     */
    private $ext;


}
