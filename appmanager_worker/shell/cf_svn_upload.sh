#!/bin/bash
cd tmp/
if [ "$8" ]
then
	mkdir $1
fi
cd $1
svn export $3 ./ --no-auth-cache --force $4 $5 $6 $7
zip -r $2 *
mv $2 ../

cd ../../
