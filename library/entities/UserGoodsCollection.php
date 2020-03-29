<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * UserGoodsCollection
 *
 * @ORM\Table(name="user_goods_collection", indexes={@ORM\Index(name="uid", columns={"uid"}), @ORM\Index(name="goods_id", columns={"goods_id"}), @ORM\Index(name="status", columns={"status"})})
 * @ORM\Entity
 */
class UserGoodsCollection
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
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="goods_id", type="integer", nullable=false)
     */
    private $goodsId;

    /**
     * @var integer
     *
     * @ORM\Column(name="create_time", type="integer", nullable=false)
     */
    private $createTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;


}
