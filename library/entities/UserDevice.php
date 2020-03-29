<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserDevice
 *
 * @ORM\Table(name="user_device", uniqueConstraints={@ORM\UniqueConstraint(name="device_id", columns={"device_id", "token"})})
 * @ORM\Entity
 */
class UserDevice
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
     * @ORM\Column(name="device_id", type="string", length=33, nullable=false)
     */
    private $deviceId;

    /**
     * @var string
     *
     * @ORM\Column(name="openid", type="string", length=200, nullable=false)
     */
    private $openid;

    /**
     * @var string
     *
     * @ORM\Column(name="unionid", type="string", length=200, nullable=false)
     */
    private $unionid;

    /**
     * @var string
     *
     * @ORM\Column(name="token", type="string", length=100, nullable=false)
     */
    private $token;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_app", type="boolean", nullable=false)
     */
    private $isApp;

    /**
     * @var string
     *
     * @ORM\Column(name="device_agent", type="string", length=50, nullable=false)
     */
    private $deviceAgent;

    /**
     * @var string
     *
     * @ORM\Column(name="os_version", type="string", length=20, nullable=false)
     */
    private $osVersion;

    /**
     * @var string
     *
     * @ORM\Column(name="software_version", type="string", length=10, nullable=false)
     */
    private $softwareVersion;

    /**
     * @var string
     *
     * @ORM\Column(name="referer_channel", type="string", length=40, nullable=false)
     */
    private $refererChannel;

    /**
     * @var string
     *
     * @ORM\Column(name="device_name", type="string", length=30, nullable=false)
     */
    private $deviceName;

    /**
     * @var string
     *
     * @ORM\Column(name="app_platform", type="string", length=11, nullable=false)
     */
    private $appPlatform;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createtime", type="datetime", nullable=false)
     */
    private $createtime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatetime", type="datetime", nullable=false)
     */
    private $updatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start_time", type="datetime", nullable=false)
     */
    private $startTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end_time", type="datetime", nullable=false)
     */
    private $endTime;

    /**
     * @var string
     *
     * @ORM\Column(name="ext", type="string", length=100, nullable=false)
     */
    private $ext;

    /**
     * @var string
     *
     * @ORM\Column(name="wx_name", type="string", length=100, nullable=false)
     */
    private $wxName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="wx_sex", type="boolean", nullable=false)
     */
    private $wxSex;

    /**
     * @var string
     *
     * @ORM\Column(name="wx_avator", type="string", length=300, nullable=false)
     */
    private $wxAvator;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=50, nullable=false)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="province", type="string", length=50, nullable=false)
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=50, nullable=false)
     */
    private $country;


}
