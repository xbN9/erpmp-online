<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * WarehouseProduct
 *
 * @ORM\Table(name="warehouse_product")
 * @ORM\Entity
 */
class WarehouseProduct
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
     * @ORM\Column(name="warehouse_id", type="integer", nullable=false)
     */
    private $warehouseId;

    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=200, nullable=false)
     */
    private $productName;

    /**
     * @var string
     *
     * @ORM\Column(name="product_barcode", type="string", length=50, nullable=false)
     */
    private $productBarcode;

    /**
     * @var integer
     *
     * @ORM\Column(name="totalstock", type="integer", nullable=false)
     */
    private $totalstock;

    /**
     * @var integer
     *
     * @ORM\Column(name="stock", type="integer", nullable=false)
     */
    private $stock;

    /**
     * @var integer
     *
     * @ORM\Column(name="lockstock", type="integer", nullable=false)
     */
    private $lockstock;

    /**
     * @var integer
     *
     * @ORM\Column(name="defectivestock", type="integer", nullable=false)
     */
    private $defectivestock;

    /**
     * @var integer
     *
     * @ORM\Column(name="lockdefectivestock", type="integer", nullable=false)
     */
    private $lockdefectivestock;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sync", type="boolean", nullable=false)
     */
    private $isSync;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;


}
