<?php

namespace App\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class OddsField implements FieldInterface
{
    use FieldTrait;

    public const OPTION_MAP_PROVIDER = 'mapProvider';

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            // this template is used in 'index' and 'detail' pages
           // ->setTemplatePath('admin/field/map.html.twig')
            // this is used in 'edit' and 'new' pages to edit the field contents
            // you can use your own form types too
            ->setFormType(CollectionType::class)
            ->addCssClass('field-map')
            // these methods allow to define the web assets loaded when the
            // field is displayed in any CRUD page (index/detail/edit/new)
            ->addCssFiles('js/admin/field-map.css')
            ->addJsFiles('js/admin/field-map.js')
            ->setCustomOption(self::OPTION_MAP_PROVIDER, 'openstreetmap')

            ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            // ...
            ArrayField::new('odds'),

        ];
    }


    public function useGoogleMaps(): self
    {
        $this->setCustomOption(self::OPTION_MAP_PROVIDER, 'google');

        return $this;
    }

    public function useOpenStreetMap(): self
    {
        $this->setCustomOption(self::OPTION_MAP_PROVIDER, 'openstreetmap');

        return $this;
    }
}
