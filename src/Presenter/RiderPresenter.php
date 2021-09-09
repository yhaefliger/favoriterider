<?php
/**
 * Copyright since 2007 PrestaShop SA and Contributors
 * PrestaShop is an International Registered Trademark & Property of PrestaShop SA
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License 3.0 (AFL-3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/AFL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to https://devdocs.prestashop.com/ for more information.
 *
 * @author    PrestaShop SA and Contributors <contact@prestashop.com>
 * @copyright Since 2007 PrestaShop SA and Contributors
 * @license   https://opensource.org/licenses/AFL-3.0 Academic Free License 3.0 (AFL-3.0)
 */

declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Presenter;

use PrestaShop\Module\FavoriteRider\Entity\Rider;
use PrestaShop\PrestaShop\Core\Foundation\Templating\PresenterInterface;

class RiderPresenter implements PresenterInterface
{

  /**
   * Return array of formatted rider fields for template
   *
   * @param Rider $rider
   * @param bool|string $images specific image size or all images / none
   * 
   * @return array
   */
  public function present($rider, $images = true): array
  {
    $data = [
      'name' => $rider->getName(),
      'discipline' => $rider->getDiscipline(),
      'votes' => (int) $rider->getVotes(),
    ];

    if (true === $images) {
      $data['image'] = $rider->getAllImages();
    } elseif (is_string($images)) {
      $data['image'] = $rider->getImageUrl($images);
    }

    return $data;
  }  
}