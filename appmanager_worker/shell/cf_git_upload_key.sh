#!/bin/bash
echo "EJECUTANDO GIT"

export HOME=$PWD
GIT_SSH=./shell/ssh_git_key git clone $1 $2 
#ping www.google.com
#GIT_CURL_VERBOSE=1 git clone $1
#GIT_CURL_VERBOSE=1 GIT_TRACE=true git clone --verbose $1 > git.log
#ls ./shell/ -l

#cd $2
#zip -r $3 *
#mv $3 ../
#cd ../../
echo "TERMINO..."

