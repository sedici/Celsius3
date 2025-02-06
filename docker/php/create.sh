#!/bin/bash

usage="Uso: $0 <nombre-proyecto> <version-symfony>"

# Verifica que se especifique el nombre del proyecto
if [ -z "$1" ]; then
    echo "No se ha especificado el nombre del proyecto"
    echo $usage
    exit 1
fi

# Verifica que se especifique la versión de Symfony
if [ -z "$2" ]; then
    echo "No se ha especificado la versión de Symfony"
    echo $usage
    exit 1
fi

# Verifica que la versión de Symfony sea mayor o igual a 4 (esta forma de crear un proyecto es a partir de la v4)
if ! [[ "$2" =~ ^[0-9]+(\.[0-9]+)*$ ]] || (( $(echo "$2" | cut -d. -f1) < 4 )); then
    echo "La versión de Symfony debe ser 4 o superior"
    echo $usage
    exit 1
fi

project_name=$1
symfony_version=$2

symfony new $project_name --full --version=$symfony_version

if [ -f "./docker/php/sym.dep" ]; then
    sym_deps=$(paste -sd' ' "./docker/php/sym.dep")
    [ -z "$sym_deps" ] || composer require $sym_deps
else
    echo "El archivo ./docker/php/sym.dep no existe"
fi

if [ -f "./docker/php/dev.dep" ]; then
    dev_deps=$(paste -sd' ' "./docker/php/dev.dep")
    [ -z "$dev_deps" ] || composer require --dev $dev_deps
else
    echo "El archivo ./docker/php/dev.dep no existe"
fi