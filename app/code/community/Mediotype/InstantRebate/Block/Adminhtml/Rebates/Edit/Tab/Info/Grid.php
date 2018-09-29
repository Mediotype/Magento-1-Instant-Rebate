<?php

/**
 *
 **/
class Mediotype_InstantRebate_Block_Adminhtml_Rebates_Edit_Tab_Info_Grid extends Mage_Adminhtml_Block_Widget_Grid
{

    public function __construct()
    {
        echo '<script type="text/javascript" src="https://code.jquery.com/jquery-latest.min.js">jQuery.noConflict();</script>';
        echo '<script type="text/javascript" >jQuery.noConflict();</script>';

        parent::__construct();
        $this->setId('rebate_products');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
    }


    protected function _addColumnFilterToCollection($column)
    {

        if ($column->getId() == "associated_products") {
            $productIds = $this->_getAssociatedProducts();

            if ($column->getFilter()->getValue()) {
                $this->getCollection()->addFieldToFilter('entity_id', array('in' => $productIds));
            } elseif (!empty($productIds)) {
                $this->getCollection()->addFieldToFilter('entity_id', array('nin' => $productIds));
            }
        } else {
            parent::_addColumnFilterToCollection($column);
        }

        return $this;
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('catalog/product')->getCollection()->addAttributeToSelect('*');
        $this->setCollection($collection);

        return parent::_prepareCollection();
    }



    protected function _prepareColumns()
    {

        $this->addColumn(
            'associated_products',
            array(
                'header_css_class' => 'a-center',
                'type' => 'checkbox',
                'name' => 'associated_products',
                'values' =>  $this->_getAssociatedProducts(),
                'align' => 'center',
                'index' => 'entity_id'
            )
        );

        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('catalog')->__('ID'),
                'sortable' => true,
                'width' => '60',
                'index' => 'entity_id'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header' => Mage::helper('catalog')->__('Name'),
                'sortable' => true,
                'index' => 'name'
            )
        );
        $this->addColumn(
            'sku',
            array(
                'header' => Mage::helper('catalog')->__('SKU'),
                'sortable' => true,
                'width' => '80',
                'index' => 'sku'
            )
        );

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn(
                'websites',
                array(
                    'header' => Mage::helper('catalog')->__('Websites'),
                    'width' => '100px',
                    'sortable' => false,
                    'index' => 'websites',
                    'type' => 'options',
                    'options' => Mage::getModel('core/website')->getCollection()->toOptionHash(),
                )
            );
        }

        $this->addColumn(
            'price',
            array(
                'header' => Mage::helper('catalog')->__('Price'),
                'type' => 'currency',
                'width' => '1',
                'sortable' => true,
                'currency_code' => (string)Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
                'index' => 'price'
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * This should be false on new and a model on edit
     *
     * @return bool|Mediotype_InstantRebate_Model_InstantRebate
     */
    protected function getRebateModel()
    {
        if ($rebate_id = $this->getRequest()->getParam('id')) {
            return Mage::getModel('mediotype_instant_rebate/instantRebate')->load($rebate_id);
        }
        return false;
    }

    protected function _getAssociatedProducts()
    {
        if ($rebateModel = $this->getRebateModel()) {
            return $rebateModel->getProducts();
        }
        return array();
    }

    public function getGridUrl()
    {
        return $this->getUrl('*/*/productGrid', array('_current' => true));
    }

//    protected function _afterToHtml($html)
//    {
//        return $this->_prependHtml() . parent::_afterToHtml($html) . $this->_appendHtml();
//    }

//    private function _prependHtml()
//    {
//
//        $html =
//            <<<JAVASCRIPT
//
//	<script type="text/javascript">
//	jQuery( function() {
//
//		var pid;
//	    var prid = jQuery("#products");
//	    var resetBtn;
//
//	    jQuery( 'button.scalable' ).each( function() {
//		if( jQuery( 'span', this ).html() == "Reset Filter" )
//		    jQuery( this ).click( function() {
//			prid.val( '' );
//		    })
//		    .ajaxComplete( function() {
//			console.log( 'request completed' );
//		    });
//	    });
//
//	    if( prid.val() === '' ) {
//			pid = new Array();
//			jQuery('input:checkbox').each( function() {
//				if( this.checked && this.value != "on" )
//				pid.push( this.value );
//			});
//			pid.sort();
//	    } else {
//			pid = prid.val().split( ',' );
//		}
//
//	    jQuery('input:checkbox').click(function() {
//			var val = this.value;
//			if( this.value === "on" && this.checked === true){
//				jQuery('input:checkbox:checked').each(function(i) {
//					if( this.checked && this.value != "on" ) {
//						pid.push( this.value );
//					}
//				});
//			} else if( this.value === "on" && this.checked === false){
//				pid = new Array();
//			} else {
//				if( this.checked === true && jQuery.inArray( this.value, pid ) == -1 ) {
//					pid.push( parseInt(this.value) );
//					pid.sort();
//				} else {
//					pid = jQuery.grep( pid, function( value ) {
//						return value !== val;
//					});
//				}
//			}
//			prid.val( pid.join( ',' ) );
//		});
//
//	    prid.val( pid.join( ',' ) );
//	    console.log( prid.val() );
//	});
//	</script>
//JAVASCRIPT;
//
//        return $html;
//    }

//    private function _appendHtml()
//    {
//        $html = '<script type="text/javascript">	</script>';
//        return $html;
//    }

}
