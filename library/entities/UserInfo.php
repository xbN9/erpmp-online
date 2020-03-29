<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserInfo
 *
 * @ORM\Table(name="user_info", uniqueConstraints={@ORM\UniqueConstraint(name="mobile", columns={"mobile"})}, indexes={@ORM\Index(name="device_id", columns={"status"})})
 * @ORM\Entity
 */
class UserInfo
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=50, nullable=false)
     */
    private $mobile;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="create_time", type="integer", nullable=false)
     */
    private $createTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="update_time", type="integer", nullable=false)
     */
    private $updateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="photo", type="string", length=120, nullable=false)
     */
    private $photo;

    /**
     * @var string
     *
     * @ORM\Column(name="invitation_code", type="string", length=33, nullable=false)
     */
    private $invitationCode;

    /**
     * @var string
     *
     * @ORM\Column(name="id_number", type="string", length=18, nullable=false)
     */
    private $idNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="id_name", type="string", length=60, nullable=false)
     */
    private $idName;


}
