#!/usr/bin/env python3
import os
import re
import zipfile
from bs4 import BeautifulSoup

# === 路徑設定 ===
SOURCE_DIR = "./storage/lezada/footers/formatted"
OUTPUT_DIR = "./storage/lezada/footers/output"
ZIP_FILE = "footer_blocks.zip"

# === 要比對的 class 組合 ===
PATTERNS = [
    re.compile(r'\bfooter-container\b.*\bfooter-(one|two)\b'),
    re.compile(r'\bfooter\b.*\bfooter--(three|four)\b'),
]

# === 建立輸出資料夾 ===
os.makedirs(OUTPUT_DIR, exist_ok=True)

# === 主邏輯 ===
count = 0
for root, _, files in os.walk(SOURCE_DIR):
    for file in files:
        if file.endswith((".html", ".htm", ".php")):
            full_path = os.path.join(root, file)
            with open(full_path, 'r', encoding='utf-8', errors='ignore') as f:
                soup = BeautifulSoup(f, 'html.parser')
                for div in soup.find_all("div", class_=True):
                    class_str = ' '.join(div.get("class"))
                    if any(pattern.search(class_str) for pattern in PATTERNS):
                        out_filename = f"{os.path.splitext(file)[0]}_footer_{count}.html"
                        out_path = os.path.join(OUTPUT_DIR, out_filename)
                        with open(out_path, 'w', encoding='utf-8') as out_file:
                            out_file.write(str(div))
                        count += 1

# === 壓縮成 zip ===
with zipfile.ZipFile(ZIP_FILE, 'w') as zipf:
    for file in os.listdir(OUTPUT_DIR):
        zipf.write(os.path.join(OUTPUT_DIR, file), arcname=file)

print(f"✅ 擷取完成，共 {count} 個符合條件的 <div> 已壓縮至：{ZIP_FILE}")
