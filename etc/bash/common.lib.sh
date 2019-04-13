#!/usr/bin/env bash

# Argument 1: Command to run
run_command()
{
    echo "> $1"

    eval "$1"
}

# Argument 1: Text
bold()
{
    echo -e "\e[1m$1\e[0;20m"
}

# Argument 1: Text
bold_green()
{
    echo -e "\e[33;1m$1\e[0;20m"
}

# Argument 1: Text
red()
{
    echo -e "\e[31m$1\e[0m"
}

# Argument 1: Text
bold_red()
{
    echo -e "\e[31;1m$1\e[0;20m"
}

# Argument 1: Text
print_error()
{
    echo -e "$(bold_red "$1")" 1>&2
}

# Argument 1: Text
print_success()
{
    echo -e "$(bold_green "$1")"
}

# Argument 1: Action
# Argument 2: Subject
print_header()
{
    echo -e "$(bold "$1"): $(bold_green "$2")"
}

# Argument 1: Text
print_info()
{
    echo "=> $1"
}

# Argument 1: Text
print_warning()
{
    echo "=> $1" 1>&2
}

# Argument 1: Array
print_array()
{
    printf " - %s\n" "$@"
}

print_empty_line()
{
    printf "\n"
}

# Argument 1: Text
exit_on_error()
{
    if [[ "$?" != "0" ]]; then
        print_error "$1"
        exit 1
    fi
}

# Argument 1: Binary name
get_binary()
{
    if [[ -x "${PROJECT_DIR}/bin/$1" ]]; then
        echo "${PROJECT_DIR}/bin/$1"
    elif [[ -x "${PROJECT_DIR}/vendor/bin/$1" ]]; then
        echo "${PROJECT_DIR}/vendor/bin/$1"
    else
        return 1
    fi
}

locate_packages()
{
    find "${PACKAGE_DIR}" -mindepth 2 -maxdepth 2 -type f -name composer.json -exec dirname "{}" \;
}

find_packages()
{
    locate_packages | package_path_to_package_name
}

package_path_to_package_name()
{
    xargs -n1 basename
}

# Argument 1: Package name
package_name_to_package_path()
{
    local package_name="$1"

    find "${PACKAGE_DIR}" -mindepth 1 -maxdepth 1 -type d -name "${package_name}"
}

# Argument 1: Package path or name
cast_package_argument_to_package_path()
{
    local package_name="$1"

    if [[ ! -d "${package_name}" ]]; then
        package_path="$(package_name_to_package_path "${package_name}")"
    fi

    if [[ -z "${package_path}" || ! -d "${package_path}" ]]; then
        return 1
    fi

    echo "${package_path}"
}