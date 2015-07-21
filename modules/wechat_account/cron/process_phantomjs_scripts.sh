# do not edit and save this file under NetBean
# NetBean adds "\r" which is not loved by shell
DIR="`dirname \"$0\"`"              # relative
DIR="`( cd \"$DIR\" && pwd )`"  # absolutized and normalized
i=0
for entry in $DIR/../../../files/cache/phantomjs_scripts/*
do
  if [ $i -lt 5 ]
  then
    sh $entry
    rm $entry
    i=`expr $i + 1`
  fi
done
