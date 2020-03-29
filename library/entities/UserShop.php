<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserShop
 *
 * @ORM\Table(name="user_shop", indexes={@ORM\Index(name="uid", columns={"uid"})})
 * @ORM\Entity
 */
class UserShop
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
     * @ORM\Column(name="name", type="string", length=50, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="picture", type="string", length=120, nullable=false)
     */
    private $picture;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="level_id", type="integer", nullable=false)
     */
    private $levelId;

    /**
     * @var string
     *
     * @ORM\Column(name="level_name", type="string", length=20, nullable=false)
     */
    private $levelName;

    /**
     * @var string
     *
     * @ORM\Column(name="share_shop_code", type="string", length=35, nullable=false)
     */
    private $shareShopCode;

    /**
     * @var string
     *
     * @ORM\Column(name="share_gift_code", type="string", length=35, nullable=false)
     */
    private $shareGiftCode;

    /**
     * @var string
     *
     * @ORM\Column(name="balance", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $balance;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=120, nullable=false)
     */
    private $img;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=false)
     */
    private $updateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=35, nullable=false)
     */
    private $ext;

    /**
     * @var string
     *
     * @ORM\Column(name="tx_money", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $txMoney;


}
