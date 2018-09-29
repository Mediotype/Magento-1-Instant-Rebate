<?php

/**
 * Class Mediotype_InstantRebate_Model_InstantRebate
 *
 * @method getProducts()
 * @method getProductSkus()
 */
class Mediotype_InstantRebate_Model_InstantRebate extends Mage_Core_Model_Abstract
{

    protected $productCollection = null;

    public function _construct()
    {
        $this->_setResourceModel('mediotype_instant_rebate/InstantRebate');
    }

    //TODO unsure if still needed
    public function getProductsToArray()
    {

        $products = $this->getProducts();

    }

    /**
     * @return bool|string Qualified URL to sponsor's logo
     * @author  Joel Hart   <joel@mediotype.com>
     */
    public function getLogoUrl()
    {
        $helper = $this->getHelper();
        return $helper->getSponsorLogoUrl($this);
    }

    /**
     * return product collection
     */
    public function getProductCollection()
    {
        /** @var  Mediotype_InstantRebate_Model_Resource_Products_Collection $collection */
        $collection = Mage::getModel('mediotype_instant_rebate/products')->getCollection();
        $collection->addFieldToFilter('instant_rebate_id', array('eq' => $this->getId()));
        $collection->setOrder('sort_order', Mediotype_InstantRebate_Model_Resource_Products_Collection::SORT_ORDER_ASC);
        $collection->load();
        $this->productCollection = $collection;

        return $this->productCollection;
    }

    /**
     * Convert Product Ids set on model to product skus set on model at time of save
     * This enables the generate or update rebate rule to have skus for association at time of first creation / etc... or just time of save
     *
     */
    protected function convertProductIdsToSkus()
    {
        $productSkus = array();
        foreach ($this->getProducts() as $productId) {
            /** @var Mage_Catalog_Model_Product $product */
            $product = Mage::getModel('catalog/product')->load($productId);
            $productSkus[] = $product->getSku();
        }
        $this->setProductSkus($productSkus);
    }

    protected function _afterSave()
    {
        $rebateProducts = $this->getProductCollection();
        $productIds = $this->getData('products');
        $productQtys = $this->getData('productQty');
        $productCost = $this->getData('productCost');
        $sortOrder = $this->getData('sortOrder');
        //save current rebate products
        if(count($rebateProducts)) {
            foreach($rebateProducts as $productModel) {
                /** @var $productModel Mediotype_InstantRebate_Model_Products */
                if(($key = array_search($productModel->getData('product_id'), $productIds)) !== false) {
                    unset($productIds[$key]);
                    //have to save the product models so they update the cart rules
                    $productModel->setData('max_instant_rebate_uses',$productQtys[$productModel->getData('product_id')]);
                    $productModel->setData('instant_rebate_amount',$productCost[$productModel->getData('product_id')]);
                    $productModel->setData('sort_order',$sortOrder[$productModel->getData('product_id')]);
                    $productModel->save();
                }
                else {
                    //this means the rebate product was removed so delete it
                    $productModel->delete();
                }
            }
        }
        //if we've any product ids left which weren't already rebate products, make them
        foreach($productIds as $productId) {
            /** @var Mage_Catalog_Model_Product $product */
            $product = Mage::getModel('catalog/product')->load($productId);
            /** @var Mediotype_InstantRebate_Model_Products $newRebateProduct */
            $newRebateProduct = Mage::getModel('mediotype_instant_rebate/products');
            $newRebateProduct->setData('product_sku',$product->getSku())
            ->setData('max_instant_rebate_uses',$productQtys[$product->getId()])
            ->setData('instant_rebate_amount',$productCost[$product->getId()])
            ->setData('sort_order',$sortOrder[$product->getId()])
            ->setData('instant_rebate_id', $this->getId())
            ->setData('product_id',$product->getId());
            $newRebateProduct->save();
        }
        return parent::_afterSave();
    }

    protected function _afterLoad()
    {
        if ($this->getId()) {
            $productIds = array();
            foreach ($this->getProductCollection() as $rebateProductModel) {
                $productIds[] = $rebateProductModel->getData('product_id');
            }
            $this->setData('products', $productIds);
        }

        return parent::_afterLoad();
    }


    /**
     * @return Mediotype_InstantRebate_Helper_Data
     */
    protected function getHelper()
    {
        return Mage::helper('mediotype_instant_rebate');
    }

    public function revalidate()
    {
        if ($this->getData('active')) {
            // VALIDATE PROGRAM AMOUNT LOGIC
            if ($maxRebateAmount = $this->getData('max_program_amount')) {
                $total = $this->getUsedAmount();
                if ((double)$total >= (double)$maxRebateAmount) {
                    $this->setData('active', 0);
                    $this->save();
                }
            }
        }

        // VALIDATE DATE RANGES
        //Filter start date is before current time stamp
        // GET CURRENT TIMESTAMP
        $currentTimeStamp = new DateTime(date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())));
        $startDate = new DateTime($this->getData('start_date'));
        if ($currentTimeStamp < $startDate) {
            $this->setData('active', 0);
            $this->save();

        }

        if (!is_null($this->getData('end_date')) && $this->getData('active')) {
            $endDate = new DateTime($this->getData('end_date'));
            if ($currentTimeStamp > $endDate) {
                $this->setData('active', 0);
                $this->save();

            }
        }
        return $this;
    }

    public function getUsedAmount()
    {
        $collection = Mage::getModel('mediotype_instant_rebate/conversions')->getCollection()
            ->addFieldToFilter('instant_rebate_id', $this->getId());

        $collection
            ->getSelect()
            ->reset('columns')
            ->columns("SUM(`main_table`.`instant_rebate_amount_applied`) as total");

        $collection->load();

        $conversionModel = $collection->getFirstItem();
        $total = $conversionModel->getData('total');
        return $total;
    }

    public function delete()
    {
        if (is_null($this->getData('shopping_cart_rule_id'))) {
            //do nothing
        } else {
            $ruleCheck = Mage::getModel('salesrule/rule')->load($this->getData('shopping_cart_rule_id'));
            if($ruleCheck->getId()){
                //delete associated shopping cart rule
                $ruleCheck->delete();
            } else {
                //do nothing
            }
        }
        return parent::delete();
    }

    public function isActive()
    {
        return (bool)$this->getData('active');
    }
}