#!/bin/bash
nohup bash OnPayTwoHour.sh > /dev/null &
nohup bash OnPayFiveMinute.sh > /dev/null &
nohup bash OnPayThreeMinute.sh > /dev/null &
nohup bash OnPayTwoMinute.sh > /dev/null &
nohup bash OnPayOneMinute.sh > /dev/null &
nohup bash Notify.sh > /dev/null  &  
