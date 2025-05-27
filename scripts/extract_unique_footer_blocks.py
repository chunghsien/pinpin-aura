#!/usr/bin/env python3
import os
import re
import zipfile
from bs4 import BeautifulSoup

SOURCE_DIR = "./storage/lezada/footers/output"
OUTPUT_DIR = "./storage/lezada/footers/output/unqi_div"
ZIP_FILE = "unique_footer_blocks.zip"

os.makedirs(OUTPUT_DIR, exist_ok=True)

PATTERNS = [
    re.compile(r'\bfooter-container\b.*\bfooter-(one|two)\b'),
    re.compile(r'\bfooter\b.*\bfooter--(three|four)\b'),
]

# 用來記錄已經輸出的 class signature
unique_class_map = {}

count = 0
for root, _, files in os.walk(SOURCE_DIR):
    for file in files:
        if file.endswith((".html", ".htm", ".php")):
            full_path = os.path.join(root, file)
            with open(full_path, 'r', encoding='utf-8', errors='ignore') as f:
                soup = BeautifulSoup(f, 'html.parser')
                for div in soup.find_all("div", class_=True):
                    class_list = div.get("class")
                    if not class_list:
                        continue
                    class_str = ' '.join(class_list).strip()
                    # 比對是否符合 footer 範圍
                    if any(p.search(class_str) for p in PATTERNS):
                        # 如果已處理過該 class 組合就略過
                        if class_str not in unique_class_map:
                            out_filename = f"footer_{count}.html"
                            out_path = os.path.join(OUTPUT_DIR, out_filename)
                            with open(out_path, 'w', encoding='utf-8') as out_file:
                                out_file.write(str(div))
                            unique_class_map[class_str] = out_path
                            count += 1

# 壓縮匯出
with zipfile.ZipFile(ZIP_FILE, 'w') as zipf:
    for file in os.listdir(OUTPUT_DIR):
        zipf.write(os.path.join(OUTPUT_DIR, file), arcname=file)

print(f"✅ 共匯出 {count} 個唯一 footer blocks 至：{ZIP_FILE}")
