FROM node:alpine

RUN apk update \
    && apk upgrade \
    && apk add openjdk8 bash \
    && npm install newman --global \
    && rm -rf /var/cache/apk/*

