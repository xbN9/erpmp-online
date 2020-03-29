<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserShopLevel
 *
 * @ORM\Table(name="user_shop_level")
 * @ORM\Entity
 */
class UserShopLevel
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
     * @ORM\Column(name="level_name", type="string", length=20, nullable=false)
     */
    private $levelName;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank_weight", type="smallint", nullable=false)
     */
    private $rankWeight;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;


}
