<?php
use Yaf\Dispatcher;

class Admin_ProductController extends Admin_BaseController {
    /**
     * 设置模板引擎路径
     */
    public function init() {
        parent::init();
    }

    public function listAction() {
    
        $this->data['title'] = [
            ['name' => '排序', 'len' => ''],
            ['name' => '商品编号', 'len' => ''],
            ['name' => 'ID', 'len' => ''],
            ['name' => '商品名称', 'len' => ''],
            ['name' => '缩略图', 'len' => ''],
            ['name' => '总库存', 'len' => ''],
            ['name' => '分类', 'len' => ''],
            ['name' => '品牌', 'len' => ''],
            ['name' => '描述', 'len' => ''],
            ['name' => '好货', 'len' => ''],
            ['name' => '供应商', 'len' => ''],
            ['name' => '创建时间', 'len' => ''],
            ['name' => '操作', 'len' => ''],
        ];
        $this->data['add_product']     = '/product/add';
        $this->data['addmuil_product'] = '/product/import';
        $this->data['add_box']         = '/product/addBox';
        $this->data['sAjaxSource']     = '/product/listData';
        //商品分类
        $this->data['cate'] = CategoryModel::getList();
        //供应商
        $this->data['supplier'] = WarehouseProductModel::getSuppiler();
        $this->getView()->assign('data', $this->data);
    }

    public function listDataAction() {
        Dispatcher::getInstance()->autoRender(0);
        $start = microtime(true);

        $size = Helper::getParameter($this->getRequest()->getPost('size'));
        if (empty($size) || $size > 50) {
            $size = 15;
        }
        $page  = Helper::getParameter($this->getRequest()->getPost('page'));
        $where = [
            'product.is_test' => 0,
        ];
        $product_name     = urldecode(Helper::getParameter($this->getRequest()->getPost('product_name')));
        $product_type     = Helper::getParameter($this->getRequest()->getPost('product_type'),2);
        $product_status   = Helper::getParameter($this->getRequest()->getPost('product_status'), 1);
        $product_category = Helper::getParameter($this->getRequest()->getPost('product_category'), 2);
        $product_supplier = Helper::getParameter($this->getRequest()->getPost('product_supplier'), 2);
        
        if (isset($product_status) && $product_status != '') {
            $where['product.status'] = $product_status;
        }
        if (!empty($product_name)) {
            $where['product.name'] = $product_name;
        }
        
        $where['product.is_box'] = 0;
        if (isset($product_type) && $product_type != '') {
            $where['product.is_box'] = $product_type;
        }
       
        if (isset($product_category) && $product_category != '') {
            $where['product.category_id'] = $product_category;
        }
        if (isset($product_supplier) && $product_supplier != '') {
            $where['supplier'] = $product_supplier;
        }
        $columns = [
            'id',
        ];
     
        $totalRow = ProductModel::totalRow($where, $columns);
        if (empty($totalRow)) {
            $end                               = microtime(true);
            $this->response_data['message']    = 'data is empty';
            $this->response_data['error_code'] = 1;
            $this->response_data['e_time']     = bcsub($end, $start, 4);
            Helper::response($this->response_data);
            return false;
        }

        $totalPage = ceil($totalRow / $size);
        if ($page < 2) {
            $offset = 0;
        } else {
            $offset = ($page - 1) * $size;
        }
        $columns = [
            'product.id', 'product.name', 'c.category_name', 'b.name as brand_name',
            'product.status', 'product.rank_weight', 'product.introduce',
            'product.create_time', 'product.img1', 'product.show_id',
            'product.tag', 'product.warehouse_id', 'product.is_good',
        ];
        $product = ProductModel::getList($where, $columns, $offset, $size);
        if (empty($product)) {
            $end                               = microtime(true);
            $this->response_data['message']    = 'data is empty';
            $this->response_data['error_code'] = 1;
            $this->response_data['e_time']     = bcsub($end, $start, 4);
            Helper::response($this->response_data);
            return false;
        } else {
            $pidArr = array_column($product, 'id');
            $where  = [
                'product_id' => $pidArr,
            ];
            $warehouse = WarehouseProductModel::getProductStock($where, ['product_id', 'stock']);
            $whArr     = array_unique(array_column($product, 'warehouse_id'));
            $wsp       = WarehouseProductModel::getProductSuppiler($whArr);
            foreach ($product as &$value) {
                $value['stock']       = 0;
                $value['img1']        = !empty(trim($value['img1'])) ? 'http://img.meidaowu.com' . $value['img1'] : '';
                $value['create_time'] = substr($value['create_time'], 0, 10);
                $value['is_good']     = !empty($value['is_good']) ? '是' : '否';
                $where                = [
                    'product_id' => $value['id'],
                    'status'     => 1,
                ];
                if (!empty($warehouse)) {
                    foreach ($warehouse as $wpval) {
                        if ($wpval['product_id'] == $value['id']) {
                            $value['stock'] = $wpval['stock'];
                        }
                    }
                }
                $value['supplier'] = '';
                if (!empty($wsp)) {
                    foreach ($wsp as $wspval) {
                        if ($wspval['id'] == $value['warehouse_id']) {
                            $value['supplier'] = $wspval['name'];
                        }
                    }
                }
            }
            $data['list']  = $product;
            $data['total'] = $totalPage;
            $data['page']  = $page;
            $data['where'] = [
                'product_name'     => isset($product_name) ? $product_name : '',
                'product_type'     => isset($product_type) ? $product_type : '',
                'product_status'   => isset($product_status) ? $product_status : '',
                'product_category' => isset($product_category) ? $product_category : '',
                'product_supplier' => isset($product_supplier) ? $product_supplier : '',
            ];
            $end                            = microtime(true);
            $this->response_data['message'] = 'success';
            $this->response_data['data']    = $data;
            $this->response_data['e_time']  = bcsub($end, $start, 4);
            Helper::response($this->response_data);
            return false;
        }
    }

    public function addAction() {
       
    }
    
     public function addDoAction() {
        Dispatcher::getInstance()->autoRender(0);
    }

    public function editAction() {
        Dispatcher::getInstance()->autoRender(0);
    }

}
