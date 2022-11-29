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

namespace WebTechnologyCodes\BackendReindexer\Controller\Adminhtml\Indexer;

/**
 * Class MassReindex
 *
 * @package WebTechnologyCodes\BackendReindexer\Controller\Adminhtml\Indexer
 */
class MassReindex extends \Magento\Backend\App\Action
{
    /**
     * ACL resource
     */
    const ADMIN_RESOURCE = 'WebTechnologyCodes_BackendReindexer::reindex';

    /**
     * @var \Magento\Framework\Indexer\IndexerRegistry
     */
    protected $indexerRegistry;

    public function __construct(
        \Magento\Framework\Indexer\IndexerRegistry $indexerRegistry,
        \Magento\Backend\App\Action\Context $context
    ){
        $this->indexerRegistry = $indexerRegistry;
        return parent::__construct($context);
    }


    public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');
        $this->reindexAll($indexerIds);

        $this->_redirect('indexer/indexer/list');
    }

    /**
     * @param integer[] $indexerIds
     *
     * @return bool
     */
    protected function reindexAll(array $indexerIds) : bool
    {
        if (!is_array($indexerIds)) {
            $this->messageManager->addErrorMessage(__('Please select one or two indices.'));
            return false;
        }

        foreach ($indexerIds as $indexerId) {
            $this->reindex($indexerId);
        }

        return true;
    }

    /**
     * @param string $indexerId
     */
    protected function reindex(string $indexerId)
    {
        $startTime = microtime(true);

        try {
            /** @var \Magento\Indexer\Model\Indexer $indexer */
            $indexer = $this->indexerRegistry->get($indexerId);
            $indexer->reindexAll();
            $totalTime = microtime(true) - $startTime;
            $totalTime = round($totalTime, 2);

            $message = sprintf(__('%s was reindexed in %s seconds'), $indexer->getTitle(), $totalTime);
            $this->messageManager->addSuccessMessage($message);

        } catch (\Magento\Framework\Exception\LocalizedException $e) {
            $message = sprintf(__('%s indexer process exception'), $indexer->getTitle());
            $this->messageManager->addErrorMessage($message, $e);

        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, $e->getMessage());
        }
    }

    /**
     * @return bool
     */
    protected function _isAllowed() : bool
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }
}
