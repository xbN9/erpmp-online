<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * StoreProduct
 *
 * @ORM\Table(name="store_product")
 * @ORM\Entity
 */
class StoreProduct
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
     * @ORM\Column(name="store_id", type="integer", nullable=false)
     */
    private $storeId;

    /**
     * @var string
     *
     * @ORM\Column(name="service_name", type="string", length=35, nullable=true)
     */
    private $serviceName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var string
     *
     * @ORM\Column(name="book_time", type="string", length=150, nullable=false)
     */
    private $bookTime;

    /**
     * @var integer
     *
     * @ORM\Column(name="favorite_cnt", type="integer", nullable=false)
     */
    private $favoriteCnt;

    /**
     * @var string
     *
     * @ORM\Column(name="show_id", type="string", length=64, nullable=false)
     */
    private $showId;

    /**
     * @var string
     *
     * @ORM\Column(name="image_detail", type="text", length=16777215, nullable=false)
     */
    private $imageDetail;

    /**
     * @var string
     *
     * @ORM\Column(name="image_1", type="string", length=150, nullable=false)
     */
    private $image1;

    /**
     * @var string
     *
     * @ORM\Column(name="image_2", type="string", length=150, nullable=false)
     */
    private $image2;

    /**
     * @var string
     *
     * @ORM\Column(name="image_3", type="string", length=150, nullable=false)
     */
    private $image3;

    /**
     * @var string
     *
     * @ORM\Column(name="image_activity", type="string", length=150, nullable=false)
     */
    private $imageActivity;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

    /**
     * @var string
     *
     * @ORM\Column(name="sale_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $salePrice;

    /**
     * @var string
     *
     * @ORM\Column(name="mark_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $markPrice;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank_weight", type="smallint", nullable=false)
     */
    private $rankWeight;


}
