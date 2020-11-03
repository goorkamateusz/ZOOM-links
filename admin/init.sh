#!/bin/sh

# Go to dir
cd `dirname "$0"`
cd ..

# preapre data dir
mkdir -p data
rm -f data/invitation-list-write.json

# Read and execute
chmod 755	css/*
chmod 755	js/*
chmod 755	*.html

# Only execute
chmod 751 	admin/*
chmod 751 	*.php
chmod 751	class/*.php
chmod 751	class/discordmsg/*.php
chmod 741 	config.php
chmod 751 	class
chmod 751 	js
chmod 751 	css
chmod 751	data

# Read and write
chmod 766 	data/*
chmod 766 	*.json

# Read
chmod 711	*.md
chmod 711	doc/*
