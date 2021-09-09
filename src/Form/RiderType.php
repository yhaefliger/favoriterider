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

namespace PrestaShop\Module\FavoriteRider\Form;

use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RiderType extends TranslatorAwareType
{
  /**
   * {@inheritDoc}
   */
  public function buildForm(FormBuilderInterface $builder, array $options)
  {
    $builder
      ->add('name', TextType::class, [
        'label' => 'Rider name',
        'translation_domain' => 'Modules.FavoriteRider.Admin',
        'constraints' => [
          new Length([
            'max' => 64,
            'maxMessage' => $this->trans(
              'This field cannot be longer than %limit% characters',
              'Admin.Notifications.Error',
              ['%limit%' => 64]
            ),
          ]),
          new NotBlank(),
        ]
      ])
      ->add('discipline', TextType::class, [
        'label' => 'Rider discipline',
        'translation_domain' => 'Modules.FavoriteRider.Admin',
        'constraints' => [
          new Length([
            'max' => 255,
            'maxMessage' => $this->trans(
              'This field cannot be longer than %limit% characters',
              'Admin.Notifications.Error',
              ['%limit%' => 255]
            ),
          ]),
          new NotBlank(),
        ]
      ])
      ->add('image', FileType::class, [
        'label' => 'Rider Photo',
        'translation_domain' => 'Modules.FavoriteRider.Admin',
        'required' => false,
      ]);
  }
}