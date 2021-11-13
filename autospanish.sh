read -p "Start date (yyyy-mm-dd): " bd
read -p "End date (yyyy-mm-dd): " fwd
var=val printf "1\n10\n1\n$bd\n$fwd\np30\nArgentina" | sh shuriken.sh &&
var=val printf "1\n10\n1\n$bd\n$fwd\np38\nChile" | sh shuriken.sh &&
var=val printf "1\n10\n1\n$bd\n$fwd\np32\nColombia" | sh shuriken.sh &&
var=val printf "1\n10\n1\n$bd\n$fwd\np26\nSpain" | sh shuriken.sh &&
php shuriken export:hugo