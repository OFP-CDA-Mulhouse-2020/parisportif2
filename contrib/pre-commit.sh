#!/usr/bin/env bash

C_RESET='\e[0m'
C_RED='\e[31m'
C_GREEN='\e[32m'
C_YELLOW='\e[33m'

function __run() #(step, name, cmd)
{
    local color output exitcode

    printf "${C_YELLOW}[%s]${C_RESET} %-20s" "$1" "$2"
    output=$(eval "$3" 2>&1)
    exitcode=$?

    if [[ 0 == $exitcode || 130 == $exitcode ]]; then
        echo -e " - Status : ${C_GREEN} OK !${C_RESET}"
    else
        if [ $1 == "1/2" ]; then
            echo -e " - Status : ${C_YELLOW} SAVE MODIFICATION !${C_RESET}\n\n$output"
        else
            echo -e " - Status : ${C_RED} ERROR DETECTED !${C_RESET}\n\n$output"
        fi
        exit 1
    fi
}

modified="git diff --diff-filter=M --name-only --cached  | grep \".php$\""
ignore="vendor"
phpcbf="./vendor/bin/phpcbf --report=code --colors --report-width=80 --standard=PSR12 --encoding=utf-8 --ignore=${ignore} -n -p"
phpcs="./vendor/bin/phpcs --report=code --colors --report-width=80 --standard=PSR12 --encoding=utf-8 --ignore=${ignore} -n -p"
phpstan="./vendor/bin/phpstan analyse -c phpstan.neon"


__run "1/3" "Code Sniffer : Correct PSR12 coding standard violations" "${modified} | xargs -r ${phpcbf}"
__run "2/3" "Code Sniffer : Detect last violations of PSR12 coding standard" "${modified} | xargs -r ${phpcs}"
__run "3/3" "PHPStan : Static code analysis" "${modified} | xargs -r ${phpstan}"
