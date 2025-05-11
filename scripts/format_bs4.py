#!/usr/bin/env python3
# -*- coding: utf-8 -*-

import sys
import os
from bs4 import BeautifulSoup, Tag

# ✅ 檢查是否啟用 Python 虛擬環境（venv）
if 'VIRTUAL_ENV' not in os.environ:
    print("⚠️ 警告：未偵測到虛擬環境 (VIRTUAL_ENV)，可能導致模組錯誤（如找不到 bs4）", file=sys.stderr)

if len(sys.argv) < 2:
    print("Usage: python format_bs4.py <input_html_file>", file=sys.stderr)
    sys.exit(1)

input_path = sys.argv[1]

# 讀取輸入的 HTML 檔案內容
with open(input_path, 'r', encoding='utf-8') as f:
    html_doc = f.read()

# 使用 lxml 作為解析器解析 HTML
soup = BeautifulSoup(html_doc, 'lxml')

# 優先找 body 內容，如果沒有就 fallback 到整棵 soup
main = soup.body or soup

# 輸出 prettify 結果（只輸出內部標籤，不加 html/body）
for tag in main.contents:
    if str(tag).strip():
        if isinstance(tag, Tag):
            print(tag.prettify(), end="")
        else:
            print(str(tag), end="")
