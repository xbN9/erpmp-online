<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * GoodsRelation
 *
 * @ORM\Table(name="goods_relation", indexes={@ORM\Index(name="goods_id", columns={"good_id", "status"})})
 * @ORM\Entity
 */
class GoodsRelation
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
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var integer
     *
     * @ORM\Column(name="good_id", type="integer", nullable=false)
     */
    private $goodId;

    /**
     * @var string
     *
     * @ORM\Column(name="k", type="string", length=15, nullable=false)
     */
    private $k;

    /**
     * @var string
     *
     * @ORM\Column(name="v", type="string", length=50, nullable=false)
     */
    private $v;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer", nullable=false)
     */
    private $stock;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;


}
