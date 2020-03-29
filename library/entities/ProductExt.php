<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * ProductExt
 *
 * @ORM\Table(name="product_ext")
 * @ORM\Entity
 */
class ProductExt
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
     * @var string
     *
     * @ORM\Column(name="image", type="text", length=16777215, nullable=false)
     */
    private $image;


}
