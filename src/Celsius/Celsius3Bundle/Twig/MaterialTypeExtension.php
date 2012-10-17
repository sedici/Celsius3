<?php

namespace Celsius\Celsius3Bundle\Twig;

use Celsius\Celsius3Bundle\Document\MaterialType;

class MaterialTypeExtension extends \Twig_Extension
{

    private $environment;
    private $templates = array(
        'BookType' => '_book.html.twig',
        'CongressType' => '_congress.html.twig',
        'JournalType' => '_journal.html.twig',
        'PatentType' => '_patent.html.twig',
        'ThesisType' => '_thesis.html.twig',
    );

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'render_material' => new \Twig_Function_Method($this, 'renderMaterial'),
        );
    }

    public function renderMaterial(MaterialType $material)
    {
        $class = explode('\\', get_class($material));
        $className = end($class);

        return $this->environment->render('CelsiusCelsius3Bundle:MaterialType:' . $this->templates[$className], array(
                    'materialData' => $material,
                ));
    }

    public function getName()
    {
        return 'material_type_extension';
    }

}