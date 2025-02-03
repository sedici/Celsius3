#!/bin/bash

target_dir="src/Controller/Web"
pattern="Controller.php"
valid_controllers="index,show,new,create,edit,update,delete"

function parse_controller() {
    local method=$(echo $1 | cut -d':' -f1)
    local inst_dep=true
    
    if [[ $1 == *":f"* ]]; then
        inst_dep=false
    fi
    
    echo "$method|$inst_dep"
}

function generate_controller() {
    local entity=$1
    local controller_spec=$2
    
    # Parse controller spec
    local parsed=($(parse_controller "$controller_spec" | tr '|' ' '))
    local controller=${parsed[0]}
    local inst_dep=${parsed[1]}
    
    # Add instance dependence param if false
    local dep_param=""
    if [ "$inst_dep" = false ]; then
        dep_param=", isInstanceDependence: false"
    fi
    
    case $controller in
        "index")
            echo "    /**
     * Lists all $entity entities.
     * @Route(\"/\", name=\"admin_$(echo $entity | tr '[:upper:]' '[:lower:]')\")
     */
    public function indexHandler(): Response
    { return parent::htmlIndex($dep_param); }"
            ;;
        "show")
            echo "    /**
     * Shows $entity entity.
     * @Route(\"/{id}\", name=\"admin_$(echo $entity | tr '[:upper:]' '[:lower:]')_show\")
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function showHandler(\$id): Response
    { return parent::htmlShow(\$id$dep_param); }"
            ;;
        "new")
            echo "    /**
     * Displays form to create new $entity entity.
     * @Route(\"/new\", name=\"admin_$(echo $entity | tr '[:upper:]' '[:lower:]')_new\")
     */
    public function newHandler(): Response
    { return parent::htmlNew($dep_param); }"
            ;;
        "create")
            echo "    /**
     * Creates new $entity entity.
     * @Route(\"/create\", name=\"admin_$(echo $entity | tr '[:upper:]' '[:lower:]')_create\", methods={\"POST\"})
     */
    public function createHandler(): Response
    { return parent::htmlCreate($dep_param); }"
            ;;
        "edit")
            echo "    /**
     * Displays form to edit $entity entity.
     * @Route(\"/{id}/edit\", name=\"admin_$(echo $entity | tr '[:upper:]' '[:lower:]')_edit\")
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function editHandler(\$id): Response
    { return parent::htmlEdit(\$id$dep_param); }"
            ;;
        "update")
            echo "    /**
     * Updates $entity entity.
     * @Route(\"/{id}/update\", name=\"admin_$(echo $entity | tr '[:upper:]' '[:lower:]')_update\", methods={\"POST\"})
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function updateHandler(\$id): Response
    { return parent::htmlUpdate(\$id$dep_param); }"
            ;;
        "delete")
            echo "    /**
     * Deletes $entity entity.
     * @Route(\"/{id}/delete\", name=\"admin_$(echo $entity | tr '[:upper:]' '[:lower:]')_delete\")
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function deleteHandler(\$id): Response
    { return parent::htmlDelete(\$id$dep_param); }"
            ;;
    esac
}

function convert_file() {
    local file=$1
    local controllers=$2
    entity=$(basename "$file" | sed 's/Admin//' | sed 's/Controller\.php//')
    
    echo "Converting $file..."
    
    # Start file content
    cat > "$file.new" << EOF
<?php

/*
 * Celsius3 - $entity HTML controller
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 */

namespace Celsius3\Controller\Html;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Celsius3\Entity\\$entity;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * $entity HTML controller.
 * @Route("/admin/$(echo $entity | tr '[:upper:]' '[:lower:]')")
 */
class Admin${entity}Controller extends HtmlEntityController
{
    final protected function getEntity(): string
    { return ${entity}::class; }

    protected function getSortDefaults(): array
    {
        return [
            'defaultSortFieldName' => 'e.name',
            'defaultSortDirection' => 'asc',
        ];
    }

EOF

    # Add requested controllers
    IFS=',' read -ra CTRL <<< "$controllers"
    for c in "${CTRL[@]}"; do
        if [[ $valid_controllers =~ $c ]]; then
            generate_controller "$entity" "$c" >> "$file.new"
            echo "" >> "$file.new"
        fi
    done

    # Close class
    echo "}" >> "$file.new"

    mv "$file.new" "$file"
    echo "Converted $file successfully"
}

# Validate input
if [ $# -lt 2 ]; then
    echo "Usage: $0 <file|directory> <controllers>"
    echo "Valid controllers: $valid_controllers"
    echo "Example: $0 src/Controller/Web/AdminCountryController.php \"index:f,edit,update:f\""
    exit 1
fi

# Process file or directory
if [ -f "$1" ]; then
    convert_file "$1" "$2"
elif [ -d "$1" ]; then
    for file in "$1"/*${pattern}; do
        [ -f "$file" ] && convert_file "$file" "$2"
    done
else
    echo "Invalid path: $1"
    exit 1
fi