#!/bin/bash
PS3='Shurikeners4, Pilih Job Yang Akan Anda Lalukan, Semoga berhasil: '
mods=("Create Netlify Site" "News Trend Tier 1 EN Deploy Netlify" "News Trend SPAIN Deploy Netlify" "Keywords txt Deploy Netlify" "Keywords txt Deploy Netlify cross sitemap" "Keywords txt RANDOM Deploy Netlify Site" "Keywords txt Custom Domain Deploy Netlify Site" "Keywords txt Custom domain, Scrape Sampai Siap hugo untuk diUpload ke Self Hosting" "Deploy 1st upload Github " "Deploy Github updated" "Edit Ulang Deploy Netlify" "Clean" "Quit")
echo Simple Shuriken 4 Hugo Menu:
echo ====================================
echo
select fav in "${mods[@]}"; do
    case $fav in
        "Create Netlify Site")
            echo "$fav Gaaaass!" && sh createnetlify.sh
        # optionally call a function or run some code here
            ;;
        "News Trend Tier 1 EN Deploy Netlify")
            echo "$fav Gaaaass!" && sh config.sh && sh autotier1en.sh && sh autodeploynetlify.sh
        # optionally call a function or run some code here
            ;;
        "News Trend SPAIN Deploy Netlify")
            echo "$fav Gaaaass!" && sh config.sh && sh autospanish.sh && sh autodeploynetlify.sh
        # optionally call a function or run some code here
            ;;
        "Keywords txt Deploy Netlify")
            echo "$fav Gaaaass!" && sh config.sh && sh auto.sh && sh autodeploynetlify.sh
        # optionally call a function or run some code here
            ;;
        "Keywords txt Deploy Netlify cross sitemap")
            echo "$fav Gaaaass!" && sh config.sh && sh auto.sh && sh genhugo.sh && sh mvsitemap.sh && cd deploy/crosssitemap/public && sh autogithub1
        # optionally call a function or run some code here
            ;;
        "Keywords txt RANDOM Deploy Netlify Site")
            echo "$fav Gaaaass!" && sh autorandomnetlify.sh
        # optionally call a function or run some code here
            ;;
        "Keywords txt Custom Domain Deploy Netlify Site")
            echo "$fav Gaaaass!" && sh customdomain.sh && sh auto.sh && sh autodeploynetlify.sh
        # optionally call a function or run some code here
            ;;
        "Keywords txt Custom domain, Scrape Sampai Siap hugo untuk diUpload ke Self Hosting")
            echo "$fav Gaaaass!" && sh customdomain.sh && sh auto.sh && sh genhugo.sh
        # optionally call a function or run some code here
            ;;
        "Deploy 1st upload Github ")
            echo "$fav Gaaaass!" && sh autogithub1.sh
        # optionally call a function or run some code here
            ;;
        "Deploy Github updated")
            echo "$fav Gaaaass!" && sh autogithub2.sh
        # optionally call a function or run some code here
            ;;
        "Edit Ulang Deploy Netlify")
            echo "$fav Gaaaass!" && php Shuriken export:hugo && sh autodeploynetlify.sh
        # optionally call a function or run some code here
            ;;
        "Clean")
            echo "Gaaaass, $fav." && sh clean.sh  
        # optionally call a function or run some code here
        break
            ;;
        "Quit")
        echo "User requested exit"
        exit
        ;;
        *) echo "invalid option $REPLY";;
    esac
done