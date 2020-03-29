<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserVisitLog
 *
 * @ORM\Table(name="user_visit_log")
 * @ORM\Entity
 */
class UserVisitLog
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
     * @ORM\Column(name="unionid", type="string", length=50, nullable=false)
     */
    private $unionid;

    /**
     * @var string
     *
     * @ORM\Column(name="share_code", type="string", length=80, nullable=false)
     */
    private $shareCode;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=300, nullable=false)
     */
    private $name;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sex", type="boolean", nullable=false)
     */
    private $sex;

    /**
     * @var string
     *
     * @ORM\Column(name="avatar", type="string", length=250, nullable=false)
     */
    private $avatar;

    /**
     * @var string
     *
     * @ORM\Column(name="language", type="string", length=20, nullable=false)
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=20, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=20, nullable=false)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=20, nullable=false)
     */
    private $country;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="uid", type="integer", nullable=false)
     */
    private $uid;


}
