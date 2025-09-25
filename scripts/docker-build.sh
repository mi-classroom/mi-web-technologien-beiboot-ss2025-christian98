#!/bin/bash
set -e

while getopts f:i:t:v:p: flag
do
    case "${flag}" in
        # Dockerfile name
        f) file=${OPTARG};;
        i) image=${OPTARG};;
        t) tag=${OPTARG};;
        v) version=${OPTARG};;
        p) push=${OPTARG};;
        *) exit 1;;
    esac
done

docker build \
  --cache-from "$image":development \
  -f "$file" \
  --build-arg APP_VERSION="$version" \
  -t "$tag" .


if [ "$push" == 'true' ]; then
    echo "Pushing docker image: $image"
    docker image push "$image" --all-tags
else
    echo "Skipping pushing docker image: $image"
fi
