#!/bin/bash
# انسخ هذا السكريبت في ملف باسم subdomain_scan.sh

echo "جاري فحص النطاقات الفرعية لـ: $1"

# استخدام multiple tools
echo "1. Using subfinder..."
subfinder -d $1 -o subfinder.txt

echo "2. Using assetfinder..."
assetfinder $1 > assetfinder.txt

echo "3. Using amass..."
amass enum -passive -d $1 -o amass.txt

# دمج النتائج وإزالة التكرار
cat subfinder.txt assetfinder.txt amass.txt | sort -u > all_subdomains.txt

echo "تم العثور على $(wc -l all_subdomains.txt) نطاق فرعي"
echo "النتائج في: all_subdomains.txt"

