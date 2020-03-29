<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserFudaiIncomeLog
 *
 * @ORM\Table(name="user_fudai_income_log")
 * @ORM\Entity
 */
class UserFudaiIncomeLog
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
     * @var integer
     *
     * @ORM\Column(name="superior_id", type="integer", nullable=false)
     */
    private $superiorId;

    /**
     * @var integer
     *
     * @ORM\Column(name="source_id", type="integer", nullable=false)
     */
    private $sourceId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_settlement", type="boolean", nullable=false)
     */
    private $isSettlement;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_sn", type="bigint", nullable=false)
     */
    private $orderSn;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $salePrice;

    /**
     * @var string
     *
     * @ORM\Column(name="income", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $income;

    /**
     * @var string
     *
     * @ORM\Column(name="f_income", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $fIncome;

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
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=100, nullable=false)
     */
    private $ext;


}
