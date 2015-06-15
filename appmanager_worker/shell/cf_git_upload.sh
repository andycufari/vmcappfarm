#!/bin/bash
cd tmp/
git clone $2 ./$1
cd $1
zip -r $3 *
mv $3 ../
cd ..
rm -Rf $1
cd ..
