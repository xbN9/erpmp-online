<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserIncomeEstimate
 *
 * @ORM\Table(name="user_income_estimate")
 * @ORM\Entity
 */
class UserIncomeEstimate
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
     * @var \DateTime
     *
     * @ORM\Column(name="income_time", type="datetime", nullable=false)
     */
    private $incomeTime;


}
