#!/bin/bash
# filepath: convert_controllers.sh

target_dir="src/Controller/Web"
pattern="Controller.php"

function convert_file() {
    local file=$1
    # Get entity name from filename (remove Admin and Controller)
    entity=$(basename "$file" | sed 's/Admin//' | sed 's/Controller\.php//')
    
    echo "Converting $file..."
    
    # Create new content
    cat > "$file.new" << EOF
<?php

/*
 * Celsius3 - $entity HTML controller
 * Copyright (C) 2014 PREBI-SEDICI <info@prebi.unlp.edu.ar> http://prebi.unlp.edu.ar http://sedici.unlp.edu.ar
 *
 * This file is part of Celsius3.
 *
 * Celsius3 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Celsius3 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Celsius3.  If not, see <http://www.gnu.org/licenses/>.
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

    /**
     * Lists all $entity entities.
     * @Route("/", name="admin_$(echo $entity | tr '[:upper:]' '[:lower:]')")
     */
    public function indexHandler(): Response
    { return parent::htmlIndex(); }

    /**
     * Displays a form to create a new $entity entity.
     * @Route("/new", name="admin_$(echo $entity | tr '[:upper:]' '[:lower:]')_new")
     */
    public function newHandler(): Response
    { return parent::htmlNew(); }

    /**
     * Creates a new $entity entity.
     * @Route("/create", name="admin_$(echo $entity | tr '[:upper:]' '[:lower:]')_create", methods={"POST"})
     */
    public function createHandler(): Response
    { return parent::htmlCreate(); }

    /**
     * Displays a form to edit an existing $entity entity.
     * @Route("/{id}/edit", name="admin_$(echo $entity | tr '[:upper:]' '[:lower:]')_edit")
     * @param string \$id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function editHandler(\$id): Response
    { return parent::htmlEdit(\$id); }

    /**
     * Edits an existing $entity entity.
     * @Route("/{id}/update", name="admin_$(echo $entity | tr '[:upper:]' '[:lower:]')_update", methods={"POST"})
     * @param string \$id The entity ID
     * @throws NotFoundHttpException If entity doesn't exists
     */
    public function updateHandler(\$id): Response
    { return parent::htmlUpdate(\$id); }
}
EOF

    # Replace old file with new one
    mv "$file.new" "$file"
    echo "Converted $file"
}

# Check if specific file was provided
if [ $# -eq 1 ]; then
    if [ -f "$1" ]; then
        convert_file "$1"
    else
        echo "File $1 not found"
        exit 1
    fi
else
    # Process all controller files in directory
    for file in ${target_dir}/*${pattern}; do
        if [ -f "$file" ]; then
            convert_file "$file"
        fi
    done
fi