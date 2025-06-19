import os
import zipfile
from bs4 import BeautifulSoup

SOURCE_DIR = "storage/lezada/headers/formatted"
EXPORT_ZIP = "storage/lezada/headers/unique_headers.zip"

def collect_unique_header_files(source_dir):
    header_class_map = {}
    for root, _, files in os.walk(source_dir):
        for file in files:
            if file.endswith(".php"):
                full_path = os.path.join(root, file)
                with open(full_path, 'r', encoding='utf-8', errors='ignore') as f:
                    soup = BeautifulSoup(f, 'html.parser')
                    header_tag = soup.find('header')
                    if header_tag and header_tag.has_attr('class'):
                        class_signature = ' '.join(header_tag['class']).strip()
                        if class_signature not in header_class_map:
                            header_class_map[class_signature] = full_path
    return header_class_map

def export_unique_files_to_zip(file_map, output_zip_path):
    with zipfile.ZipFile(output_zip_path, 'w') as zipf:
        for path in file_map.values():
            arcname = os.path.basename(path)
            zipf.write(path, arcname)
    print(f"✅ 匯出完成，共 {len(file_map)} 筆檔案 → {output_zip_path}")

if __name__ == "__main__":
    result = collect_unique_header_files(SOURCE_DIR)
    if result:
        export_unique_files_to_zip(result, EXPORT_ZIP)
    else:
        print("⚠️ 沒有找到任何包含 <header class=\"...\"> 的檔案。")
