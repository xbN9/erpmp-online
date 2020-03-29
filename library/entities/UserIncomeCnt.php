<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserIncomeCnt
 *
 * @ORM\Table(name="user_income_cnt")
 * @ORM\Entity
 */
class UserIncomeCnt
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
     * @ORM\Column(name="order_sn_p_cnt", type="integer", nullable=false)
     */
    private $orderSnPCnt;

    /**
     * @var integer
     *
     * @ORM\Column(name="order_sn_t_cnt", type="integer", nullable=false)
     */
    private $orderSnTCnt;

    /**
     * @var string
     *
     * @ORM\Column(name="p_sale", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $pSale;

    /**
     * @var string
     *
     * @ORM\Column(name="t_sale", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $tSale;

    /**
     * @var boolean
     *
     * @ORM\Column(name="p_pass", type="boolean", nullable=false)
     */
    private $pPass;

    /**
     * @var boolean
     *
     * @ORM\Column(name="t_pass", type="boolean", nullable=false)
     */
    private $tPass;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=30, nullable=false)
     */
    private $ext;


}
