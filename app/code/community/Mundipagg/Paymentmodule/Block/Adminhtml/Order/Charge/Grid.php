<?php
 
class Mundipagg_Paymentmodule_Block_Adminhtml_Order_Charge_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('order_charge_grid');
        $this->setFilterVisibility(false);
        $this->setPagerVisibility(false);
        $this->setSaveParametersInSession(true);
    }

    public function getRowUrl($row){
       return false;
    }
 
    protected function _prepareCollection()
    {
        $orderId = Mage::app()->getRequest()->getParam('order_id');

        $collection = Mage::getResourceModel('sales/order_collection')
            ->join(['b' => 'sales/order_payment'],
                'main_table.entity_id = b.parent_id',
                ['additional_information' => 'additional_information']
            )
            ->addFieldToFilter('main_table.entity_id', $orderId);

        $collection = $this->createChargeCollection($collection);
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    protected function createChargeCollection($collection)
    {
        $aditional = [];
        foreach ($collection as $order) {
            $aditional = unserialize($order->additional_information);
        }

        $collection = new Varien_Data_Collection();
        array_walk($aditional['mundipagg_payment_module_charges'],
            function ($item) use ($collection) {
                $item['amount'] = $item['amount'] / 100;

                $rowObj = new Varien_Object();
                $rowObj->setData($item);
                $collection->addItem($rowObj);
            }
        );

        return $collection;
    }
 
    protected function _prepareColumns()
    {
        $helper = Mage::helper('paymentmodule/order');
        $currency = (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE);
 
        $this->addColumn('id', array(
            'header' => $helper->__('Charge Id'),
            'index'  => 'id',
            'filter' => false,
            'sortable'  => false,
        ));
 
        $this->addColumn('amount', array(
            'header' => $this->__('Amount'),
            'index'  => 'amount',
            'type'   => 'currency',
            'currency_code' => $currency,
            'filter' => false,
            'sortable'  => false,
        ));
 
        $this->addColumn('status', array(
            'header' => $helper->__('Status'),
            'index'  => 'status',
            'filter' => false,
            'sortable'  => false,
        ));
 
        $this->addColumn('payment_method', array(
            'header' => $this->__('Payment Method'),
            'index'  => 'payment_method',
            'filter' => false,
            'sortable'  => false,
        ));
/*
        $this->addColumn('action_capture', array(
            'header' => $helper->__(''),
            'width'     => '5%',
            'type'      => 'action',
            'getter'     => 'getId',
            'actions'   => array(
                    array(
                        'caption' => Mage::helper('sales')->__('Capturar'),
                        'onclick' => 'javascript();',
                        'field'   => 'id'
                    )
                ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ));

        $this->addColumn('action_cancel', array(
            'header' => $helper->__(''),
            'width'     => '5%',
            'type'      => 'action',
            'getter'     => 'getId',
            'actions'   => array(
                    array(
                        'caption' => Mage::helper('sales')->__('Cancelar'),
                        'onclick' => 'javascript();',
                        'field'   => 'id'
                    )
                ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true,
        ));
 */
        return parent::_prepareColumns();
    }
 
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }
}
