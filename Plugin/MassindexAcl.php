<?php
/**
 * WebTechnologyCodes
 *
 * Do not edit or add to this file if you wish to upgrade to newer versions in the future.
 * If you wish to customize this module for your needs.
 * Please contact us https://magentoextensions.in
 *
 * @category   WebTechnologyCodes
 * @package    WebTechnologyCodes_BackendReindexer
 * @copyright  Copyright (C) 2018 WebTechnologyCodes LLP (https://magentoextensions.in)
 * @license    https://magentoextensions.in
 */


namespace WebTechnologyCodes\BackendReindexer\Plugin;

/**
 * Class MassindexAcl
 */
class MassindexAcl
{
    /**
     * @var \Magento\Framework\AuthorizationInterface
     */
    protected $authorization;

    /**
     * @param \Magento\Framework\AuthorizationInterface $authorization
     */
    public function __construct(\Magento\Framework\AuthorizationInterface $authorization)
    {
        $this->authorization = $authorization;
    }

    /**
     * @param \Magento\Indexer\Block\Backend\Grid\ItemsUpdater $subject
     * @param $argument
     *
     * @return mixed
     */
    public function afterUpdate(
        \Magento\Indexer\Block\Backend\Grid\ItemsUpdater $subject,
        $argument
    )
    {
        if ($this->authorization->isAllowed('WebTechnologyCodes_BackendReindexer::reindex') === false) {
            unset($argument['reindex']);
        }

        return $argument;
    }
}