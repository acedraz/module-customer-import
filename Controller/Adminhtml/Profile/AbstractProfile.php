<?php
/**
 * ACedraz
 *
 * @category  ACedraz
 * @package   CustomerImport
 * @version   1.0.1
 * @author    Aislan Cedraz <aislan.cedraz@gmail.com.br>
 */

declare(strict_types=1);

namespace ACedraz\CustomerImport\Controller\Adminhtml\Profile;

use Magento\Backend\App\Action;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;

/**
 * Class AbstractProfile
 * @package ACedraz\CustomerImport\Controller\Adminhtml\Profile
 */
abstract class AbstractProfile extends Action implements HttpGetActionInterface
{
    /** @var string */
    const ADMIN_RESOURCE = 'ACedraz_CustomerImport::customerimport';

    /**
     * Init page.
     * @param Page $resultPage
     * @return Page
     */
    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
            ->addBreadcrumb(__('Profile Map'), __('Profile Map'))
            ->addBreadcrumb(__('Profile'), __('Profile'));
        return $resultPage;
    }
}
