#!/bin/sh

# Go to dir
cd `dirname "$0"`
cd ..

# preapre data dir
mkdir -p data
rm data/invitation-list-write.json

# Read and execute
chmod 755	class/*
chmod 755	css/*
chmod 755	js/*
chmod 755 	*.php
chmod 755	*.html

# Only execute
chmod 754 	class
chmod 754 	js
chmod 754 	css
chmod 754	data
chmod 754 	config.php
chmod 754	check-mailbox.php

# Read and write
chmod 753 	data/*

# Nothing
