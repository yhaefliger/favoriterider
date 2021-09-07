<?php
declare(strict_types=1);

namespace PrestaShop\Module\FavoriteRider\Form;

use PrestaShop\PrestaShop\Core\ConstraintValidator\Constraints\DefaultLanguage;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
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