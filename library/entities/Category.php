<?php
namespace Entities;


use Doctrine\ORM\Mapping as ORM;

/**
 * Category
 *
 * @ORM\Table(name="category", indexes={@ORM\Index(name="INDEX_ID", columns={"index_id"}), @ORM\Index(name="INDEX_PARENT_ID", columns={"parent_id"})})
 * @ORM\Entity
 */
class Category
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
     * @ORM\Column(name="parent_id", type="integer", nullable=false)
     */
    private $parentId;

    /**
     * @var boolean
     *
     * @ORM\Column(name="level", type="boolean", nullable=false)
     */
    private $level;

    /**
     * @var string
     *
     * @ORM\Column(name="category_name", type="string", length=200, nullable=false)
     */
    private $categoryName;

    /**
     * @var boolean
     *
     * @ORM\Column(name="category_type", type="boolean", nullable=true)
     */
    private $categoryType;

    /**
     * @var string
     *
     * @ORM\Column(name="index_id", type="string", length=255, nullable=true)
     */
    private $indexId;

    /**
     * @var string
     *
     * @ORM\Column(name="keywords", type="string", length=200, nullable=true)
     */
    private $keywords;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_keywords", type="string", length=200, nullable=true)
     */
    private $seoKeywords;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=true)
     */
    private $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_show", type="boolean", nullable=false)
     */
    private $isShow;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_delete", type="boolean", nullable=false)
     */
    private $isDelete;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="createtime", type="datetime", nullable=true)
     */
    private $createtime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updatetime", type="datetime", nullable=true)
     */
    private $updatetime;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="deletetime", type="datetime", nullable=true)
     */
    private $deletetime;


}
