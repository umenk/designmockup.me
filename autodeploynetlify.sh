netlify sites:list |  grep 'url:' > sini.txt
sed -i -e 's#  url:  https://##g' sini.txt
sed -i -e 's/.netlify.app//g' sini.txt
ini_sini=`head -n 1 sini.txt` 
cd deploy && hugo --buildFuture --minify && netlify unlink && netlify link --name $ini_sini && var=val printf "public\n" | netlify deploy --prod