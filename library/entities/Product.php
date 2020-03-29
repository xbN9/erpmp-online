<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table(name="product", indexes={@ORM\Index(name="brand_id", columns={"brand_id"}), @ORM\Index(name="show_id", columns={"status", "show_id"}), @ORM\Index(name="category_id", columns={"category_id"}), @ORM\Index(name="status", columns={"status"}), @ORM\Index(name="is_showgoodsname", columns={"is_showgoodsname"})})
 * @ORM\Entity
 */
class Product
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
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     */
    private $name;

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
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank_weight", type="smallint", nullable=false)
     */
    private $rankWeight;

    /**
     * @var integer
     *
     * @ORM\Column(name="rank_sentiment", type="smallint", nullable=false)
     */
    private $rankSentiment;

    /**
     * @var string
     *
     * @ORM\Column(name="introduce", type="string", length=300, nullable=false)
     */
    private $introduce;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_time", type="datetime", nullable=false)
     */
    private $createTime;

    /**
     * @var string
     *
     * @ORM\Column(name="create_user", type="string", length=25, nullable=false)
     */
    private $createUser;

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

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="delete_time", type="datetime", nullable=false)
     */
    private $deleteTime;

    /**
     * @var string
     *
     * @ORM\Column(name="delete_user", type="string", length=25, nullable=false)
     */
    private $deleteUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="onshelf_time", type="datetime", nullable=false)
     */
    private $onshelfTime;

    /**
     * @var string
     *
     * @ORM\Column(name="onshelf_user", type="string", length=25, nullable=false)
     */
    private $onshelfUser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="offshelf_time", type="datetime", nullable=false)
     */
    private $offshelfTime;

    /**
     * @var string
     *
     * @ORM\Column(name="offshelf_user", type="string", length=25, nullable=false)
     */
    private $offshelfUser;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_showgoodsname", type="boolean", nullable=false)
     */
    private $isShowgoodsname;

    /**
     * @var string
     *
     * @ORM\Column(name="img1", type="string", length=45, nullable=false)
     */
    private $img1;

    /**
     * @var string
     *
     * @ORM\Column(name="img2", type="string", length=45, nullable=false)
     */
    private $img2;

    /**
     * @var string
     *
     * @ORM\Column(name="img3", type="string", length=45, nullable=false)
     */
    private $img3;

    /**
     * @var string
     *
     * @ORM\Column(name="img_activity", type="string", length=45, nullable=false)
     */
    private $imgActivity;

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
     * @var string
     *
     * @ORM\Column(name="base_price", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $basePrice;

    /**
     * @var string
     *
     * @ORM\Column(name="fees", type="decimal", precision=10, scale=2, nullable=false)
     */
    private $fees;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_good", type="boolean", nullable=false)
     */
    private $isGood;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_zhuan", type="boolean", nullable=false)
     */
    private $isZhuan;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sheng", type="boolean", nullable=false)
     */
    private $isSheng;

    /**
     * @var string
     *
     * @ORM\Column(name="commission", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $commission;

    /**
     * @var string
     *
     * @ORM\Column(name="show_id", type="string", length=20, nullable=false)
     */
    private $showId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_sc", type="boolean", nullable=false)
     */
    private $isSc;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_box", type="boolean", nullable=false)
     */
    private $isBox;

    /**
     * @var string
     *
     * @ORM\Column(name="tag", type="string", length=20, nullable=false)
     */
    private $tag;

    /**
     * @var integer
     *
     * @ORM\Column(name="warehouse_id", type="integer", nullable=false)
     */
    private $warehouseId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_test", type="boolean", nullable=false)
     */
    private $isTest;

    /**
     * @var string
     *
     * @ORM\Column(name="common_commission", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $commonCommission;

    /**
     * @var string
     *
     * @ORM\Column(name="self_commission", type="decimal", precision=5, scale=2, nullable=false)
     */
    private $selfCommission;

    /**
     * @var boolean
     *
     * @ORM\Column(name="type", type="boolean", nullable=false)
     */
    private $type;


}
