read -p "Category: " cat
read -p "Start date (yyyy-mm-dd): " bd
read -p "End date (yyyy-mm-dd): " fwd 
var=val printf "10\n0\n$bd\n$fwd\n0\n$cat" | sh shuriken.sh &&
php shuriken export:hugo
