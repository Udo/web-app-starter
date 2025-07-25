#!/usr/bin/env bash

csensitive=false
patterns=()

# Define search paths
declare -a php_sea=("lib" "views" "components")
declare -a css_sea=("theme" "views")
declare -a js_sea=("js")

while [[ $# -gt 0 ]]; do
    case "$1" in
        -c|--case-sensitive)
            csensitive=true
            shift
            ;;
        -h|--help)
            echo "Usage: $0 [-c|--case-sensitive] <pattern> [<pattern>...]" >&2
            exit 1
            ;;
        -* )
            echo "Unknown option: $1" >&2
            echo "Usage: $0 [-c|--case-sensitive] <pattern> [<pattern>...]" >&2
            exit 1
            ;;
        * )
            patterns+=("$1")
            shift
            ;;
    esac
done

if (( ${#patterns[@]} == 0 )); then
    echo "Usage: $0 [-c|--case-sensitive] <pattern> [<pattern>...]" >&2
    exit 1
fi

grep_opts=( -nR --color=tty )
if ! $csensitive; then
    grep_opts+=( -i )
fi

pattern_opts=()
for pat in "${patterns[@]}"; do
    pattern_opts+=( -e "$pat" )
done

for vs in "${php_sea[@]}"; do
    grep "${grep_opts[@]}" --include="*.php" "${pattern_opts[@]}" "$vs"
done

for vs in "${css_sea[@]}"; do
    grep "${grep_opts[@]}" --include="*.css" "${pattern_opts[@]}" "$vs" \
        | grep -iv "fontawesome"
done

for vs in "${js_sea[@]}"; do
    grep "${grep_opts[@]}" --include="*.js" "${pattern_opts[@]}" "$vs"
done
