<?php
/**
 * Orange Management
 *
 * PHP Version 8.0
 *
 * @package   Modules\ProjectManagement
 * @copyright Dennis Eichhorn
 * @license   OMS License 1.0
 * @version   1.0.0
 * @link      https://orange-management.org
 */
declare(strict_types=1);

$footerView   = new \phpOMS\Views\PaginationView($this->l11nManager, $this->request, $this->response);
$footerView->setTemplate('/Web/Templates/Lists/Footer/PaginationBig');
$footerView->setPages(20);
$footerView->setPage(1);

$list = $this->getData('projects');

echo $this->getData('nav')->render(); ?>

<div class="row">
    <div class="col-xs-12">
        <div class="portlet">
            <div class="portlet-head"><?= $this->getHtml('Projects'); ?><i class="fa fa-download floatRight download btn"></i></div>
            <table class="default sticky">
                <thead>
                <tr>
                    <td class="wf-100"><?= $this->getHtml('Title'); ?>
                    <td><?= $this->getHtml('Start'); ?>
                    <td><?= $this->getHtml('Due'); ?>
                <tbody>
                <?php $count = 0; foreach ($list as $key => $value) : ++$count;
                $url         = \phpOMS\Uri\UriFactory::build('{/prefix}projectmanagement/profile?{?}&id=' . $value->getId()); ?>
                <tr tabindex="0" data-href="<?= $url; ?>">
                    <td data-label="<?= $this->getHtml('Title'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->name); ?></a>
                    <td data-label="<?= $this->getHtml('Start'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->getStart()->format('Y-m-d')); ?></a>
                    <td data-label="<?= $this->getHtml('Due'); ?>"><a href="<?= $url; ?>"><?= $this->printHtml($value->getEnd()->format('Y-m-d')); ?></a>
                <?php endforeach; ?>
                <?php if ($count === 0) : ?>
                <tr><td colspan="5" class="empty"><?= $this->getHtml('Empty', '0', '0'); ?>
                        <?php endif; ?>
            </table>
        </div>
    </div>
</div>
