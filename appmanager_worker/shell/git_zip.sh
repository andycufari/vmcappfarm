#!/bin/bash
cd $1
zip -r $2 *
mv $2 ../
cd ../../