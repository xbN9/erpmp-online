<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Goods
 *
 * @ORM\Table(name="goods", indexes={@ORM\Index(name="status", columns={"status"}), @ORM\Index(name="product_id", columns={"product_id", "status"}), @ORM\Index(name="show_id", columns={"show_id", "status"})})
 * @ORM\Entity
 */
class Goods
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
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(name="suit_num", type="smallint", nullable=false)
     */
    private $suitNum;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer", nullable=false)
     */
    private $categoryId;

    /**
     * @var integer
     *
     * @ORM\Column(name="brand_id", type="integer", nullable=false)
     */
    private $brandId;

    /**
     * @var string
     *
     * @ORM\Column(name="unit", type="string", length=1, nullable=false)
     */
    private $unit;

    /**
     * @var string
     *
     * @ORM\Column(name="origin", type="string", length=50, nullable=false)
     */
    private $origin;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", nullable=false)
     */
    private $countryId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_will_buy", type="boolean", nullable=false)
     */
    private $isWillBuy;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_shui", type="boolean", nullable=false)
     */
    private $isShui;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var boolean
     *
     * @ORM\Column(name="ready_sale", type="boolean", nullable=false)
     */
    private $readySale;

    /**
     * @var string
     *
     * @ORM\Column(name="market_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $marketPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $salePrice;

    /**
     * @var string
     *
     * @ORM\Column(name="reduce_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $reducePrice;

    /**
     * @var string
     *
     * @ORM\Column(name="refer_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $referPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="base_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $basePrice;

    /**
     * @var string
     *
     * @ORM\Column(name="commission", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $commission;

    /**
     * @var string
     *
     * @ORM\Column(name="fees", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $fees;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer", nullable=false)
     */
    private $stock;

    /**
     * @var integer
     *
     * @ORM\Column(name="total_sale_amount", type="integer", nullable=false)
     */
    private $totalSaleAmount;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_outlets", type="boolean", nullable=false)
     */
    private $isOutlets;

    /**
     * @var string
     *
     * @ORM\Column(name="shelf_life", type="string", length=20, nullable=false)
     */
    private $shelfLife;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="sale_time", type="datetime", nullable=false)
     */
    private $saleTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_return", type="boolean", nullable=false)
     */
    private $isReturn;

    /**
     * @var boolean
     *
     * @ORM\Column(name="warehouse_type", type="boolean", nullable=false)
     */
    private $warehouseType;

    /**
     * @var string
     *
     * @ORM\Column(name="img", type="string", length=50, nullable=false)
     */
    private $img;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_master_img", type="boolean", nullable=false)
     */
    private $isMasterImg;

    /**
     * @var string
     *
     * @ORM\Column(name="vedio", type="string", length=50, nullable=false)
     */
    private $vedio;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_master_vedio", type="boolean", nullable=false)
     */
    private $isMasterVedio;

    /**
     * @var string
     *
     * @ORM\Column(name="show_id", type="string", length=25, nullable=false)
     */
    private $showId;

    /**
     * @var integer
     *
     * @ORM\Column(name="warehouse_id", type="integer", nullable=false)
     */
    private $warehouseId;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_time", type="datetime", nullable=false)
     */
    private $createdTime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="modify_time", type="datetime", nullable=false)
     */
    private $modifyTime;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;


}
