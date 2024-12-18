#!/bin/bash

# Ruta del archivo .env
ENV_FILE=".env"

# Expresión regular para capturar variables en formato clave=valor (comentadas o no)
rgx_cmnt="#"
# rgx_varname="([a-zA-Z_]\w*)"
rgx_varname="([a-zA-Z_][a-zA-Z0-9_.-]*)"
rgx_value="(.*)"

cmnt="#"
eq="="

rgx="^\s*?$rgx_cmnt?\s*?$rgx_varname\s*?$eq\s*?$rgx_value\s*"
rgx_commented="^\s*?$rgx_cmnt\s*?$rgx_varname\s*?$eq\s*?$rgx_value\s*"
rgx_not_commented="^\s*?$rgx_varname\s*?$eq\s*?$rgx_value\s*"

# Verifica si el archivo existe
check_file_exists() {
  ENV_FILE="$1"
  if [[ ! -f "$ENV_FILE" ]]; then
    echo "El archivo $ENV_FILE no existe."
    exit 1
  fi
}

# Carga todas las variables con su línea asociada
declare -A env_vars
declare -A env_lines

load_env_vars() {
  check_file_exists "$1"
  local line_num=1
  while IFS= read -r line || [[ -n "$line" ]]; do
    if [[ "$line" =~ $rgx ]]; then
      key="${BASH_REMATCH[1]}"
      value="${BASH_REMATCH[2]}"
      env_vars["$key"]="$value"
      env_lines["$key"]="$line_num"
    fi
    ((line_num++))
  done < "$ENV_FILE"
}

# Lista todas las variables
list_all_vars() {
  printf "\nTodas las variables encontradas:\n\n"
  for key in "${!env_vars[@]}"; do
    echo "$key=${env_vars[$key]}"
  done
  printf "\n"
}

# Lista variables no comentadas
list_uncommented_vars() {
  printf "\nVariables no comentadas:\n\n"
  grep -P $rgx_not_commented "$ENV_FILE"
  printf "\n"
}

# Escapa caracteres especiales en sed
escape_sed() {
  echo "$1" | sed -e 's/[\/&]/\\&/g'
}

# Modifica el valor de una variable
modify_env_var() {
  local var_name="$1"
  local new_value="$2"

  if [[ -v env_vars["$var_name"] ]]; then
    # Verificar que el nuevo valor cumple con el regex
    if [[ "$new_value" =~ $rgx_value ]]; then
      local line_num="${env_lines[$var_name]}"
      local escaped_value=$(escape_sed "$new_value")
      sed -i "${line_num}s/.*/$var_name$eq$escaped_value/" "$ENV_FILE"
      echo "Variable '$var_name' actualizada a '$new_value'."
    else
      echo "El nuevo valor '$new_value' no es válido."
    fi
  else
    echo "La variable '$var_name' no existe en el archivo."
  fi
}

# Agrega una nueva variable
add_new_var() {
  local var_name="$1"
  local value="$2"
  
  if [[ -v env_vars["$var_name"] ]]; then
    echo "La variable '$var_name' ya existe. Usa la opción de modificar."
  else
    { [[ -z $(tail -n 1 "$ENV_FILE") ]] || printf "\n"; } >> "$ENV_FILE"
    echo "$var_name=$value" >> "$ENV_FILE"
    echo "Variable '$var_name' agregada con el valor '$value'."
  fi
}

# Hace toggle de comentar/descomentar una variable
toggle_comment_var() {
  local var_name="$1"
  
  if [[ -v env_vars["$var_name"] ]]; then
    if grep -Pq "^\s*$rgx_cmnt\s*$var_name\s*$eq\s*$rgx_value" "$ENV_FILE"; then
      # Si está comentada, descomentar
      sed -i "s/^\s*$cmnt\s*\($var_name\s*$eq\)/\1/" "$ENV_FILE"
      echo "Variable '$var_name' descomentada."
    else
      # Si está descomentada, comentar
      sed -i "s/^\($var_name\s*$eq\)/$cmnt \1/" "$ENV_FILE"
      echo "Variable '$var_name' comentada."
    fi
  else
    echo "La variable '$var_name' no existe."
  fi
}

# Elimina una variable
delete_env_var() {
  local var_name="$1"

  if [[ -v env_vars["$var_name"] ]]; then
    # Obtener la línea de la variable
    local line_num="${env_lines[$var_name]}"
    # Eliminar la línea del archivo
    sed -i "${line_num}d" "$ENV_FILE"
    unset env_vars["$var_name"]  # Eliminar de la estructura
    unset env_lines["$var_name"]

    # Recalcular las líneas de las variables restantes
    # load_env_vars # Solo necesario si el programa no es de ejecución única

    echo "Variable '$var_name' eliminada."
  else
    echo "La variable '$var_name' no existe."
  fi
}

# Muestra la ayuda
help() {
  echo "Uso: $0 <envfile> <opción> [argumentos...]"
  echo "Opciones:"
  echo "  la                   Listar todas las variables"
  echo "  l 	               Listar variables no comentadas"
  echo "  m <var> <valor>      Modificar el valor de una variable"
  echo "  a <var> <valor>      Agregar una nueva variable"
  echo "  c <var>              Comentar o descomentar una variable (toggle)"
  echo "  d <var>              Eliminar variable"
}

[ "$2" == "" ] && help

load_env_vars "$1"

# Procesar los parámetros
case "$2" in
  la)
    list_all_vars
    ;;
  
  l)
    list_uncommented_vars
    ;;

  m)
    if [[ -n "$3" && -n "$4" ]]; then
      modify_env_var "$3" "$4"
    else
      echo "Uso: $0 m <var> <nuevo_valor>"
    fi
    ;;

  a)
    if [[ -n "$3" && -n "$4" ]]; then
      add_new_var "$3" "$4"
    else
      echo "Uso: $0 a <var> <valor>"
    fi
    ;;

  c)
    if [[ -n "$3" ]]; then
      toggle_comment_var "$3"
    else
      echo "Uso: $0 c <var>"
    fi
    ;;

  d)
    if [[ -n "$3" ]]; then
      delete_env_var "$3"
    else
      echo "Uso: $0 d <var>"
    fi
    ;;

  *)
    help
    ;;
esac

