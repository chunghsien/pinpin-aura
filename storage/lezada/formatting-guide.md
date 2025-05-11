# 樣板 Header 統一格式處理規則

以下為從樣板上傳開始，我們統整出的 HTML 樣板統一處理步驟與規範：

---

## 1 統一縮排格式

- 全部使用 **4 個空格縮排**
- 將所有 `\t` tab 符號轉換為 4 個空格

---

## 2 清理註解格式

- 將多行裝飾性註解（如 `===`、`---`）轉換為簡潔格式
- 統一為：
  ```html
  <!-- Header Style 1 -->
  ```

---

## 3 移除空白行

- 刪除所有完全空白的行（包括只有空格的）

---

## 4 統一 HTML 結構格式

使用 HTML 解析器進行結構標準化（建議使用 `BeautifulSoup` 或 `tidy`）：

- 統一 HTML 標籤縮排
- 所有 HTML 屬性依照 **字母順序排列**
- 統一自閉合標籤格式（如 `<img />`）

---

## 5 檔名與版本辨識

- 根據註解內容產生檔名或識別用 `slug`
- 方便後續建立資料庫欄位或 Blade Component 名稱

---

## 🗂️ 建議目錄結構

```plaintext
/originals/               <- 原始上傳樣板
/formatted/               <- 已清除空白行 + 統一註解
/normalized/              <- 格式化結構後的標準 HTML
/components/              <- Blade component 輸出（條件參數化）
```

---

## ✅ Artisan CLI 工具建議

可以建立一支指令來批次處理：

```bash
php artisan header:normalize resources/views/headers
```

此指令會：

- 對 `.blade.php` 檔案統一縮排、清除註解裝飾、移除空白行、格式化 HTML 結構
- 可選擇輸出到對應的版本目錄

---

## 🧰 工具安裝建議

### 📦 安裝 BeautifulSoup (Python)

```bash
pip install beautifulsoup4
```

用法範例：

```python
from bs4 import BeautifulSoup

soup = BeautifulSoup(html, "html.parser")
pretty_html = soup.prettify()
```

---

### 📦 安裝 tidy（HTML Tidy）

#### Ubuntu / Debian

```bash
sudo apt install tidy
```

#### macOS (使用 Homebrew)

```bash
brew install tidy-html5
```

#### PHP 使用 tidy 擴充功能

需要安裝 PHP tidy extension：

```bash
sudo apt install php-tidy          # Ubuntu
brew install php@8.2-tidy          # macOS（可能需指定 PHP 版本）
```

用法範例：

```php
$tidy = new tidy();
$cleaned = $tidy->repairString($html, [
    'indent' => true,
    'wrap' => 0,
    'output-xhtml' => true
], 'utf8');
```