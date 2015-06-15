#!/bin/bash

cd tmp/
mkdir $1
cd $1
mv ../$2 ./
unzip $2
rm -Rf $2
echo "Ya descomprimi..."
vmc target $3
echo "target...."
vmc login --email $4 --passwd $5
echo "update..."
#vmc update $1
#vmc start $1
cd ..
rm -Rf $1
