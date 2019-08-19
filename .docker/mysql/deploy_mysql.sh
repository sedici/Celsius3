#!/bin/bash

# Update host
mysql --user=celsius3_usr --password=celsius3_pass celsius3 -e "UPDATE instance SET host='prebi.localhost' WHERE url='prebi';"



