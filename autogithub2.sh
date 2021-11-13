cd deploy/crosssitemap/public
git fetch origin master
git pull origin master
git add . 
DATE=$(date)
git commit -m "updated on $DATE" 
read -p "nama github user anda: " user 
read -p "nama repo : " repo 
git remote add origin https://$user@github.com/$user/$repo.git
git push -u origin master