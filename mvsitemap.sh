netlify sites:list |  grep 'url:' > sini.txt
sed -i -e 's#  url:  https://##g' sini.txt
sed -i -e 's/.netlify.app//g' sini.txt
inisini=`head -n 1 sini.txt` 
mv ../V42/deploy/public/sitemap.xml ../V42/deploy/crosssitemap/public/"$inisini"_sitemap.xml
netlify unlink && netlify link --name $inisini && var=val printf "deploy/public\n" | netlify deploy --prod