<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserTxLog
 *
 * @ORM\Table(name="user_tx_log")
 * @ORM\Entity
 */
class UserTxLog
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
     * @ORM\Column(name="account", type="string", length=50, nullable=false)
     */
    private $account;

    /**
     * @var string
     *
     * @ORM\Column(name="money", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $money;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

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
