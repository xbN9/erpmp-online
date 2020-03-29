<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * CommisionConf
 *
 * @ORM\Table(name="commision_conf")
 * @ORM\Entity
 */
class CommisionConf
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
     * @ORM\Column(name="k", type="string", length=50, nullable=false)
     */
    private $k;

    /**
     * @var string
     *
     * @ORM\Column(name="v", type="string", length=20, nullable=false)
     */
    private $v;

    /**
     * @var string
     *
     * @ORM\Column(name="d", type="string", length=50, nullable=false)
     */
    private $d;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_time", type="datetime", nullable=false)
     */
    private $updateTime;

    /**
     * @var string
     *
     * @ORM\Column(name="update_user", type="string", length=25, nullable=false)
     */
    private $updateUser;


}
