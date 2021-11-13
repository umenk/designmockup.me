read -p "Start date (yyyy-mm-dd): " bd
read -p "End date (yyyy-mm-dd): " fwd
var=val printf "10\n1\n$bd\n$fwd\np9\nEngland" | sh shuriken.sh &&
var=val printf "10\n1\n$bd\n$fwd\np1\nUSA" | sh shuriken.sh &&
var=val printf "10\n1\n$bd\n$fwd\np8\nAustralia" | sh shuriken.sh &&
var=val printf "10\n1\n$bd\n$fwd\np13\nCanada" | sh shuriken.sh &&
php shuriken export:hugo