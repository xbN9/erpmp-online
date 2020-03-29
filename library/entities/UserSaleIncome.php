<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserSaleIncome
 *
 * @ORM\Table(name="user_sale_income")
 * @ORM\Entity
 */
class UserSaleIncome
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
     * @ORM\Column(name="income", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $income;

    /**
     * @var boolean
     *
     * @ORM\Column(name="times", type="boolean", nullable=false)
     */
    private $times;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=200, nullable=false)
     */
    private $ext;


}
