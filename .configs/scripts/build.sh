#!/bin/sh

cd ../../

mkdir ./build/wp-express
mkdir ./build/wp-express/assets

cp -R ./assets/dist ./build/wp-express/assets
cp -R ./classes ./build/wp-express
cp ./autoload.php ./build/wp-express

find ./build/wp-express/classes -type f -name "*.spec.php" -exec rm -f {} \;

cd ./build
zip -r wp-express.zip ./wp-express
rm -rf ./wp-express
