<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserBank
 *
 * @ORM\Table(name="user_bank")
 * @ORM\Entity
 */
class UserBank
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
     * @ORM\Column(name="mobile", type="string", length=35, nullable=false)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="id_number", type="string", length=20, nullable=false)
     */
    private $idNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="id_name", type="string", length=60, nullable=false)
     */
    private $idName;

    /**
     * @var integer
     *
     * @ORM\Column(name="bank_number", type="bigint", nullable=false)
     */
    private $bankNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="bank_name", type="string", length=300, nullable=false)
     */
    private $bankName;

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
