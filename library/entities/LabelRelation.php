<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * LabelRelation
 *
 * @ORM\Table(name="label_relation")
 * @ORM\Entity
 */
class LabelRelation
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
     * @ORM\Column(name="lable_id", type="integer", nullable=false)
     */
    private $lableId;

    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var integer
     *
     * @ORM\Column(name="goods_id", type="integer", nullable=false)
     */
    private $goodsId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;


}
