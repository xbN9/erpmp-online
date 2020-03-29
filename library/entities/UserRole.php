<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserRole
 *
 * @ORM\Table(name="user_role")
 * @ORM\Entity
 */
class UserRole
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
     * @ORM\Column(name="ordersn", type="bigint", nullable=false)
     */
    private $ordersn;

    /**
     * @var integer
     *
     * @ORM\Column(name="level_id", type="integer", nullable=false)
     */
    private $levelId;

    /**
     * @var string
     *
     * @ORM\Column(name="commission", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $commission;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=false)
     */
    private $updateTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="role_start", type="datetime", nullable=false)
     */
    private $roleStart;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="rolle_stop", type="datetime", nullable=false)
     */
    private $rolleStop;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;


}
