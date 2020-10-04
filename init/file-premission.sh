#!/bin/sh

# Only execute
chmod 754 	class
chmod 754 	js
chmod 754 	css
chmod 754	data
chmod 754 	config.php
chmod 754	check-mailbox.php

# Read and execute
chmod 755	class/*
chmod 755	css/*
chmod 755	js/*
chmod 755 	index.php
chmod 755	./*.html

# Only read
chmod 751 	data/*

# Nothing
