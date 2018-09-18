<?php
/**
 * Created by PhpStorm.
 * @author Danilo Cavalcanti de Moura
 * Email: danilo-cm@hotmail.com
 */

namespace Fcamara\Ebit\Block\Html;

use \Magento\Framework\View\Element\Template;
use \Magento\Framework\View\Element\Template\Context;
use \Fcamara\Ebit\Helper\Data;
use \Magento\Checkout\Model\Session;

class Ebit extends Template
{
    const XML_PATH_ENABLE_BANNER = 'ebit/general/enable_banner';
    const XML_PATH_ENABLE_SELO = 'ebit/general/enable_selo';
    const XML_PATH_STORE = 'ebit/general/store';
    const XML_PATH_BUSCAPE_ID = 'ebit/general/buscape_id';
    const XML_PATH_LIGHTBOX = 'ebit/general/lightbox';

    protected $_helper;

    /**
     * Ebit constructor.
     * @param Context $context
     * @param Data $helper
     */
    public function __construct(Context $context, Data $helper)
    {
        $this->_helper = $helper;
        parent::__construct($context);
    }

    /**
     * @todo Return banner is active
     * @return mixed
     */
    public function isEnableBanner()
    {
        return $this->_helper->getConfigValue(self::XML_PATH_ENABLE_BANNER);
    }

    /**
     * @todo Return if selo is active
     * @return mixed
     */
    public function isEnableSelo()
    {
        return $this->_helper->getConfigValue(self::XML_PATH_ENABLE_SELO);
    }

    /**
     * @todo Return get store configured from ebit site
     * @return mixed
     */
    public function getStoreEbit()
    {
        return $this->_helper->getConfigValue(self::XML_PATH_STORE);
    }

    /**
     * @todo Return get buscape id configured from ebit site
     * @return mixed
     */
    public function getBuscapeId()
    {
        return $this->_helper->getConfigValue(self::XML_PATH_BUSCAPE_ID);
    }

    /**
     * @return mixed
     */
    public function showLightbox()
    {
        return $this->_helper->getConfigValue(self::XML_PATH_LIGHTBOX);
    }


    /**
     * @return string
     */
    public function getValueEbitParam()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $session = $objectManager->get('Magento\Checkout\Model\Session');

        $lastOrder = $session->getLastRealOrder();

        if (!$lastOrder) {
            return;
        }

        $shippingAddress = $lastOrder->getShippingAddress();

        $value = 'email=' . $lastOrder->getCustomerEmail() . '';
        $value .= '&gender=' . $lastOrder->getCustomerGender() . '';
        $value .= '&birthDay=' . $lastOrder->getCustomerDob() . '';
        $value .= '&zipCode=' . $shippingAddress->getPostCode() . '';
        $value .= '&deliveryTax=' . $lastOrder->getShippingInclTax() . '';
        $value .= '&totalSpent=' . $lastOrder->getGrandTotal() . '';
        $value .= '&value=' . $lastOrder->getGrandTotal() . '';

        $qty  = [];
        $name = [];
        $sku  = [];

        $i = 0;

        foreach ($lastOrder->getItemsCollection() as $item) {
            $qty[$i] = $item->getQtyOrdered();
            $name[$i] = $item->getName();
            $sku[$i] = $item->getSku();

            $i++;
        }

        $value .= '&quantity=' . implode('|', $qty) . '';
        $value .= '&productName=' . implode('|', $name) . '';
        $value .= '&sku=' . implode('|', $sku) . '';
        $value .= '&transactionId=' . $lastOrder->getIncrementId() . '';
        $value .= '&buscapeId=' . $this->getBuscapeId() . '';
        $value .= '&storeId=' . $this->getStoreEbit() . '';
        $value .= '&mktSaleID=0';

        return trim($value);
    }
}