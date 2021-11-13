netlify sites:list |  grep 'https:'
read -p "Choose your target domain (without https: & netlify.app):  " list


echo "######################## default configuration ####################
baseURL = \"https://$list.netlify.app\"
title = \"$list\"
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
description = \"$list.netlify.app is $list web reference\"
author = \"\"

# search  
search = true
# contact form action
contact_form_action = \"#\" # contact form works with https://formspree.io
# copyright
copyright = \"Copyright 2021 [$list.netlify.app](https://$list.netlify.app)\" "> config.toml


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

read -p "Category: " cat 
read -p "Start date (yyyy-mm-dd): " bd
read -p "End date (yyyy-mm-dd): " fwd
var=val printf "1\n10\n1\n$bd\n$fwd\n0\n$cat" | sh shuriken.sh &&
php shuriken export:hugo
cd deploy && hugo --buildFuture && netlify unlink && netlify link --name $list && var=val printf "public\n" | netlify deploy --prod
