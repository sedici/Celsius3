<?php

namespace Celsius3\CoreBundle\Twig;

use Celsius3\CoreBundle\Document\MaterialType;

class MaterialTypeExtension extends \Twig_Extension
{

    private $environment;
    private $materials = array(
        'BookType' => array(
            'template' => '_book.html.twig',
            'class' => 'book',
        ),
        'CongressType' => array(
            'template' => '_congress.html.twig',
            'class' => 'congress',
        ),
        'JournalType' => array(
            'template' => '_journal.html.twig',
            'class' => 'journal',
        ),
        'PatentType' => array(
            'template' => '_patent.html.twig',
            'class' => 'patent',
        ),
        'ThesisType' => array(
            'template' => '_thesis.html.twig',
            'class' => 'thesis',
        ),
    );

    private function getClassName(MaterialType $material)
    {
        $class = explode('\\', get_class($material));

        return end($class);
    }

    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    public function getFunctions()
    {
        return array(
            'render_material' => new \Twig_Function_Method($this, 'renderMaterial'),
            'get_material_type' => new \Twig_Function_Method($this, 'getMaterialType'),);
    }

    public function renderMaterial(MaterialType $material)
    {
        return $this->environment->render('Celsius3CoreBundle:MaterialType:' . $this->materials[$this->getClassName($material)]['template'], array(
                    'materialData' => $material,
        ));
    }

    public function getMaterialType(MaterialType $material)
    {
        return $this->materials[$this->getClassName($material)]['class'];
    }

    public function getName()
    {
        return 'celsius3_core.material_type_extension';
    }

}
