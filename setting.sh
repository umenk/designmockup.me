netlify sites:list |  grep 'url:' > domain.txt
sed -i -e 's/  url: /baseURL = "/g' domain.txt
sed -i -e 's/" /"/g' domain.txt
sed -i -e 's/app/app"/g' domain.txt
(head -n 1 domain.txt > config.toml)

netlify sites:list | grep -o "https:\/\/[A-Za-z0-9\.\/]*" > copyright.txt
sed -i -e 's#https://#_+-[#g' copyright.txt
sed -i -e 's/.netlify.app/.netlify.app]+_-/g' copyright.txt
sed -i -e 's/.*/&- -&+/' copyright.txt
sed -i -e 's# -_+-\[#(https://#g' copyright.txt
sed -i -e 's#+_-+#)"#g' copyright.txt
sed -i -e 's#+_-\[#(#g' copyright.txt
sed -i -e 's#]+_--#]#g' copyright.txt
sed -i -e 's#_+-#copyright = "Copyright 2021 #g' copyright.txt
sed -i -e 's#])#)#g' copyright.txt
(head -n 1 copyright.txt >> config.toml)

netlify sites:list | grep -o "https:\/\/[A-Za-z0-9\.\/]*" > title.txt
sed -i -e 's#https://#title = "#g' title.txt
sed -i -e 's/.netlify.app/"/g' title.txt
(head -n 1 title.txt >> config.toml ; cat isiconfigtoml.txt >> config.toml ; sed -i '1s/^/######################## default configuration ####################\'$'\n/' config.toml ; printf '3m84\nw\n' | ex config.toml ; mv ../V42/config.toml ../V42/deploy/config.toml)
