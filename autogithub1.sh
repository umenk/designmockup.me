cd deploy/crosssitemap/public
git init
read -p "nama github user anda: " user 
git config --global user.name "$user"
git add . 
DATE=$(date)
git commit -m "$user is processing 1st upload on $DATE" 
read -p "nama repo : " repo 
git remote add origin https://$user@github.com/$user/$repo.git
git push -u origin master