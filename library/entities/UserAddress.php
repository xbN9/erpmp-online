<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserAddress
 *
 * @ORM\Table(name="user_address", indexes={@ORM\Index(name="id", columns={"uid"}), @ORM\Index(name="status", columns={"status"}), @ORM\Index(name="uuid", columns={"uuid"})})
 * @ORM\Entity
 */
class UserAddress
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
     * @ORM\Column(name="uuid", type="string", length=100, nullable=false)
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=20, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="mobile", type="string", length=20, nullable=false)
     */
    private $mobile;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=15, nullable=false)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=15, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="district", type="string", length=25, nullable=false)
     */
    private $district;

    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=50, nullable=false)
     */
    private $street;

    /**
     * @var boolean
     *
     * @ORM\Column(name="default_address", type="boolean", nullable=false)
     */
    private $defaultAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="id_number", type="string", length=18, nullable=false)
     */
    private $idNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="real_name", type="string", length=60, nullable=false)
     */
    private $realName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_auth", type="boolean", nullable=false)
     */
    private $isAuth;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;


}
