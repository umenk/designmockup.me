#!/bin/bash

read -p "Masukkan alamat lengkap (tanpa https) custom domain anda: "  customdomain

read -p "Masukkan nama site custom domain anda (boleh kapital dan pisah): "  sitename

echo "######################## default configuration ####################
baseURL = \"https://$customdomain\"
title = \"$sitename\"
theme = \"jala\"
languageName = \"En\"
languageCode = \"en-us\"
# post pagination
paginate = \"10\"
# disqus short name
disqusShortname = \"\" # get your shortname form here : https://disqus.com

############################# output ##############################
[outputs]
home = [ \"HTML\", \"RSS\", \"json\"]

# render unsafe html
[markup.goldmark.renderer]
unsafe = true

############################# Plugins ##############################
# CSS Plugins
[[params.plugins.css]]
link = \"plugins/bootstrap/bootstrap.min.css\"
[[params.plugins.css]]
link = \"plugins/themify-icons/themify-icons.css\"

# JS Plugins
[[params.plugins.js]]
link = \"plugins/jQuery/jquery.min.js\"
[[params.plugins.js]]
link = \"plugins/bootstrap/bootstrap.min.js\"
[[params.plugins.js]]
link = \"plugins/search/fuse.min.js\"
[[params.plugins.js]]
link = \"plugins/search/mark.js\"
[[params.plugins.js]]
link = \"plugins/search/search.js\"


################################ menu ##################################
[[menu.main]]
name = \"Home\"
url = \"/\"
weight = 1

[[menu.main]]
name = \"About\"
url = \"about\"
weight = 2

[[menu.main]]
name = \"Contact\"
url = \"contact\"
weight = 3

[[menu.main]]
name = \"Privacy Policy\"
url = \"privacy-policy\"
weight = 4

[[menu.main]]
name = \"DMCA\"
url = \"dmca\"
weight = 5


#################### default parameters ################################
[params]
logo = \"\"
# Meta data
description = \"$customdomain is $sitename web reference\"
author = \"\"

# search  
search = true
# contact form action
contact_form_action = \"#\" # contact form works with https://formspree.io
# copyright
copyright = \"Copyright 2021 [$customdomain](https://$customdomain)\" "> config.toml


echo "
# Preloader
[params.preloader]
enable = false
preloader = \"\" # use jpg, png, svg or gif format.

# cookies
[params.cookies]
enable = true
expire_days = 2 " >> config.toml
mv ../V42/config.toml ../V42/deploy/config.toml