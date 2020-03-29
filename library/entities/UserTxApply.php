<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserTxApply
 *
 * @ORM\Table(name="user_tx_apply")
 * @ORM\Entity
 */
class UserTxApply
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
     * @ORM\Column(name="user_bank_id", type="integer", nullable=false)
     */
    private $userBankId;

    /**
     * @var string
     *
     * @ORM\Column(name="money", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $money;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_remit", type="boolean", nullable=false)
     */
    private $isRemit;

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
